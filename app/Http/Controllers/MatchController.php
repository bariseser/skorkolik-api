<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\MatchEvent;
use App\Models\MatchLineup;
use App\Models\Prediction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function show(Request $request)
    {
        $date = $request->input('date');
        if (!$date) {
            $date = Carbon::today();
        } else {
            $date = Carbon::parse($date);
        }

        $startDate = $date->startOfDay();
        $endDate = $startDate->copy()->endOfDay();
        $matches = MatchModel::with([
            'league:id,name,logo,order,country_id',
            'league.country:id,name,flag',
            'homeTeam:id,name,logo',
            'awayTeam:id,name,logo',
            'predictions'
        ])
        ->join('leagues', 'matches.league_id', '=', 'leagues.id')
        ->whereBetween('matches.match_date', [$startDate, $endDate])
        ->where("leagues.active", true)
        ->orderBy('leagues.order', 'desc')
        ->orderBy('matches.match_date')
        ->select('matches.*')
        ->get();

        $groupedMatches = $matches->groupBy('league.name')->map(function ($matches, $leagueName) {
            $league = $matches->first()->league;
            $country = $matches->first()->league->country;
            return [
                'league' => [
                    'id' => $league->id,
                    'name' => $league->name,
                    'logo' => $league->logo
                ],
                'country' => $country,
                'matches' => $matches->map(function ($match) {
                    $matchData = $match->makeHidden(['league', 'predictions', 'season', 'round']);

                    if ($match->predictions) {
                        $prediction = $match->predictions;
                        $matchData['ai'] = [
                            'win_or_draw' => $prediction->win_or_draw,
                            'under_over' => $this->getUnderOverMapping($prediction->under_over),
                            'winner' => $this->getWinnerMapping($prediction->winner_id, $match->home_team_id, $match->away_team_id)
                        ];
                    } else {
                        $matchData['prediction'] = null;
                    }

                    return $matchData;
                })
            ];
        })->values();

        return response()->json([
            'success' => true,
            'date' => $date->format('Y-m-d'),
            'leagues' => $groupedMatches
        ]);
    }

    public function details($id)
    {
        $match = MatchModel::with([
            'league:id,name,logo',
            'season:id,year',
            'homeTeam:id,name,logo',
            'awayTeam:id,name,logo',
            'round:id,name'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'match' => $match
        ]);
    }

    public function events($id)
    {
        $match = MatchModel::select('id', 'home_team_id', 'away_team_id')->findOrFail($id);

        $events = MatchEvent::where('match_id', $id)
            ->with([
                'player:id,name',
                'assist:id,name'
            ])
            ->orderBy('elapsed')
            ->orderBy('extra')
            ->get();

        $homeTeamEvents = $events->where('team_id', $match->home_team_id)->values();
        $awayTeamEvents = $events->where('team_id', $match->away_team_id)->values();

        return response()->json([
            'success' => true,
            'events' => [
                'home_team' => $homeTeamEvents,
                'away_team' => $awayTeamEvents
            ]
        ]);
    }

    public function lineups($id)
    {
        $match = MatchModel::select('id', 'home_team_id', 'away_team_id')->findOrFail($id);

        $lineups = MatchLineup::where('match_id', $id)
            ->with([
                'coach:id,name',
                'players' => function ($query) {
                    $query->with([
                        'player:id,name,position,number,photo'
                    ])->orderBy('is_starter', 'desc')
                    ->orderBy('number');
                }
            ])
            ->get();

        $homeTeamLineup = $lineups->where('team_id', $match->home_team_id)->first();
        $awayTeamLineup = $lineups->where('team_id', $match->away_team_id)->first();

        return response()->json([
            'success' => true,
            'lineups' => [
                'home_team' => $homeTeamLineup,
                'away_team' => $awayTeamLineup
            ]
        ]);
    }

    public function playerStats($id)
    {
        $match = MatchModel::with([
            'playerStatistics' => function ($query) {
                $query->with([
                    'team:id,name',
                    'player:id,name'
                ]);
            }
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'player_statistics' => $match->playerStatistics
        ]);
    }

    public function teamStats($id)
    {
        $match = MatchModel::with(['teamStatistics' => function ($query) {
                $query->with('team:id,name');
            }
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'team_statistics' => $match->teamStatistics
        ]);
    }

    public function predictions($id)
    {
        $prediction = Prediction::with(['team'])
            ->where("match_id", $id)
            ->select("predictions.*")
            ->first();
        return response()->json([
            'success' => true,
            'predictions' => $prediction
        ]);
    }

    private function getWinnerMapping($winnerId, $homeTeamId, $awayTeamId): ?string
    {
        if ($winnerId === null) {
            return null;
        }

        if ($winnerId == $homeTeamId) {
            return 'MS1';
        } elseif ($winnerId == $awayTeamId) {
            return 'MS2';
        }

        return null;
    }

    private function getUnderOverMapping($underOver): ?string
    {
        if (!empty($underOver)) {
            if (strpos($underOver, '-') === 0) {
                return ltrim($underOver, '-') . ' ALT';
            }

            if (strpos($underOver, '+') === 0) {
                return ltrim($underOver, '+') . ' ÜST';
            }
            return $underOver;
        }
        return $underOver;
    }
}

<?php

use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchController;

Route::prefix('api')->group(function () {
    Route::prefix('matches')->group(function () {
        Route::get('/', [MatchController::class, 'show']);
        Route::get('/{id}', [MatchController::class, 'details']);
        Route::get('/{id}/events', [MatchController::class, 'events']);
        Route::get('/{id}/lineups', [MatchController::class, 'lineups']);
        Route::get('/{id}/player-stats', [MatchController::class, 'playerStats']);
        Route::get('/{id}/team-stats', [MatchController::class, 'teamStats']);
        Route::get('/{id}/predictions', [MatchController::class, 'predictions']);
    });

    Route::prefix('banners')->group(function () {
        Route::get('/', [BannerController::class, 'show']);
    });
});

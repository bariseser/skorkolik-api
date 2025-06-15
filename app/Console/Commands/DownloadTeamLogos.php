<?php

namespace App\Console\Commands;

use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DownloadTeamLogos extends Command
{
    protected $signature = 'teams:download-images {--batch-size=1000 : Number of logos to process per batch}';

    protected $description = 'Download team logos from API to public/images/teams directory';

    public function handle()
    {
        $batchSize = (int) $this->option('batch-size');

        $this->info("Starting team logo download process...");
        $this->info("Batch size: {$batchSize}");

        $teams = Team::whereNull('logo')->get();

        if ($teams->isEmpty()) {
            $this->warn('No teams with provider_id found in database.');
            return 0;
        }

        $this->info("Found {$teams->count()} teams to process.");

        $batches = $teams->chunk($batchSize);
        $totalBatches = $batches->count();
        $currentBatch = 1;

        foreach ($batches as $batch) {
            $this->info("Processing batch {$currentBatch}/{$totalBatches}...");

            foreach ($batch as $team) {
                $this->downloadLogo($team);
            }

            if ($currentBatch < $totalBatches) {
                $this->info("Waiting 5 seconds before next batch...");
                sleep(5);
            }

            $currentBatch++;
        }

        $this->info('Team logo download process completed!');
        return 0;
    }

    private function downloadLogo(Team $team)
    {
        try {
            if (empty($team->provider_id)) {
                $this->warn("Team '{$team->name}' has empty provider_id, skipping...");
                return;
            }

            $logoUrl = "https://media.api-sports.io/football/teams/{$team->provider_id}.png";
            $this->line("Downloading logo for: {$team->name}");
            $this->line("URL: {$logoUrl}");

            $response = Http::timeout(30)->get($logoUrl);

            if (!$response->successful()) {
                $this->error("Failed to download logo for '{$team->name}': HTTP {$response->status()}");
                return;
            }

            $contentType = $response->header('Content-Type');
            if (!$this->isValidImageType($contentType)) {
                $this->warn("Invalid image type for '{$team->name}': {$contentType}");
                return;
            }

            $filename = "{$team->id}.png";

            $publicPath = public_path('images/teams');
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            $filePath = $publicPath . '/' . $filename;
            file_put_contents($filePath, $response->body());

            $team->update(['logo' => $filename]);

            $this->info("✓ Saved: {$filename}");
            $this->info("✓ Updated database logo field: {$filename}");

        } catch (\Exception $e) {
            $this->error("Error downloading logo for '{$team->name}': " . $e->getMessage());
        }
    }

    private function isValidImageType(?string $contentType): bool
    {
        if (!$contentType) {
            return false;
        }

        $validTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml'
        ];

        return in_array(strtolower($contentType), $validTypes);
    }
}

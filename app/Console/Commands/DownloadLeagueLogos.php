<?php

namespace App\Console\Commands;

use App\Models\League;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DownloadLeagueLogos extends Command
{
    protected $signature = 'leagues:download-logos {--batch-size=1000 : Number of logos to process per batch}';

    protected $description = 'Download league logos from database URLs to public/images/leagues directory';

    public function handle()
    {
        $batchSize = (int) $this->option('batch-size');

        $this->info("Starting league logo download process...");
        $this->info("Batch size: {$batchSize}");

        $leagues = League::whereNotNull('provider_id')
            ->where('provider_id', '!=', '')
            ->get();

        if ($leagues->isEmpty()) {
            $this->warn('No leagues with provider_id found in database.');
            return 0;
        }

        $this->info("Found {$leagues->count()} leagues to process.");

        $batches = $leagues->chunk($batchSize);
        $totalBatches = $batches->count();
        $currentBatch = 1;

        foreach ($batches as $batch) {
            $this->info("Processing batch {$currentBatch}/{$totalBatches}...");

            foreach ($batch as $league) {
                $this->downloadLogo($league);
            }

            if ($currentBatch < $totalBatches) {
                $this->info("Waiting 5 seconds before next batch...");
                sleep(5);
            }

            $currentBatch++;
        }

        $this->info('League logo download process completed!');
        return 0;
    }

    private function downloadLogo(League $league)
    {
        try {
            if (empty($league->provider_id)) {
                $this->warn("League '{$league->name}' has empty provider_id, skipping...");
                return;
            }

            $logoUrl = "https://media.api-sports.io/football/leagues/{$league->provider_id}.png";
            $this->line("Downloading logo for: {$league->name}");
            $this->line("URL: {$logoUrl}");

            $response = Http::timeout(30)->get($logoUrl);

            if (!$response->successful()) {
                $this->error("Failed to download logo for '{$league->name}': HTTP {$response->status()}");
                return;
            }

            $contentType = $response->header('Content-Type');
            if (!$this->isValidImageType($contentType)) {
                $this->warn("Invalid image type for '{$league->name}': {$contentType}");
                return;
            }

            $filename = "{$league->id}.png";

            $publicPath = public_path('images/leagues');
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            $filePath = $publicPath . '/' . $filename;
            file_put_contents($filePath, $response->body());

            $league->update(['logo' => $filename]);

            $this->info("✓ Saved: {$filename}");
            $this->info("✓ Updated database logo field: {$filename}");

        } catch (\Exception $e) {
            $this->error("Error downloading logo for '{$league->name}': " . $e->getMessage());
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

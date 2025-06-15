<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DownloadPlayerPhotos extends Command
{
    protected $signature = 'players:download-images {--batch-size=1000 : Number of photos to process per batch}';

    protected $description = 'Download player photos from API to public/images/players directory';

    public function handle()
    {
        $batchSize = (int) $this->option('batch-size');

        $this->info("Starting player photo download process...");
        $this->info("Batch size: {$batchSize}");

        $players = Player::whereNull('photo')->get();

        if ($players->isEmpty()) {
            $this->warn('No players with provider_id found in database.');
            return 0;
        }

        $this->info("Found {$players->count()} players to process.");

        $batches = $players->chunk($batchSize);
        $totalBatches = $batches->count();
        $currentBatch = 1;

        foreach ($batches as $batch) {
            $this->info("Processing batch {$currentBatch}/{$totalBatches}...");

            foreach ($batch as $player) {
                $this->downloadPhoto($player);
            }

            if ($currentBatch < $totalBatches) {
                $this->info("Waiting 5 seconds before next batch...");
                sleep(5);
            }

            $currentBatch++;
        }

        $this->info('Player photo download process completed!');
        return 0;
    }

    private function downloadPhoto(Player $player)
    {
        try {
            if (empty($player->provider_id)) {
                $this->warn("Player '{$player->name}' has empty provider_id, skipping...");
                return;
            }

            $photoUrl = "https://media.api-sports.io/football/players/{$player->provider_id}.png";
            $this->line("Downloading photo for: {$player->name}");
            $this->line("URL: {$photoUrl}");

            $response = Http::timeout(30)->get($photoUrl);

            if (!$response->successful()) {
                $this->error("Failed to download photo for '{$player->name}': HTTP {$response->status()}");
                return;
            }

            $contentType = $response->header('Content-Type');
            if (!$this->isValidImageType($contentType)) {
                $this->warn("Invalid image type for '{$player->name}': {$contentType}");
                return;
            }

            $filename = "{$player->id}.png";

            $publicPath = public_path('images/players');
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            $filePath = $publicPath . '/' . $filename;
            file_put_contents($filePath, $response->body());

            $player->update(['photo' => $filename]);

            $this->info("✓ Saved: {$filename}");
            $this->info("✓ Updated database photo field: {$filename}");

        } catch (\Exception $e) {
            $this->error("Error downloading photo for '{$player->name}': " . $e->getMessage());
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

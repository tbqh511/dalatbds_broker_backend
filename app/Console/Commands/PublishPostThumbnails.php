<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NewsPostmeta;
use Illuminate\Support\Facades\File;

class PublishPostThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish-thumbs {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy stored post thumbnails from storage/app/public to public/assets/images/posts so they are directly accessible';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dry = $this->option('dry-run');
        $metas = NewsPostmeta::where('meta_key', '_thumbnail')->get();
        $this->info('Found ' . $metas->count() . ' thumbnail meta entries.');

        $publicDir = public_path('assets/images/posts');
        if (!File::isDirectory($publicDir)) {
            if ($dry) {
                $this->info("[dry-run] Would create directory: $publicDir");
            } else {
                File::makeDirectory($publicDir, 0755, true);
                $this->info("Created directory: $publicDir");
            }
        }

        $copied = 0;
        foreach ($metas as $meta) {
            $path = $meta->meta_value;
            if (empty($path)) continue;
            $filename = basename($path);
            $source = storage_path('app/public/' . $path);
            $dest = $publicDir . '/' . $filename;
            if (File::exists($dest)) {
                continue;
            }
            if (!File::exists($source)) {
                $this->warn("Source missing: $source");
                continue;
            }
            if ($dry) {
                $this->info("[dry-run] Would copy $source -> $dest");
            } else {
                File::copy($source, $dest);
                $copied++;
            }
        }

        $this->info('Done. Copied: ' . $copied);
        return 0;
    }
}

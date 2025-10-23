<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;

class BackfillPropertyTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backfill-property-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
        Property::query()->chunkById(500, function ($chunk) {
        foreach ($chunk as $p) {
            $p->title_sr        = $p->title_sr        ?: $p->title;
            $p->title_ru        = $p->title_ru        ?: $p->title;
            $p->description_sr  = $p->description_sr  ?: $p->description;
            $p->description_ru  = $p->description_ru  ?: $p->description;
            $p->saveQuietly();
        }
    });

    $this->info('Backfill complete.');
}
}

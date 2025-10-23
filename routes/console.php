<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Models\Property;
use App\Services\Translator;


Artisan::command('properties:translate {--fields=title,description} {--limit=200} {--offset=0} {--dry-run}', function (Translator $tx) {
    $fields = array_filter(array_map('trim', explode(',', (string)$this->option('fields') ?: 'title,description')));
    $limit  = (int) $this->option('limit');
    $offset = (int) $this->option('offset');
    $dry    = (bool) $this->option('dry-run');

    $rows = Property::query()->orderBy('id')->skip($offset)->take($limit)->get();
    $bar  = $this->output->createProgressBar($rows->count());
    $bar->start();

    foreach ($rows as $p) {
        $tBag = (array) ($p->title_i18n ?? []);
        $dBag = (array) ($p->description_i18n ?? []);

        $titleEn = trim((string) ($tBag['en'] ?? $p->title ?? ''));
        $descEn  = trim((string) ($dBag['en'] ?? $p->description ?? ''));

        $changed = false;

        // TITLE
        if (in_array('title', $fields, true) && $titleEn !== '') {
            $sr = (string) ($tBag['sr'] ?? '');
            $ru = (string) ($tBag['ru'] ?? '');
            $needsSr = ($sr === '' || mb_strtolower($sr) === mb_strtolower($titleEn));
            $needsRu = ($ru === '' || mb_strtolower($ru) === mb_strtolower($titleEn));

            if ($needsSr || $needsRu) {
                $tr = $tx->translate($titleEn);
                if ($needsSr && !empty($tr['sr'])) { $tBag['sr'] = $tr['sr']; $changed = true; }
                if ($needsRu && !empty($tr['ru'])) { $tBag['ru'] = $tr['ru']; $changed = true; }
            }
        }

        // DESCRIPTION
        if (in_array('description', $fields, true) && $descEn !== '') {
            $sr = (string) ($dBag['sr'] ?? '');
            $ru = (string) ($dBag['ru'] ?? '');
            $needsSr = ($sr === '' || mb_strtolower($sr) === mb_strtolower($descEn));
            $needsRu = ($ru === '' || mb_strtolower($ru) === mb_strtolower($descEn));

            if ($needsSr || $needsRu) {
                $tr = $tx->translate($descEn);
                if ($needsSr && !empty($tr['sr'])) { $dBag['sr'] = $tr['sr']; $changed = true; }
                if ($needsRu && !empty($tr['ru'])) { $dBag['ru'] = $tr['ru']; $changed = true; }
            }
        }

        if ($changed && !$dry) {
            $p->title_i18n       = $tBag;
            $p->description_i18n = $dBag;
            $p->save();
            usleep(700000); // 0.5s – manje udara u rate limit
        }

        $bar->advance();
    }

    $bar->finish(); $this->newLine();
    $this->info('Done.');
})->describe('Translate EN → SR/RU for title/description where missing or equal to EN.');



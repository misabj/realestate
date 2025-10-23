<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // MySQL specifično — moramo ručno napisati SQL sa AFTER
        DB::statement("
            ALTER TABLE `properties`
              MODIFY COLUMN `title`          VARCHAR(255) NOT NULL AFTER `category_id`,
              MODIFY COLUMN `title_sr`       VARCHAR(255) NULL     AFTER `title`,
              MODIFY COLUMN `title_ru`       VARCHAR(255) NULL     AFTER `title_sr`,
              MODIFY COLUMN `description`    TEXT         NULL     AFTER `lng`,
              MODIFY COLUMN `description_sr` TEXT         NULL     AFTER `description`,
              MODIFY COLUMN `description_ru` TEXT         NULL     AFTER `description_sr`
        ");
    }

    public function down(): void
    {
        // (Opcionalno) vrati ih na prethodne pozicije – prilagodi po želji.
    }
};
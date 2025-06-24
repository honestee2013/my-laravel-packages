<?php

namespace App\Modules\User\Database\Migrations;


use App\Modules\Core\Database\Migrations\BaseStatusCategoryMigration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends BaseStatusCategoryMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_status_categories', function (Blueprint $table) {
            $this->defineStatusCategoryColumns($table);
            // Add any user-specific category columns here if needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_status_categories');
    }
};
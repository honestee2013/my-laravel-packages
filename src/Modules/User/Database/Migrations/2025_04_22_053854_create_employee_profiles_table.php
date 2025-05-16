<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete();
			$table->string('employee_id')->nullable();
			$table->string('department')->nullable();
			$table->string('designation')->nullable();
			$table->date('joining_date')->nullable();
            $table->date('termination_date')->nullable();
			$table->foreignId('reporting_manager_id')->nullable()->constrained('users', 'id')->setNullOnDelete();
			$table->foreignId('job_title_id')->nullable()->constrained('job_titles', 'id')->setNullOnDelete();
			$table->string('employment_type')->nullable();
			$table->string('work_location')->nullable();
			
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_profiles');
    }
};

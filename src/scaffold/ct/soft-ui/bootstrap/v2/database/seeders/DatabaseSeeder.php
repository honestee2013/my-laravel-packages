<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    /*public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class
        ]);
    }*/


    public function run()
    {
        $modulePath = app_path('Modules');
        foreach (scandir($modulePath) as $module) {
            $seederPath = $modulePath . '/' . $module . '/Database/Seeders';
            if (is_dir($seederPath)) {
                foreach (glob($seederPath . '/*.php') as $file) {
                    $class = "\\App\\Modules\\$module\\Database\\Seeders\\" . basename($file, '.php');
                    if (class_exists($class)) {
                        $this->call($class);
                    }
                }
            }
        }
    }
    



}

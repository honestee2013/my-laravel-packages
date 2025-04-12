<?php

namespace QuickerFaster\CodeGen\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use QuickerFaster\CodeGen\Commands\MakeCrudCommand;
use QuickerFaster\CodeGen\Http\Livewire\CrudComponent; // Example

class QuickerFasterCodeGenServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
        // Merge configuration
        /*$this->mergeConfigFrom(__DIR__.'/../config/code-gen.php', 'code-gen');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                //MakeCrudCommand::class, // Example command
            ]);
        }*/

        // Register Livewire component (example - adjust namespace and class name if needed)
        //Livewire::component('crud-component', CrudComponent::class);
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /*
        
        // Example: Loading routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Example: Loading migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publishing common configuration
        $this->publishes([
            __DIR__.'/../config/code-gen.php' => config_path('code-gen.php'),
        ], 'code-gen-config');

        // Publishing views for Livewire CRUD with Creative Tim Bootstrap theme
        $this->publishes([
            __DIR__.'/../resources/views/livewire-crud-ct-bootstrap' => resource_path('views/vendor/code-gen/livewire-crud-ct-bootstrap'),
        ], 'code-gen-livewire-crud-ct-bootstrap-views');

        // Publishing assets for Livewire CRUD with Creative Tim Bootstrap theme
        $this->publishes([
            __DIR__.'/../public/livewire-crud-ct-bootstrap' => public_path('vendor/code-gen/livewire-crud-ct-bootstrap'),
        ], 'code-gen-livewire-crud-ct-bootstrap-assets');

        // Publishing views for Inertia CRUD with Tailwind (Example - adjust paths)
        $this->publishes([
            __DIR__.'/../resources/views/inertia-crud-tailwind' => resource_path('views/vendor/code-gen/inertia-crud-tailwind'),
        ], 'code-gen-inertia-crud-tailwind-views');

        // Publishing assets for Inertia CRUD with Tailwind (Example - adjust paths)
        $this->publishes([
            __DIR__.'/../public/inertia-crud-tailwind' => public_path('vendor/code-gen/inertia-crud-tailwind'),
        ], 'code-gen-inertia-crud-tailwind-assets');

        // Publishing app integration files (adjust paths as needed)
        $this->publishes([
            __DIR__.'/../app-integration/Models' => app_path('Models/CodeGen'),
            __DIR__.'/../app-integration/Controllers' => app_path('Http/Controllers/CodeGen'),
        ], 'code-gen-app-integration');
        
*/

        // Publishing Creative Tim Bootstrap Soft UI Scaffolding
        $this->publishes([
        __DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/app/Http/Controllers' => app_path('Http/Controllers'),
        __DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/app/Models/User.php' => app_path('Models/User.php'),

        __DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/public' => public_path('/'),
        //__DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/public/assets' => public_path('assets'),
        //__DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/public/css' => public_path('css'),
        //__DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/public/js' => public_path('js'),


        __DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/database/migrations' => database_path('migrations'),
        __DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/database/seeders' => database_path('seeders'),


        __DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/resources' => resource_path('/'),
        //__DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/resources/views/layouts' => resource_path('views/layouts'),
        //__DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/resources/views/session' => resource_path('views/session'),
        //__DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/resources/views/components' => resource_path('views/components'),


        //__DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/resources/views/dashboard.blade.php' => resource_path('views/dashboard.blade.php'),
        //__DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/resources/views/welcome.blade.php' => resource_path('views/welcome.blade.php'),
        //__DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/resources/views/profile.blade.php' => resource_path('views/profile.blade.php'),
      
        __DIR__.'/../scaffold/ct/soft-ui/bootstrap/v2/routes/web.php' => base_path('routes/quicker-faster/code-gen/web.php'), // Be cautious with this!
        
        ], 'code-gen-ct-soft-ui-bootstrap-v2');


    }
}



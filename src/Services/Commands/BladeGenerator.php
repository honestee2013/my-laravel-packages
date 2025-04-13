<?php

namespace App\Modules\Core\Services\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\File;

class BladeGenerator extends Command
{



    public function generateBladeFile($module, $modelName, $modelData, $command)
    {
        //  (path setup)
        $viewPath = app_path("Modules/{$module}/Resources/views/" . strtolower(Str::kebab($modelName)) . '.blade.php'); // Modular path

        if (!File::exists(dirname($viewPath))) {
            File::makeDirectory(dirname($viewPath), 0755, true);
        }


        if (isset($modelData['tab'])) { // It has tabs

            $modelData['tab'] = $this->initialiseTabParameters($modelData['tab'], $modelName);
            $this->generateTabBarLinks($module, $modelName, $modelData, $command); // Generate tab bar links

                $tab = $modelData['tab'] ?? null;
                if (!$tab)
                    return;

                $viewDir = app_path("Modules/{$module}/Resources/views/");
                $viewPath = $viewDir . $tab['view'] . '.blade.php';

                if (!File::exists(dirname($viewPath))) {
                    File::makeDirectory(dirname($viewPath), 0755, true);
                }
                $stub = $this->getBladeStub($module, $modelName, $modelData, $tab['id']); // Pass tab name
                File::put($viewPath, $stub);
                $command->info("Blade view created: {$viewPath}");

        } else { // No tabs - standard single view
            $viewPath = app_path("Modules/{$module}/Resources/views/" . Str::plural(strtolower(Str::kebab($modelName))) . '.blade.php');
            if (!File::exists(dirname($viewPath))) {
                File::makeDirectory(dirname($viewPath), 0755, true);
            }
            $stub = $this->getBladeStub($module, $modelName, $modelData);
            File::put($viewPath, $stub);
            $command->info("Blade view created: {$viewPath}");
        }

    }


    protected function getBladeStub($module, $modelName, $modelData, $activeTab = null) // Add $activeTab
    {

        $includeHeader = $modelData['includeHeader'] ?? false;
        $includeFooter = $modelData['includeFooter'] ?? false;
        $includeSidebar = $modelData['includeSidebar'] ?? true; // Get sidebar flag


        $stub = File::get(app_path('Modules/Core/Stubs/view.blade.stub'));
        $pageTitle = Str::plural(Str::title(str_replace('_', ' ', Str::snake($modelName)))). " Management";
        $parentPageTitle = $pageTitle;

        $hiddenFields = var_export($modelData['hiddenFields'] ?? [], true);
        $hiddenFields = str_replace("array (", "[", str_replace(")", "]", $hiddenFields));

        $queryFilters = var_export($modelData['queryFilters'] ?? [], true);
        $queryFilters = str_replace("array (", "[", str_replace(")", "]", $queryFilters));

        $tabBarLinks = "";
        if (isset($modelData['tab'])) {
            $modelData['tab'] = $this->initialiseTabParameters($modelData['tab'], $modelName);
            $parentPageTitle = $modelData['tab']['parentPageTitle'];
            $pageTitle = $modelData['tab']['pageTitle'];
            $includeHeader = $includeFooter = true;

            $tabBarLinks = "<x-core.views::tab-bar>\n";
            $tabBarLinks .= "    <x-{$module}.views::layouts.navbars.auth.{$modelData['tab']['group']}-tab-bar-links active='{$modelData['tab']['id']}' />\n"; // Include the generated component
            $tabBarLinks .= "</x-core.views::tab-bar>\n";
        }

        $livewireComponent = "<livewire:core.data-tables.data-table-manager model=\"App\\Modules\\{$module}\\Models\\{$modelName}\"\n";
        $livewireComponent .= "    pageTitle=\"{$pageTitle}\"\n";
        $livewireComponent .= "    queryFilters=[]\n";
        $livewireComponent .= "    :hiddenFields=\"{$hiddenFields}\"\n";
        $livewireComponent .= "    :queryFilters=\"{$queryFilters}\"\n";
        $livewireComponent .= "/>\n";


        $header = "";
        if ($includeHeader) {
            $header = "<x-slot name=\"pageHeader\">\n";
            $header .= "    @include('core.views::components.layouts.navbars.auth.content-header', [ \"pageTitile\" => \"$parentPageTitle\"])\n";
            $header .= "</x-slot>\n\n";
        }

        $footer = "";
        if ($includeFooter) {
            $footer = "<x-slot name=\"pageFooter\">\n";
            $footer .= "    @include('core.views::components.layouts.navbars.auth.content-footer', [ ])\n";
            $footer .= "</x-slot>\n";
        }


        $sidebar = "";
        if ($includeSidebar) { // Conditionally include sidebar
            $sidebar = "<x-slot name=\"sidebar\">\n";
            $sidebar .= "    <x-core.views::layouts.navbars.auth.sidebar moduleName=\"{{module}}\">\n";
            $sidebar .= "        <x-{{module}}.views::layouts.navbars.auth.sidebar-links />\n";
            $sidebar .= "    </x-core.views::layouts.navbars.auth.sidebar>\n";
            $sidebar .= "</x-slot>\n\n";
        }


        $stub = str_replace('{{pageTitle}}', $pageTitle, $stub);
        $stub = str_replace('{{hiddenFields}}', $hiddenFields, $stub);
        $stub = str_replace('{{queryFilters}}', $queryFilters, $stub);
        $stub = str_replace('{{tabBarLinks}}', $tabBarLinks, $stub);
        $stub = str_replace('{{livewireComponent}}', $livewireComponent, $stub);



        $stub = str_replace('{{header}}', $header, $stub);
        $stub = str_replace('{{sidebar}}', $sidebar, $stub); // Replace sidebar placeholder
        $stub = str_replace('{{footer}}', $footer, $stub);

        $stub = str_replace('{{module}}', strtolower($module), $stub);
        $stub = str_replace('{{modelName}}', $modelName, $stub);


        return $stub;

    }





    private function initialiseTabParameters($tab, $modelName) {
        $pageTitle = Str::plural(Str::title(str_replace('_', ' ', $modelName))). " Management";

        $tab['parentPageTitle'] ??= $pageTitle;
        $tab['pageTitle'] ??=  $tab['parentPageTitle'];
        $tab['group'] ??= strtolower(Str::plural(Str::kebab($modelName)));
        $tab['view'] ??=  $tab['group'];
        $tab['url'] ??= $tab['view'];
        $tab['id'] ??= $tab['url'];
        $tab['label'] ??= $modelName;

        return $tab;
    }



    protected function generateTabBarLinks($module, $modelName, $modelData, $command)
    {

        $tabGroup = strtolower(Str::kebab($modelData['tab']['group'] ?? $modelName));
        $tabBarLinksPath = app_path("Modules/{$module}/Resources/views/components/layouts/navbars/auth/" . $tabGroup . "-tab-bar-links.blade.php"); // Path based on model name or a provided name

        $existingContent = "";

        if (!File::exists(dirname($tabBarLinksPath))) {
            File::makeDirectory(dirname($tabBarLinksPath), 0755, true);
        } else if (File::exists($tabBarLinksPath)) {
            $existingContent = File::get($tabBarLinksPath);
        }

        $stub = $this->getTabBarLinksStub($modelData);

        // Check if the link already exists (avoid duplicates):
        if (!str_contains($existingContent, $stub)) { // Check for near-duplicates
            File::append($tabBarLinksPath, "\n" . $stub);
            $command->info("Tabbar link already exists in: {$tabBarLinksPath}. Skipping.");
        } else {
            File::put($tabBarLinksPath, $stub);
            $command->info("Tab bar links file created: {$tabBarLinksPath}");
        }

    }



    protected function getTabBarLinksStub($modelData)
    {

        $tab = $modelData['tab']?? null;
        if (!isset($tab))
            return "";

        $iconClasses = $modelData['tab']['iconClasses']?? $modelData["iconClasses"]?? "fa-user";


        $links = "";
        //foreach ($modelData['tabs'] as $tab) {
            $links .= "<x-core.views::layouts.navbars.sidebar-link-item\n";
            $links .= "    iconClasses=\"$iconClasses\"\n"; // Or dynamic icon class
            $links .= "    url=\"{$tab['url']}\"\n";
            $links .= "    title=\"{$tab['label']}\"\n";
            $links .= "    anchorClasses=\"{{ (\$active == '{$tab['id']}')? 'active': ''}}\"\n";
            $links .= "/>\n\n";
        //}

        return $links;
    }








}

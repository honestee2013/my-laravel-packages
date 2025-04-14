<?php

namespace QuickerFaster\CodeGen\Services\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\File;

class SidebarLinksGenerator extends Command
{


    public function generateSidebarLinks($module, $modelName, $modelData, $command)
    {

        $sidebar = $modelData['sidebar']?? [];
        if(isset($sidebar, $sidebar['add']) && !$sidebar['add']) // Add sidebar Set to false
            return;

        $sidebarLinksPath = app_path("Modules/{$module}/Resources/views/components/layouts/navbars/auth/sidebar-links.blade.php");


        $newLink = $this->getSidebarLinksStub($module, $modelName, $modelData);

        if (File::exists($sidebarLinksPath)) {
            // Append the new link if it doesn't already exist.
            $existingContent = File::get($sidebarLinksPath);

            // Check if the link already exists (avoid duplicates):
            if (!str_contains($existingContent, $newLink)) { // Check for near-duplicates
                File::append($sidebarLinksPath, "\n" . $newLink);
                $command->info("New sidebar link appended to: {$sidebarLinksPath}");
            } else {
                $command->info("Sidebar link already exists in: {$sidebarLinksPath}. Skipping.");
            }

        } else {
            // Create the file with the new link if it doesn't exist.
            if (!File::exists(dirname($sidebarLinksPath))) {
                File::makeDirectory(dirname($sidebarLinksPath), 0755, true);
            }
            File::put($sidebarLinksPath, "<hr class = 'horizontal dark' /> \n\n$newLink");
            $command->info("Sidebar links file created: {$sidebarLinksPath}");
        }
    }


    protected function getSidebarLinksStub($module, $modelName, $modelData)
    {
        $sidebar = $modelData['sidebar']?? [];
        $iconClasses = $sidebar["iconClasses"]?? $modelData['iconClasses']?? "fas fa-user";

        $url = Str::plural(str_replace("_", "-", Str::snake($modelName)));
        $title = Str::plural(str_replace("_", " ", Str::snake($modelName)));
        $title = $sidebar['title']?? "Manage ".$title;
        $url = $sidebar['url']?? $url;
        //$iconClasses = $sidebar['iconClasses']?? "fas fa-cubes sidebar-icon";

        return "<x-core.views::layouts.navbars.sidebar-link-item\n" .
            "    iconClasses=\"$iconClasses sidebar-icon\"\n" .
            "    url=\"{$module}/" . strtolower($url) . "\"\n" . // Default URL: /{module}/{pluralized_module_name}
            "    title=\"" . Str::title(Str::plural($title)) . "\"\n" . // Default title
            "/>\n";
    }





}

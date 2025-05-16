<?php

namespace QuickerFaster\CodeGen\Http\Livewire\Menus;


use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use App\Modules\Access\Http\Livewire\AccessControls\AccessControlManager;
use Illuminate\Support\Facades\Auth;


class MenuInitializer extends Component
{

    public $moduleName;

    protected $listeners = [
        "initializeMenuEvent" => "initializeMenu"
    ];





    public function initializeMenu() {
        $user = Auth::user();
        $sidebarLinks = $this->getSidebarLinks($user);
        $this->dispatch('sidebar-data-loaded', $sidebarLinks);
    }





    private function getSidebarLinks($user)
    {
        $links = $this->loadSidebarLinks($this->moduleName);

        return collect($links)
            ->filter(fn ($link) => !isset($link['permission']) || $user->can($link['permission']))
            ->map(function ($link) {
                // If no URL is provided for a top-level item with a submenu,
                // we don't want to create a clickable link for it.
                if (isset($link['submenu']) && !isset($link['url'])) {
                    return $link;
                }
                // Ensure there's a default '#' if no URL is provided for non-submenu items
                $link['url'] = $link['url'] ?? '#';
                return $link;
            })
            ->values()
            ->toArray();
    }



    function loadSidebarLinks(string $moduleName): array {
        $path = base_path("app/Modules/" . ucfirst($moduleName) . "/Config/sidebar_menu.php");
        return file_exists($path) ? include $path : [];
    }


    public function render()
    {
        return view('core.views::menus.menu-initializer');
    }
    



}
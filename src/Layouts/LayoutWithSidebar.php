<?php
namespace QuickerFaster\CodeGen\Http\Livewire\Layouts;


use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LayoutWithSidebar extends Component
{
    // ... other properties and methods

    public function mount()
    {
        $user = Auth::user();
        $sidebarLinks = $this->getSidebarLinks($user);

        $this->dispatch('sidebar-data-loaded', $sidebarLinks);
    }

    private function getSidebarLinks($user)
    {
        $links = [
            ['icon' => 'bi-house', 'text' => 'Home', 'url' => '/dashboard', 'permission' => 'view-dashboard'],
            ['icon' => 'bi-person', 'text' => 'Profile', 'submenu' => [
                ['text' => 'View Profile', 'link' => '#'],
                ['text' => 'Edit Profile', 'link' => '#']
            ], 'permission' => 'view-profile'],
            ['icon' => 'bi-gear', 'text' => 'Settings', 'url' => '/settings', 'permission' => 'manage-settings'],
            // ... more links based on permissions
        ];

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

    public function render()
    {
        return view('livewire.layout-with-sidebar');
    }
}
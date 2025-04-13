<?php

namespace App\Modules\Core\Formatters;

use App\Modules\Core\Contracts\DataTable\CellFormatterInterface;

class StatusFormatter implements CellFormatterInterface
{
    // Define the mapping of status names to Bootstrap classes
    private static $statusClasses = [
        'pending' => 'bg-gradient-warning',
        'approved' => 'bg-gradient-success',
        'rejected' => 'bg-gradient-danger',
        'completed' => 'bg-gradient-success',
        'canceled' => 'bg-gradient-secondary',
        'returned' => 'bg-gradient-dark',
        'on_hold' => 'bg-gradient-info',
        'in_progress' => 'bg-gradient-info',
        'under_review' => 'bg-gradient-info',
        'scheduled' => 'bg-gradient-success',
        'paused' => 'bg-gradient-warning',
        'error' => 'bg-gradient-danger',
    ];

    public static function format($value, $row)
    {
        // Get the appropriate class for the status or a default class
        $class = self::$statusClasses[strtolower($value)] ?? 'bg-gradient-secondary';

        return sprintf(
            '<span class="ms-3 text-xxs badge %s rounded-pill pt-1" style="height: 1.8em;">%s</span>',
            $class,
            htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
        );
    }
}


<?php

namespace QuickerFaster\CodeGen\Services\Formatters;

use App\Modules\Core\Contracts\DataTable\CellFormatterInterface;

class ProgressFormatter implements CellFormatterInterface
{
    // Define progress colors for different thresholds
    public static $progressColors = [
        25 => 'bg-gradient-danger',
        50 => 'bg-gradient-warning',
        75 => 'bg-gradient-info',
        100 => 'bg-gradient-success',
    ];

    public static function format($value, $row)
    {
        // Ensure the value is a valid percentage (0 to 100)
        $percentage = is_numeric($value) && $value >= 0 && $value <= 100 ? $value : 0;

        // Determine the appropriate color based on the percentage
        $colorClass = self::getColorClass($percentage);

        return sprintf(
            '<div class="d-flex align-items-center justify-content-center mt-3">
                <span class="me-2 text-xs font-weight-bold">%d%%</span>
                <div>
                    <div class="progress" style="width: 100px;"> <!-- Adjust width as needed -->
                        <div class="progress-bar %s" role="progressbar" aria-valuenow="%d" aria-valuemin="0" aria-valuemax="100" style="width: %d%%;"></div>
                    </div>
                </div>
            </div>',
            $percentage,
            $colorClass,
            $percentage,
            $percentage
        );
    }

    private static function getColorClass($percentage)
    {
        // Iterate through the thresholds and find the appropriate color
        foreach (self::$progressColors as $threshold => $class) {
            if ($percentage <= $threshold) {
                return $class;
            }
        }

        // Default color if no thresholds match
        return 'bg-gradient-secondary';
    }
}


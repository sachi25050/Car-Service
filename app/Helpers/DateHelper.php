<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format date to dd/mm/yyyy format
     *
     * @param mixed $date
     * @return string|null
     */
    public static function formatDate($date): ?string
    {
        if (!$date) {
            return null;
        }

        if ($date instanceof Carbon || $date instanceof \DateTime) {
            return $date->format('d/m/Y');
        }

        try {
            return Carbon::parse($date)->format('d/m/Y');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Format date with time to dd/mm/yyyy hh:mm format
     *
     * @param mixed $date
     * @return string|null
     */
    public static function formatDateTime($date): ?string
    {
        if (!$date) {
            return null;
        }

        if ($date instanceof Carbon || $date instanceof \DateTime) {
            return $date->format('d/m/Y H:i');
        }

        try {
            return Carbon::parse($date)->format('d/m/Y H:i');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Format date with time to dd/mm/yyyy hh:mm AM/PM format
     *
     * @param mixed $date
     * @return string|null
     */
    public static function formatDateTime12($date): ?string
    {
        if (!$date) {
            return null;
        }

        if ($date instanceof Carbon || $date instanceof \DateTime) {
            return $date->format('d/m/Y h:i A');
        }

        try {
            return Carbon::parse($date)->format('d/m/Y h:i A');
        } catch (\Exception $e) {
            return null;
        }
    }
}

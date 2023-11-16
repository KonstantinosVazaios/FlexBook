<?php

namespace App\Services;

use App\Models\Store;
use Carbon\Carbon;

class ReservationAvailabilityCalculator
{
    public static function getUnavailableDatesForStore(int | null $storeId) : array
    {
        if (!$storeId) return [];

        $store = Store::find($storeId);

        if (!$store) return [];

        $unavailableDates = [];

        $storeHours = $store->storeHours;
        $storeHolidays = $store->storeHolidays;
        $combinedDates = $storeHours->concat($storeHolidays);

        foreach ($combinedDates as $dateRecord) {
            $date = $dateRecord->date ?? Carbon::now()->startOfWeek()->addDays($dateRecord->day - 1);
            $date = Carbon::parse($date);

            if (!$dateRecord->is_open) {
                $unavailableDates[] = $date->format('Y-m-d');
            }
        }

        return $unavailableDates;
    }

}

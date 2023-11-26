<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Store;
use Carbon\Carbon;

class ReservationAvailabilityCalculator
{
    public static function isStoreAvailableOnDate(int $storeId, string $date)
    {
        $store = Store::find($storeId);

        if (!$store) return false;

        $storeHours = $store->storeHours;
        $storeHolidays = $store->storeHolidays;

        $numericDayOfWeek = Carbon::createFromFormat('Y-m-d', $date)->format('N');

        $closedForHoliday = $storeHolidays->where('date', $date)->where('is_open', false)->isNotEmpty();
        $closedBasedOnHours = $storeHours->where('day', $numericDayOfWeek)->where('is_open', false)->isNotEmpty();

        return !($closedForHoliday || $closedBasedOnHours);
    }

    public static function getUnavailableDatesForStore(int $storeId)
    {
        $store = Store::find($storeId);

        if (!$store) return [];

        $storeHours = $store->storeHours()->where('is_open', false)->get();
        $storeHolidays = $store->storeHolidays()->where('is_open', false)->get();

        $startDateCheck = Carbon::now();
        $endDateCheck = $startDateCheck->copy()->addDays(30);

        $unavailableDates = [];

        while ($startDateCheck->lte($endDateCheck)) {
            $currentDate = $startDateCheck->format('Y-m-d');

            $dayOfWeek = Carbon::createFromFormat('Y-m-d', $currentDate)->format('N');

            if ($storeHours->where('day', $dayOfWeek)->isNotEmpty() || $storeHolidays->where('date', $currentDate)->isNotEmpty()) {
                $unavailableDates[] = $currentDate;
            }

            $startDateCheck->addDay();
        }

        return $unavailableDates;
    }

    public static function getAvailableHoursForStore($storeId, $date)
    {
        $store = Store::find($storeId);

        if (!$store) return [];

        $numericDayOfWeek = Carbon::createFromFormat('Y-m-d', $date)->format('N');

        $storeIsOpen = self::isStoreAvailableOnDate($storeId, $date);
        if (!$storeIsOpen) {
            return [];
        }

        $storeHours = collect();

        $specialHours = $store->storeHolidays()->whereDate('date', $date)->where('is_open', true)->get();
        $commonHours = $storeHours = $store->storeHours()->where('day', $numericDayOfWeek)->where('is_open', true)->get();

        if ($specialHours->isNotEmpty()) {
            $storeHours = $specialHours;
        } else {
            $storeHours = $commonHours;
        }

        $bookingInterval = Setting::where('parameter', 'BOOKING_INTERVAL')->first()?->value ?? 30;
        $availableTimeSlots = [];

        // Iterate over the store hours and add available time slots to the array
        $storeHours->map(function ($storeHour) use (&$availableTimeSlots, $bookingInterval) {
            $startTime = Carbon::parse($storeHour->open);
            $endTime = Carbon::parse($storeHour->close);

            // Create time slots with defined booking interval
            while ($startTime->lt($endTime)) {
                $availableTimeSlots[] = $startTime->format('H:i');
                $startTime->addMinutes(intval($bookingInterval));
            }
        });

        return $availableTimeSlots;
    }
}

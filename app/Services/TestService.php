<?php

// Step 1: Get the business's working days and hours
$workingHours = Business::find($businessId)->workingHours;

// Step 2: Get the selected services and their durations
$selectedServices = $request->input('selected_services');
$serviceDurations = Service::whereIn('service_id', $selectedServices)->pluck('duration', 'service_id');

// Step 3: Define the 30-day window
$startDate = Carbon::now();
$endDate = $startDate->copy()->addDays(30);

// Step 4: Initialize an array to store available time slots
$availableTimeSlots = [];

// Define the time interval (30 minutes)
$timeInterval = 30;

// Step 5: Loop through each day in the window
while ($startDate <= $endDate) {
    $dayOfWeek = $startDate->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

    // Check if the day is a working day for the business
    if ($workingHours[$dayOfWeek]) {
        $currentDate = $startDate->toDateString();
        $startTime = $startDate;
        $endTime = $startDate;

        // Initialize an array to store available 30-minute time slots
        $availableSlotsForDay = [];

        // Loop through services in the selected order
        foreach ($selectedServices as $serviceId) {
            $duration = $serviceDurations[$serviceId];

            while ($endTime <= $startDate->copy()->addDay()) {
                $endCheckTime = $endTime->copy()->addMinutes($duration);

                // Loop through staff members, giving priority to the selected staff member
                $staffMembers = $this->getStaffMembersInOrder($selectedStaffId, $currentDate, $startTime, $endCheckTime);
                if ($staffMembers) {
                    // Calculate available 30-minute slots within the current service's duration
                    while ($endTime <= $endCheckTime) {
                        $availableSlotsForDay[] = [
                            'staff_id' => $staffMembers[0], // The first staff member is available
                            'date' => $currentDate,
                            'start_time' => $endTime->toTimeString(),
                            'end_time' => $endTime->copy()->addMinutes($timeInterval)->toTimeString(),
                        ];
                        $endTime->addMinutes($timeInterval);
                    }
                    break; // Break out of the loop for this service
                } else {
                    // No available staff members for the given time slot
                    $endTime = $endCheckTime; // Move to the next potential start time
                }
            }

            $startTime = $endTime; // Update the start time for the next service
        }

        // Append the available slots for the day to the main array
        $availableTimeSlots = array_merge($availableTimeSlots, $availableSlotsForDay);
    }

    // Move to the next day
    $startDate->addDay();
}

// Step 6: Return the available time slots
return $availableTimeSlots;

// Helper function to get staff members in order
function getStaffMembersInOrder($selectedStaffId, $date, $startTime, $endTime)
{
    // Query the ReservationServices table to check for existing bookings
    $existingBookings = ReservationService::where('date', $date)
        ->where(function ($query) use ($startTime, $endTime) {
            $query->whereBetween('start_time', [$startTime, $endTime])
                ->orWhereBetween('end_time', [$startTime, $endTime]);
        })
        ->pluck('staff_id');

    // If the selected staff member is available, return them first
    if ($existingBookings->contains($selectedStaffId)) {
        return [$selectedStaffId];
    }

    // If the selected staff member is not available, look for other staff members
    $availableStaffMembers = Staff::whereNotIn('staff_id', $existingBookings)->pluck('staff_id');
    return $availableStaffMembers->toArray();
}

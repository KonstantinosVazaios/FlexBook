<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Resources\Pages\Page;

class CalendarReservations extends Page
{
    protected static string $resource = ReservationResource::class;

    protected static string $view = 'filament.resources.reservation-resource.pages.calendar-reservations';

    protected static ?string $title = 'Ραντεβού';

    protected function getHeaderWidgets(): array
    {
        return [
            ReservationResource\Widgets\ReservationsCalendarWidget::class,
        ];
    }
}

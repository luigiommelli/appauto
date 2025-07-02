<?php

namespace App\Filament\Resources\VehicleResource\Widgets;

use App\Models\Vehicle;
use Carbon\Carbon;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;

class FatturatoChartWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'Fatturato Mensile';
    protected static string $color = 'success';
    protected static ?string $icon = 'heroicon-o-banknotes';
    protected static ?string $iconColor = 'success';
    protected static ?string $iconBackgroundColor = 'success';
    protected static ?string $label = 'Andamento giornaliero';

    protected static ?string $badge = 'Mese';
    protected static ?string $badgeColor = 'success';
    protected static ?string $badgeIcon = 'heroicon-o-calendar-days';
    protected static ?string $badgeIconPosition = 'after';
    protected static ?string $badgeSize = 'xs';

    public ?string $filter = 'luglio'; // Mese di default

    protected function getFilters(): ?array
    {
        return [
            'gennaio' => 'Gennaio 2025',
            'febbraio' => 'Febbraio 2025',
            'marzo' => 'Marzo 2025',
            'aprile' => 'Aprile 2025',
            'maggio' => 'Maggio 2025',
            'giugno' => 'Giugno 2025',
            'luglio' => 'Luglio 2025',
            'agosto' => 'Agosto 2025',
            'settembre' => 'Settembre 2025',
            'ottobre' => 'Ottobre 2025',
            'novembre' => 'Novembre 2025',
            'dicembre' => 'Dicembre 2025',
        ];
    }

    protected function getData(): array
    {
        $monthMapping = [
            'gennaio' => 1, 'febbraio' => 2, 'marzo' => 3, 'aprile' => 4,
            'maggio' => 5, 'giugno' => 6, 'luglio' => 7, 'agosto' => 8,
            'settembre' => 9, 'ottobre' => 10, 'novembre' => 11, 'dicembre' => 12
        ];
        
        $selectedMonth = $monthMapping[$this->filter] ?? Carbon::now()->month;
        $year = 2025;
        
        // Numero di giorni nel mese selezionato
        $daysInMonth = Carbon::create($year, $selectedMonth, 1)->daysInMonth;
        
        $days = [];
        $data = [];
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $days[] = $day;
            
            // Fatturato del giorno per veicoli archiviati
            $fatturato = Vehicle::where('status', 'archiviato')
                ->whereDate('updated_at', Carbon::create($year, $selectedMonth, $day))
                ->sum('sale_price');
                
            $data[] = round($fatturato / 1000, 1); // Converti in migliaia
        }

        return [
            'datasets' => [
                [
                    'label' => 'Fatturato (€k)',
                    'data' => $data,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'tension' => 0.3,
                    'pointBackgroundColor' => 'rgb(34, 197, 94)',
                    'pointBorderColor' => 'rgb(34, 197, 94)',
                    'pointRadius' => 4,
                ],
            ],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

}
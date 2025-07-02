<?php

namespace App\Filament\Resources\VehicleResource\Widgets;

use App\Models\Vehicle;
use Carbon\Carbon;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;

class FatturatoAnnualeWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'Fatturato Annuale';
    protected static string $color = 'info';
    protected static ?string $icon = 'heroicon-o-chart-bar';
    protected static ?string $iconColor = 'info';
    protected static ?string $iconBackgroundColor = 'info';
    protected static ?string $label = 'Andamento per mesi';

    protected static ?string $badge = 'Anno';
    protected static ?string $badgeColor = 'info';
    protected static ?string $badgeIcon = 'heroicon-o-calendar';
    protected static ?string $badgeIconPosition = 'after';
    protected static ?string $badgeSize = 'xs';

    public ?string $filter = '2025'; // Anno di default

    protected function getFilters(): ?array
    {
        return [
            '2023' => 'Anno 2023',
            '2024' => 'Anno 2024', 
            '2025' => 'Anno 2025',
        ];
    }

    protected function getData(): array
    {
        $year = $this->filter ?? Carbon::now()->year;
        
        // Array per tutti i mesi dell'anno
        $months = [];
        $data = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::create($year, $month, 1)->format('M');
            $months[] = $monthName;
            
            // Fatturato del mese per veicoli archiviati
            $fatturato = Vehicle::where('status', 'archiviato')
                ->whereMonth('updated_at', $month)
                ->whereYear('updated_at', $year)
                ->sum('sale_price');
                
            $data[] = round($fatturato / 1000, 1); // Converti in migliaia per leggibilità
        }

        return [
            'datasets' => [
                [
                    'label' => 'Fatturato (€k)',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)', // Blu
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.3,
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => 'rgb(59, 130, 246)',
                    'pointRadius' => 4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
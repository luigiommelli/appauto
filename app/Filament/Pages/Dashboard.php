<?php

namespace App\Filament\Pages;

use App\Filament\Resources\VehicleResource\Widgets\VehicleStatsWidget;
use App\Filament\Resources\VehicleResource\Widgets\FatturatoChartWidget;
use App\Filament\Resources\VehicleResource\Widgets\FatturatoAnnualeWidget;
use App\Filament\Resources\VehicleResource\Widgets\UltimiVeicoliWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{

    protected static ?string $navigationGroup = 'Generale';
    protected static ?int $navigationSort = 1;
    
    protected function getHeaderWidgets(): array
    {
        return [
            VehicleStatsWidget::class,  // 4 stats overview in alto
        ];
    }

    public function getWidgets(): array
    {
        return [
            FatturatoChartWidget::class,    // Widget mensile (2 spazi)
            FatturatoAnnualeWidget::class,  // Widget annuale (2 spazi)
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            UltimiVeicoliWidget::class,  // Tabella ultimi veicoli (4 spazi)
        ];
    }
}
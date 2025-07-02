<?php

namespace App\Filament\Resources\AvailableVehicleResource\Pages;

use App\Filament\Resources\AvailableVehicleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use PDF; // Aggiungi questo

class ListAvailableVehicles extends ListRecords
{
    protected static string $resource = AvailableVehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportPdf')
                ->label('Scarica Listino PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $vehicles = \App\Models\Vehicle::where('status', 'disponibile')
                        ->select('brand_model', 'license_plate', 'registration_year', 'color', 'fuel_type', 'sale_price')
                        ->orderBy('brand_model')
                        ->get();
                    
                    $pdf = \PDF::loadView('pdf.listino-veicoli', compact('vehicles'))
                        ->setPaper('a4', 'portrait')
                        ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
                    
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'listino-veicoli-disponibili.pdf', [
                        'Content-Type' => 'application/pdf',
                    ]);
                }),
        ];
    }
}
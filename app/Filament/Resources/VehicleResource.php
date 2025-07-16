<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    
    protected static ?string $navigationLabel = 'Veicoli';
    
    protected static ?string $modelLabel = 'Veicolo';
    
    protected static ?string $pluralModelLabel = 'Veicoli';

    protected static ?string $navigationGroup = 'Gestione Veicoli';
    protected static ?int $navigationSort = 1;

   public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dati Principali')
                    ->schema([
                        Forms\Components\TextInput::make('brand_model')
                            ->label('Marca / Modello')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('license_plate')
                            ->label('Targa')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('chassis')
                            ->label('Telaio')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('registration_year')
                            ->label('Anno Immatricolazione')
                            ->required()
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y')),
                        Forms\Components\TextInput::make('color')
                            ->label('Colore')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('fuel_type')
                            ->label('Alimentazione')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('second_key')
                            ->label('Seconda Chiave'),
                        Forms\Components\TextInput::make('origin')
                            ->label('Provenienza')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('vat_exposed')
                            ->label('IVA Esposta'),
                        Forms\Components\DatePicker::make('purchase_date')
                            ->label('Data Acquisto')
                            ->required(),
                        Forms\Components\TextInput::make('registry_number')
                            ->label('N° Registro')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('archive_number')
                            ->label('N° Archiviazione')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Costi')
                    ->schema([
                        Forms\Components\TextInput::make('purchase_price')
                            ->label('Prezzo di Acquisto')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)
                             ->lazy()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('broker')
                            ->label('Mediatore')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)    
                             ->lazy()                      
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('transport')
                            ->label('Trasporto')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)   
                             ->lazy()                       
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('mechatronics')
                            ->label('Meccatronica')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)
                             ->lazy()      
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('bodywork')
                            ->label('Carrozzeria')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)
                             ->lazy()                           
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('tire_shop')
                            ->label('Gommista')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0) 
                             ->lazy()                          
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('upholstery')
                            ->label('Tappezzeria')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)      
                             ->lazy()                
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('travel')
                            ->label('Viaggio')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)    
                             ->lazy()         
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('inspection')
                            ->label('Revisione')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)     
                             ->lazy()      
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('miscellaneous')
                            ->label('Varie')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)   
                             ->lazy()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('spare_parts')
                            ->label('Ricambi')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)
                             ->lazy()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('washing')
                            ->label('Lavaggio')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)  
                             ->lazy()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('total_cost')
                            ->label('Totale Costo')
                            ->numeric()
                            ->prefix('€')
                            ->readOnly()
                            ->default(0),
                    ])->columns(3),
               Forms\Components\Section::make('Dati Cliente')
    ->schema([
        Forms\Components\TextInput::make('customer_name')
            ->label('Nome Cliente')
            ->maxLength(255),
        Forms\Components\TextInput::make('customer_surname')
            ->label('Cognome Cliente')
            ->maxLength(255),
        Forms\Components\TextInput::make('phone_number')
            ->label('Numero di Telefono')
            ->tel()
            ->maxLength(20)
            ->placeholder('+39 123 456 7890')
            ->rule('regex:/^[\+]?[0-9\s\-\(\)]+$/')
            ->helperText('Inserisci il numero di telefono del cliente'),
        Forms\Components\Select::make('payment_method')
            ->label('Metodo di Pagamento')
            ->options([
                'contanti' => 'Contanti',
                'bonifico' => 'Bonifico',
                'permuta' => 'Permuta',
                'finanziamento' => 'Finanziamento',
                'misto' => 'Misto',
            ]),
    ])->columns(2),
                    Forms\Components\Section::make('Documenti')
    ->collapsible()
    ->icon('heroicon-o-document-text')
    ->description('Documenti del veicolo')
    ->schema([
        Forms\Components\FileUpload::make('libretto_files')
            ->label('Libretto di Circolazione (Max 4)')
            ->multiple()
            ->maxFiles(4)
            ->acceptedFileTypes(['application/pdf', 'image/*'])
            ->directory('vehicles/libretto')
            ->previewable(true) // 🖼️ ABILITA PREVIEW IMMAGINI
            ->imagePreviewHeight('150') // 🖼️ Altezza preview
            ->enableOpen()
            ->enableDownload()
            ->loadingIndicatorPosition('center'),
                         
        Forms\Components\FileUpload::make('riparazione_files')
            ->label('Documenti di Riparazione (Max 10)')
            ->multiple()
            ->maxFiles(10)
            ->acceptedFileTypes(['application/pdf', 'image/*'])
            ->directory('vehicles/riparazione')
            ->previewable(true) // 🖼️ ABILITA PREVIEW IMMAGINI
            ->imagePreviewHeight('150') // 🖼️ Altezza preview
            ->enableOpen()
            ->enableDownload()
            ->loadingIndicatorPosition('center'),
                             
        Forms\Components\FileUpload::make('atto_vendita_files')
            ->label('Atto di Vendita (Max 3)')
            ->multiple()
            ->maxFiles(3)
            ->acceptedFileTypes(['application/pdf', 'image/*'])
            ->directory('vehicles/atto_vendita')
            ->reorderable()
            ->previewable(true) // 🖼️ ABILITA PREVIEW IMMAGINI
            ->imagePreviewHeight('150') // 🖼️ Altezza preview
            ->enableOpen()
            ->enableDownload()
            ->loadingIndicatorPosition('center'),
    ])->columns(1),

                Forms\Components\Section::make('Stato del Veicolo')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Stato')
                            ->options([
                                'disponibile' => 'Disponibile',
                                'venduto' => 'Venduto',
                                'archiviato' => 'Archiviato',
                            ])
                            ->default('disponibile')
                            ->required(),
                        Forms\Components\TextInput::make('sale_price')
                            ->label('Prezzo di Vendita')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01),
                    ])->columns(2),
            ]);
    }

  public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand_model')
                    ->label('Marca / Modello')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Targa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_year')
                    ->label('Anno')
                    ->sortable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('Colore')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('fuel_type')
                    ->label('Alimentazione')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_cost')
                    ->label('Totale Costo')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sale_price')
                    ->label('Prezzo Vendita')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Stato')
                    ->colors([
                        'success' => 'disponibile',
                        'info' => 'in_arrivo',
                        'warning' => 'venduto', 
                        'danger' => 'archiviato',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'disponibile' => 'Disponibile',
                        'in_arrivo' => 'In Arrivo',
                        'venduto' => 'Venduto',
                        'archiviato' => 'Archiviato',
                    }),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creato il')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(function ($record) {
                return match($record->status) {
                    'archiviato' => '/admin/archived-vehicles/' . $record->id,
                    'venduto' => '/admin/sold-vehicles/' . $record->id . '/edit', 
                    'disponibile' => '/admin/available-vehicles/' . $record->id . '/edit',
                     'in_arrivo' => '/admin/in-arrivo-vehicles/' . $record->id . '/edit',
                    default => '/admin/vehicles/' . $record->id . '/edit'
                };
            })
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Stato')
                    ->options([
                        'disponibile' => 'Disponibile',
                        'in_arrivo' => 'In Arrivo',
                        'venduto' => 'Venduto',
                        'archiviato' => 'Archiviato',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('manage')
                    ->label('Gestisci')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(function ($record) {
                        return match($record->status) {
                            'archiviato' => '/admin/archived-vehicles/' . $record->id,
                            'venduto' => '/admin/sold-vehicles/' . $record->id . '/edit', 
                            'disponibile' => '/admin/available-vehicles/' . $record->id . '/edit',
                              'in_arrivo' => '/admin/in-arrivo-vehicles/' . $record->id . '/edit',
                            default => '/admin/vehicles/' . $record->id . '/edit'
                        };
                    })
                    ->openUrlInNewTab(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
    
    
    private static function calculateTotalCost(Forms\Set $set, Forms\Get $get): void
    {
        $purchasePrice = (float) ($get('purchase_price') ?? 0);
        $broker = (float) ($get('broker') ?? 0);
        $transport = (float) ($get('transport') ?? 0);
        $mechatronics = (float) ($get('mechatronics') ?? 0);
        $bodywork = (float) ($get('bodywork') ?? 0);
        $tireShop = (float) ($get('tire_shop') ?? 0);
        $upholstery = (float) ($get('upholstery') ?? 0);
        $travel = (float) ($get('travel') ?? 0);
        $inspection = (float) ($get('inspection') ?? 0);
        $miscellaneous = (float) ($get('miscellaneous') ?? 0);
        $spareParts = (float) ($get('spare_parts') ?? 0);
        $washing = (float) ($get('washing') ?? 0);

        $total = $purchasePrice + $broker + $transport + $mechatronics + $bodywork + 
                $tireShop + $upholstery + $travel + $inspection + $miscellaneous + 
                $spareParts + $washing;

        $set('total_cost', number_format($total, 2, '.', ''));
    }
}
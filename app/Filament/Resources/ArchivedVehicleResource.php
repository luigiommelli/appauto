<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArchivedVehicleResource\Pages;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ArchivedVehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    
    protected static ?string $navigationLabel = 'Veicoli Archiviati';
    
    protected static ?string $modelLabel = 'Veicolo Archiviato';
    
    protected static ?string $pluralModelLabel = 'Veicoli Archiviati';

    protected static ?string $navigationGroup = 'Gestione Veicoli';
    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 'archiviato');
    }

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
                            ->maxLength(255),
                        Forms\Components\TextInput::make('registration_year')
                            ->label('Anno Immatricolazione')
                            ->required()
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y')),
                        Forms\Components\TextInput::make('km')
                            ->label('Chilometri')
                            ->numeric()
                            ->suffix(' km')
                            ->placeholder('0'),
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
                             ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('broker')
                            ->label('Mediatore')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)    
                             ->live(onBlur: true)                      
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('transport')
                            ->label('Trasporto')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)   
                             ->live(onBlur: true)                       
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('mechatronics')
                            ->label('Meccatronica')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)
                             ->live(onBlur: true)      
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('bodywork')
                            ->label('Carrozzeria')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)
                             ->live(onBlur: true)                           
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('tire_shop')
                            ->label('Gommista')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0) 
                             ->live(onBlur: true)                          
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('upholstery')
                            ->label('Tappezzeria')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)      
                             ->live(onBlur: true)                
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('travel')
                            ->label('Viaggio')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)    
                             ->live(onBlur: true)         
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('inspection')
                            ->label('Revisione')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)     
                             ->live(onBlur: true)      
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('miscellaneous')
                            ->label('Varie')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)   
                             ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('spare_parts')
                            ->label('Ricambi')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)
                             ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('washing')
                            ->label('Lavaggio')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)  
                             ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                            Forms\Components\TextInput::make('passaggio')          // AGGIUNGI QUESTO
                            ->label('Passaggio')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                self::calculateTotalCost($set, $get);
                            }),
                        Forms\Components\TextInput::make('accessori')          // AGGIUNGI QUESTO
                            ->label('Accessori')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)
                            ->live(onBlur: true)
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
            ])
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                // Reset completo di payment_details quando cambia il metodo
                $set('payment_details', []);
                
                // Reset specifico per ogni campo possibile
                $fieldsToReset = [
                    // Contanti
                    'payment_details.contanti.importo',
                    'payment_details.contanti.data',
                    'payment_details.contanti.note',
                    // Bonifico
                    'payment_details.bonifico.importo',
                    'payment_details.bonifico.da_chi',
                    'payment_details.bonifico.banca',
                    'payment_details.bonifico.data',
                    'payment_details.bonifico.note',
                    // Permuta
                    'payment_details.permuta.nome_veicolo',
                    'payment_details.permuta.targa',
                    'payment_details.permuta.anno',
                    'payment_details.permuta.colore',
                    'payment_details.permuta.foto',
                    'payment_details.permuta.valore_permuta',
                    'payment_details.permuta.data',
                    'payment_details.permuta.note',
                    // Finanziamento
                    'payment_details.finanziamento.importo_totale',
                    'payment_details.finanziamento.anticipo',
                    'payment_details.finanziamento.numero_rate',
                    'payment_details.finanziamento.importo_rata',
                    'payment_details.finanziamento.istituto_finanziario',
                    'payment_details.finanziamento.data_inizio',
                    'payment_details.finanziamento.note',
                    // Misto
                    'payment_details.misto.metodi',
                    'payment_details.misto.totale',
                    'payment_details.misto.note_generali',
                ];
                
                foreach ($fieldsToReset as $field) {
                    $set($field, null);
                }
            }),
    ])->columns(2),

// Sezione Pagamento Contanti
Forms\Components\Section::make('Pagamento Contanti')
    ->schema([
        Forms\Components\TextInput::make('payment_details.contanti.importo')
            ->label('Importo')
            ->numeric()
            ->prefix('€')
            ->step(0.01)
            ->required(),
        Forms\Components\DatePicker::make('payment_details.contanti.data')
            ->label('Data Pagamento')
            ->default(now())
            ->required(),
        Forms\Components\Textarea::make('payment_details.contanti.note')
            ->label('Note')
            ->maxLength(500)
            ->rows(2),
    ])
    ->columns(2)
    ->visible(fn (callable $get) => $get('payment_method') === 'contanti'),

// Sezione Pagamento Bonifico
Forms\Components\Section::make('Pagamento Bonifico')
    ->schema([
        Forms\Components\TextInput::make('payment_details.bonifico.importo')
            ->label('Importo')
            ->numeric()
            ->prefix('€')
            ->step(0.01)
            ->required(),
        Forms\Components\TextInput::make('payment_details.bonifico.da_chi')
            ->label('Da Chi')
            ->maxLength(255)
            ->required(),
        Forms\Components\TextInput::make('payment_details.bonifico.banca')
            ->label('Banca')
            ->maxLength(255)
            ->required(),
        Forms\Components\DatePicker::make('payment_details.bonifico.data')
            ->label('Data Bonifico')
            ->default(now())
            ->required(),
        Forms\Components\Textarea::make('payment_details.bonifico.note')
            ->label('Note')
            ->maxLength(500)
            ->rows(2)
            ->columnSpanFull(),
    ])
    ->columns(2)
    ->visible(fn (callable $get) => $get('payment_method') === 'bonifico'),

// Sezione Pagamento Permuta
Forms\Components\Section::make('Pagamento Permuta')
    ->schema([
        Forms\Components\TextInput::make('payment_details.permuta.nome_veicolo')
            ->label('Nome Veicolo')
            ->maxLength(255)
            ->required(),
        Forms\Components\TextInput::make('payment_details.permuta.targa')
            ->label('Targa')
            ->maxLength(20)
            ->required(),
        Forms\Components\TextInput::make('payment_details.permuta.anno')
            ->label('Anno')
            ->numeric()
            ->minValue(1900)
            ->maxValue(date('Y'))
            ->required(),
        Forms\Components\TextInput::make('payment_details.permuta.colore')
            ->label('Colore')
            ->maxLength(50)
            ->required(),
        Forms\Components\FileUpload::make('payment_details.permuta.foto')
            ->label('Foto Veicolo')
            ->image()
            ->multiple()
            ->directory('permute')
            ->maxFiles(5)
            ->imageEditor()
            ->columnSpanFull(),
        Forms\Components\TextInput::make('payment_details.permuta.valore_permuta')
            ->label('Valore Permuta')
            ->numeric()
            ->prefix('€')
            ->step(0.01)
            ->required(),
        Forms\Components\DatePicker::make('payment_details.permuta.data')
            ->label('Data Permuta')
            ->default(now())
            ->required(),
        Forms\Components\Textarea::make('payment_details.permuta.note')
            ->label('Note')
            ->maxLength(500)
            ->rows(2)
            ->columnSpanFull(),
    ])
    ->columns(2)
    ->visible(fn (callable $get) => $get('payment_method') === 'permuta'),

// Sezione Pagamento Finanziamento
Forms\Components\Section::make('Pagamento Finanziamento')
    ->schema([
        Forms\Components\TextInput::make('payment_details.finanziamento.importo_totale')
            ->label('Importo Totale')
            ->numeric()
            ->prefix('€')
            ->step(0.01)
            ->required(),
        Forms\Components\TextInput::make('payment_details.finanziamento.anticipo')
            ->label('Anticipo')
            ->numeric()
            ->prefix('€')
            ->step(0.01)
            ->default(0),
        Forms\Components\TextInput::make('payment_details.finanziamento.numero_rate')
            ->label('Numero Rate')
            ->numeric()
            ->minValue(1),
        Forms\Components\TextInput::make('payment_details.finanziamento.importo_rata')
            ->label('Importo Rata')
            ->numeric()
            ->prefix('€')
            ->step(0.01),
        Forms\Components\TextInput::make('payment_details.finanziamento.istituto_finanziario')
            ->label('Istituto Finanziario')
            ->maxLength(255)
            ->required(),
        Forms\Components\DatePicker::make('payment_details.finanziamento.data_inizio')
            ->label('Data Inizio')
            ->default(now()),
        Forms\Components\Textarea::make('payment_details.finanziamento.note')
            ->label('Note')
            ->maxLength(500)
            ->rows(2)
            ->columnSpanFull(),
    ])
    ->columns(2)
    ->visible(fn (callable $get) => $get('payment_method') === 'finanziamento'),

// Sezione Pagamento Misto
Forms\Components\Section::make('Pagamento Misto')
    ->schema([
        Forms\Components\Repeater::make('payment_details.misto.metodi')
            ->label('Metodi di Pagamento')
            ->schema([
                Forms\Components\Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'contanti' => 'Contanti',
                        'bonifico' => 'Bonifico',
                        'permuta' => 'Permuta',
                        'finanziamento' => 'Finanziamento',
                    ])
                    ->required()
                    ->reactive(),
                
                // Campi Contanti
                Forms\Components\TextInput::make('importo')
                    ->label('Importo')
                    ->numeric()
                    ->prefix('€')
                    ->step(0.01)
                    ->required()
                    ->visible(fn (callable $get) => in_array($get('tipo'), ['contanti', 'bonifico', 'finanziamento'])),
                
                Forms\Components\DatePicker::make('data')
                    ->label('Data')
                    ->default(now())
                    ->required()
                    ->visible(fn (callable $get) => in_array($get('tipo'), ['contanti', 'bonifico', 'finanziamento'])),
                
                // Campi Bonifico
                Forms\Components\TextInput::make('da_chi')
                    ->label('Da Chi')
                    ->maxLength(255)
                    ->required()
                    ->visible(fn (callable $get) => $get('tipo') === 'bonifico'),
                
                Forms\Components\TextInput::make('banca')
                    ->label('Banca')
                    ->maxLength(255)
                    ->required()
                    ->visible(fn (callable $get) => $get('tipo') === 'bonifico'),
                
                // Campi Permuta
                Forms\Components\TextInput::make('nome_veicolo')
                    ->label('Nome Veicolo')
                    ->maxLength(255)
                    ->required()
                    ->visible(fn (callable $get) => $get('tipo') === 'permuta'),
                
                Forms\Components\TextInput::make('targa')
                    ->label('Targa')
                    ->maxLength(20)
                    ->required()
                    ->visible(fn (callable $get) => $get('tipo') === 'permuta'),
                
                Forms\Components\TextInput::make('anno')
                    ->label('Anno')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(date('Y'))
                    ->required()
                    ->visible(fn (callable $get) => $get('tipo') === 'permuta'),
                
                Forms\Components\TextInput::make('colore')
                    ->label('Colore')
                    ->maxLength(50)
                    ->required()
                    ->visible(fn (callable $get) => $get('tipo') === 'permuta'),
                
                Forms\Components\TextInput::make('valore_permuta')
                    ->label('Valore Permuta')
                    ->numeric()
                    ->prefix('€')
                    ->step(0.01)
                    ->required()
                    ->visible(fn (callable $get) => $get('tipo') === 'permuta'),
                
                Forms\Components\DatePicker::make('data_permuta')
                    ->label('Data Permuta')
                    ->default(now())
                    ->required()
                    ->visible(fn (callable $get) => $get('tipo') === 'permuta'),
                
                Forms\Components\FileUpload::make('foto')
                    ->label('Foto Veicolo')
                    ->image()
                    ->multiple()
                    ->directory('permute')
                    ->maxFiles(5)
                    ->imageEditor()
                    ->columnSpanFull()
                    ->visible(fn (callable $get) => $get('tipo') === 'permuta'),
                
                // Campi Finanziamento
                Forms\Components\TextInput::make('anticipo')
                    ->label('Anticipo')
                    ->numeric()
                    ->prefix('€')
                    ->step(0.01)
                    ->default(0)
                    ->visible(fn (callable $get) => $get('tipo') === 'finanziamento'),
                
                Forms\Components\TextInput::make('numero_rate')
                    ->label('Numero Rate')
                    ->numeric()
                    ->minValue(1)
                    ->visible(fn (callable $get) => $get('tipo') === 'finanziamento'),
                
                Forms\Components\TextInput::make('importo_rata')
                    ->label('Importo Rata')
                    ->numeric()
                    ->prefix('€')
                    ->step(0.01)
                    ->visible(fn (callable $get) => $get('tipo') === 'finanziamento'),
                
                Forms\Components\TextInput::make('istituto_finanziario')
                    ->label('Istituto Finanziario')
                    ->maxLength(255)
                    ->required()
                    ->visible(fn (callable $get) => $get('tipo') === 'finanziamento'),
                
                Forms\Components\DatePicker::make('data_inizio')
                    ->label('Data Inizio')
                    ->default(now())
                    ->visible(fn (callable $get) => $get('tipo') === 'finanziamento'),
                
                // Campo Note generale per tutti
                Forms\Components\Textarea::make('note')
                    ->label('Note')
                    ->maxLength(500)
                    ->rows(2)
                    ->columnSpanFull(),
            ])
            ->columns(3)
            ->defaultItems(1)
            ->addActionLabel('Aggiungi Metodo')
            ->collapsed()
            ->itemLabel(fn (array $state): ?string => $state['tipo'] ?? 'Nuovo metodo')
            ->columnSpanFull(),
        Forms\Components\TextInput::make('payment_details.misto.totale')
            ->label('Totale Complessivo')
            ->numeric()
            ->prefix('€')
            ->step(0.01)
            ->required(),
        Forms\Components\Textarea::make('payment_details.misto.note_generali')
            ->label('Note Generali')
            ->maxLength(500)
            ->rows(2)
            ->columnSpanFull(),
    ])
    ->columns(2)
    ->visible(fn (callable $get) => $get('payment_method') === 'misto'),

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
            ->previewable(true)
            ->imagePreviewHeight('150')
            ->enableOpen()
            ->enableDownload()
            ->loadingIndicatorPosition('center')
            ->afterStateHydrated(function (Forms\Components\FileUpload $component, $record) {
                if (!$record || !$record->exists) return;
                
                $documents = $record->documents()
                    ->where('category', 'libretto')
                    ->pluck('file_path')
                    ->toArray();
                
                $component->state($documents);
            })
            ->saveRelationshipsUsing(function ($component, $state, $record) {
                if (!$state) return;
                
                $record->documents()->where('category', 'libretto')->delete();
                
                foreach ($state as $filePath) {
                    $record->documents()->create([
                        'category' => 'libretto',
                        'file_path' => $filePath,
                        'filename' => basename($filePath),
                        'original_name' => basename($filePath),
                        'file_type' => pathinfo($filePath, PATHINFO_EXTENSION),
                        'mime_type' => mime_content_type(storage_path('app/public/' . $filePath)),
                        'file_size' => filesize(storage_path('app/public/' . $filePath)),
                    ]);
                }
            })
            ->dehydrated(false),
            
        Forms\Components\FileUpload::make('riparazione_files')
            ->label('Documenti di Riparazione (Max 10)')
            ->multiple()
            ->maxFiles(10)
            ->acceptedFileTypes(['application/pdf', 'image/*'])
            ->directory('vehicles/riparazione')
            ->previewable(true)
            ->imagePreviewHeight('150')
            ->enableOpen()
            ->enableDownload()
            ->loadingIndicatorPosition('center')
            ->afterStateHydrated(function (Forms\Components\FileUpload $component, $record) {
                if (!$record || !$record->exists) return;
                
                $documents = $record->documents()
                    ->where('category', 'riparazione')
                    ->pluck('file_path')
                    ->toArray();
                
                $component->state($documents);
            })
            ->saveRelationshipsUsing(function ($component, $state, $record) {
                if (!$state) return;
                
                $record->documents()->where('category', 'riparazione')->delete();
                
                foreach ($state as $filePath) {
                    $record->documents()->create([
                        'category' => 'riparazione',
                        'file_path' => $filePath,
                        'filename' => basename($filePath),
                        'original_name' => basename($filePath),
                        'file_type' => pathinfo($filePath, PATHINFO_EXTENSION),
                        'mime_type' => mime_content_type(storage_path('app/public/' . $filePath)),
                        'file_size' => filesize(storage_path('app/public/' . $filePath)),
                    ]);
                }
            })
            ->dehydrated(false),
            
        Forms\Components\FileUpload::make('atto_vendita_files')
            ->label('Atto di Vendita (Max 3)')
            ->multiple()
            ->maxFiles(3)
            ->acceptedFileTypes(['application/pdf', 'image/*'])
            ->directory('vehicles/atto_vendita')
            ->reorderable()
            ->previewable(true)
            ->imagePreviewHeight('150')
            ->enableOpen()
            ->enableDownload()
            ->loadingIndicatorPosition('center')
            ->afterStateHydrated(function (Forms\Components\FileUpload $component, $record) {
                if (!$record || !$record->exists) return;
                
                $documents = $record->documents()
                    ->where('category', 'atto_vendita')
                    ->pluck('file_path')
                    ->toArray();
                
                $component->state($documents);
            })
            ->saveRelationshipsUsing(function ($component, $state, $record) {
                if (!$state) return;
                
                $record->documents()->where('category', 'atto_vendita')->delete();
                
                foreach ($state as $filePath) {
                    $record->documents()->create([
                        'category' => 'atto_vendita',
                        'file_path' => $filePath,
                        'filename' => basename($filePath),
                        'original_name' => basename($filePath),
                        'file_type' => pathinfo($filePath, PATHINFO_EXTENSION),
                        'mime_type' => mime_content_type(storage_path('app/public/' . $filePath)),
                        'file_size' => filesize(storage_path('app/public/' . $filePath)),
                    ]);
                }
            })
            ->dehydrated(false),
    ])->columns(1),

                Forms\Components\Section::make('Stato del Veicolo')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Stato')
                            ->options([
                                'disponibile' => 'Disponibile',
                                'in_arrivo' => 'In Arrivo',
                                'venduto' => 'Venduto',
                                'archiviato' => 'Archiviato',
                            ])
                            ->default('archiviato')
                            ->required(),
                        Forms\Components\TextInput::make('sale_price')
                            ->label('Prezzo di Vendita')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->required(),
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
                Tables\Columns\TextColumn::make('registry_number')  
                    ->label('N° Registro')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('archive_number') 
                    ->label('N° Archiviazione')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('registration_year')
                    ->label('Anno')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cost')
                    ->label('Totale Costo')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_price')
                    ->label('Prezzo Vendita')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('profit')
                    ->label('Guadagno Finale')
                    ->money('EUR')
                    ->getStateUsing(function ($record) {
                        return $record->sale_price - $record->total_cost;
                    })
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                    ->weight('bold'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Stato')
                    ->colors([
                        'danger' => 'archiviato',
                    ])
                    ->formatStateUsing(fn (string $state): string => 'Archiviato'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Pagamento')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('archive_number')
                    ->label('N° Archiviazione')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creato il')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('profitable')
                    ->label('Solo Profittevoli')
                    ->query(fn (Builder $query): Builder => 
                        $query->whereRaw('sale_price > total_cost')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Nessuna azione bulk per veicoli archiviati
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListArchivedVehicles::route('/'),
            'create' => Pages\CreateArchivedVehicle::route('/create'),
            'edit' => Pages\EditArchivedVehicle::route('/{record}/edit'),
            'view' => Pages\ViewArchivedVehicle::route('/{record}'),
        ];
    }
    
 private static function calculateTotalCost(Forms\Set $set, Forms\Get $get): void
    {
        try {
            $fields = [
                'purchase_price', 'broker', 'transport', 'mechatronics', 
                'bodywork', 'tire_shop', 'upholstery', 'travel', 
                'inspection', 'miscellaneous', 'spare_parts', 'washing','passaggio', 'accessori'
            ];
            
            $total = collect($fields)
                ->sum(fn($field) => (float) ($get($field) ?? 0));

            $set('total_cost', number_format($total, 2, '.', ''));
            
        } catch (\Exception $e) {
            \Log::warning('Calcolo costi fallito: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ROCContinousResource\Pages;
use App\Filament\Resources\ROCContinousResource\Pages\CreateROCContinous;
use App\Models\ContinousReport;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ROCContinousResource extends Resource
{
    protected static ?string $model = ContinousReport::class;

    protected static ?string $modelLabel = 'Transaction';

    protected static ?string $pluralModelLabel = 'Report of Collection (Continous)';

    protected static ?string $navigationLabel = 'Continous Receipts';

    protected static ?string $navigationGroup = 'Reports of Collection';

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('or_number')
                    ->label('OR Number')
                    ->required()
                    ->reactive()
                    ->debounce('500ms')
                    ->placeholder('Enter OR Number'),
                TextInput::make('payor_name')
                    ->label('Payor Name')
                    ->required()
                    ->placeholder('Enter Payor Name')
                    ->disabled(fn ($livewire) => $livewire instanceof CreateROCContinous && $livewire->ORExists),
                TextInput::make('student_number')
                    ->label('Student Number')
                    ->required()
                    ->reactive()
                    ->mask('9999-99999')
                    // ->regex('/\d{4}-\d{5}/')
                    ->placeholder('20XX-XXXXX')
                    ->disabled(fn ($livewire) => $livewire instanceof CreateROCContinous && $livewire->ORExists),
                Select::make('college')
                    ->label('College')
                    ->required()
                    ->options([
                        'CAUP' => 'College of Architecture and Urban Planning',
                        'CED' => 'College of Education',
                        'COE' => 'College of Engineering',
                        'CHASS' => 'College of Humanities, Arts, and Social Sciences',
                        'CM' => 'College of Medicine',
                        'CN' => 'College of Nursing',
                        'CPT' => 'College of Physical Therapy',
                        'CS' => 'College of Science',
                        'COL' => 'College of Law',
                        'CISTM' => 'College of Information Systems and Technology Management',
                    ])
                    ->placeholder('Select College')
                    ->searchable()
                    ->disabled(fn ($livewire) => $livewire instanceof CreateROCContinous && $livewire->ORExists),
                TextInput::make('transaction_code')
                    ->label('Transaction Code')
                    ->required()
                    ->placeholder('Enter Transaction Code'),
                TextInput::make('amount')
                    ->label('Amount')
                    ->required()
                    ->numeric()
                    ->prefix('PHP')
                    ->inputMode('decimal')
                    ->placeholder('Enter Amount'),
                Toggle::make('has_cheque')
                    ->label('Transaction with Cheque?')
                    ->live()
                    ->default(false),
                Textarea::make('remarks')
                    ->label('Remarks')
                    ->placeholder('Enter Remarks'),
                Group::make([
                    TextInput::make('bank_name')
                        ->label('Bank')
                        ->placeholder('Enter Bank')
                        ->requiredWith('has_cheque')
                        ->disabled(fn ($livewire) => $livewire instanceof CreateROCContinous && $livewire->ORExists),
                    TextInput::make('bank_number')
                        ->label('Cheque Number')
                        ->placeholder('Enter Cheque Number')
                        ->requiredWith('has_cheque')
                        ->disabled(fn ($livewire) => $livewire instanceof CreateROCContinous && $livewire->ORExists)
                ])
                    ->visible(fn (Get $get): bool => $get('has_cheque') || false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // show the columns
                TextColumn::make('or_number')
                    ->label('OR Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payor_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('student_number')
                    ->label('Student Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('college')
                    ->label('College')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transaction_code')
                    ->label('Transaction Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->getStateUsing(function ($record) {
                        return ContinousReport::where('or_number', $record->or_number)->sum('amount');
                    })
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->searchable()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_at')
                            ->default(today())
                            ->label('Created At')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_at'],
                                fn (Builder $query, $value) => $query->whereDate('created_at', '=', $value)
                            );
                    })
            ])
            //->actions([
            //    Tables\Actions\ViewAction::make(),
            //    Tables\Actions\EditAction::make(),
            //])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make()->fromModel()->except([
                                'id',
                                'transaction_type',
                            ])->withFilename(date('Y-m-d') . ' - Continuous Report'),
                        ]),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('transaction_type', 0);
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
            'index' => Pages\ListROCContinouses::route('/'),
            'create' => Pages\CreateROCContinous::route('/create'),
            'view' => Pages\ViewROCContinous::route('/{record}'),
            'edit' => Pages\EditROCContinous::route('/{record}/edit'),
        ];
    }
}

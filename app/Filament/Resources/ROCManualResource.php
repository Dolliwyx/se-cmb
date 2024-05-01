<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ROCManualResource\Pages;
use App\Filament\Resources\ROCManualResource\RelationManagers;
use App\Models\ROCManual;
use Filament\Forms;
use Filament\Checkbox;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ROCManualResource extends Resource
{
    protected static ?string $model = ROCManual::class;

    protected static ?string $modelLabel = 'Report';

    protected static ?string $pluralModelLabel = 'Report of Collection (Manual)';

    protected static ?string $navigationLabel = 'Manual Receipts';

    protected static ?string $navigationGroup = 'Reports of Collection';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('or_number')
                    ->label('OR Number')
                    ->required()
                    ->unique()
                    ->placeholder('Enter OR Number'),
                TextInput::make('payor_name')
                    ->label('Payor Name')
                    ->required()
                    ->placeholder('Enter Payor Name'),
                TextInput::make('student_number')
                    ->label('Student Number')
                    ->required()
                    ->mask('9999-99999')
                    ->regex('/\d{4}-\d{5}/')
                    ->placeholder('20XX-XXXXX'),
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
                    ])
                    ->placeholder('Select College'),
                TextInput::make('transaction_code')
                    ->label('Transaction Code')
                    ->required()
                    ->placeholder('Enter Transaction Code'),
                TextInput::make('amount')
                    ->label('Amount')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->placeholder('Enter Amount'),
                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->placeholder('Enter Total Amount'),
                Textarea::make('remarks')
                    ->label('Remarks')
                    ->required()
                    ->placeholder('Enter Remarks'),
                Toggle::make('has_cheque')
                    ->label('Transaction with Cheque?')
                    ->live()
                    ->default(false),
                Group::make([
                    TextInput::make('cheque_bank')
                        ->label('Bank')
                        ->placeholder('Enter Bank')
                        ->requiredWith('has_cheque'),
                    TextInput::make('cheque_number')
                        ->label('Cheque Number')
                        ->placeholder('Enter Cheque Number')
                        ->requiredWith('has_cheque')
                ])
                    ->visible(fn (Get $get): bool => $get('has_cheque')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('PHP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->searchable(),
                TextColumn::make('cheque_bank')
                    ->label('Bank')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cheque_number')
                    ->label('Cheque Number')
                    ->searchable()
                    ->sortable(),
            ])
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListROCManuals::route('/'),
            'create' => Pages\CreateROCManual::route('/create'),
            'view' => Pages\ViewROCManual::route('/{record}'),
            'edit' => Pages\EditROCManual::route('/{record}/edit'),
        ];
    }
}

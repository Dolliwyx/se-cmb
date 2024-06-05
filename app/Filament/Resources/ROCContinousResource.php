<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ROCContinousResource\Pages;
use App\Filament\Resources\ROCContinousResource\RelationManagers;
use App\Models\ROCContinous;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ROCContinousResource extends Resource
{
    protected static ?string $model = ROCContinous::class;

    protected static ?string $modelLabel = 'Report';

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
                    ->prefix('PHP')
                    ->inputMode('decimal')
                    ->placeholder('Enter Amount'),
                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->required()
                    ->numeric()
                    ->prefix('PHP')
                    ->inputMode('decimal')
                    ->placeholder('Enter Total Amount'),
                Textarea::make('remarks')
                    ->label('Remarks')
                    ->required()
                    ->placeholder('Enter Remarks'),
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
                    ->label('Payor Name')
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
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
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

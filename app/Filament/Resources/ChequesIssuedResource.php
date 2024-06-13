<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChequesIssuedResource\Pages;
use App\Filament\Resources\ChequesIssuedResource\RelationManagers;
use App\Models\ChequesIssued;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ChequesIssuedResource extends Resource
{
    protected static ?string $model = ChequesIssued::class;

    protected static ?string $modelLabel = 'Cheque';

    protected static ?string $pluralModelLabel = 'Cheques Issued';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $slug = 'cheques-issued';

    public static function form(Form $form): Form
    {
        $options = collect(self::getPayables())->mapWithKeys(function ($item) {
            return [$item[1] => $item[0]];
        })->toArray();

        return $form
            ->schema([
                Select::make('BUR')
                    ->label('BUR Number')
                    ->required()
                    ->reactive()
                    ->options($options)
                    ->searchable(),
                TextInput::make('cheque_number')
                    ->label('Cheque Number')
                    ->required()
                    ->placeholder('Enter Cheque Number'),
                TextInput::make('payee')
                    ->label('Payee')
                    ->required()
                    ->disabled(true)
                    ->placeholder('Enter Payee'),
                TextInput::make('nature')
                    ->label('Nature')
                    ->required()
                    ->placeholder('Enter Nature'),
                TextInput::make('amount')
                    ->label('Amount')
                    ->required()
                    ->numeric()
                    ->disabled(true)
                    ->inputMode('decimal')
                    ->prefix('PHP')
                    ->placeholder('Enter Amount'),
                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        'Pending' => 'Pending',
                        'For Verification' => 'For Verification',
                        'Cleared' => 'Cleared',
                        'Rejected' => 'Rejected',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cheque_number')
                    ->label('Cheque Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payee')
                    ->label('Payee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nature')
                    ->label('Nature')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('PHP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('status')
                    ->label('Status')
                    ->form([
                        Select::make('status')
                            ->options([
                                'Pending' => 'Pending',
                                'For Verification' => 'For Verification',
                                'Cleared' => 'Cleared',
                                'Rejected' => 'Rejected',
                            ])
                            ->placeholder('Select Status'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['status'], fn ($query, $value) => $query->where('status', $value));
                    }),
                Filter::make('created_at')
                    ->label('Date Created')
                    ->form([
                        DatePicker::make('created_at')
                            ->label('Date Created')
                            ->placeholder('Select Date'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['created_at'], fn ($query, $value) => $query->whereDate('created_at', $value));
                    })
            ])
            //->actions([
            //    Tables\Actions\ViewAction::make(),
            //    Tables\Actions\EditAction::make(),
            //])
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
            'index' => Pages\ListChequesIssueds::route('/'),
            'create' => Pages\CreateChequesIssued::route('/create'),
            'view' => Pages\ViewChequesIssued::route('/{record}'),
            'edit' => Pages\EditChequesIssued::route('/{record}/edit'),
        ];
    }

    private static function getPayables()
    {
        $result = DB::table('payables')
            ->select(
                DB::raw('CONCAT(bur, " - ", SupplierName) as formattedString'),
                'bur'
            )
            ->pluck('formattedString', 'bur')
            ->map(function ($formattedString, $bur) {
                return [$formattedString, $bur];
            })
            ->values()
            ->toArray();
        return $result;
    }

    private static function updateFormFields($bur, $set)
    {
        $payable = DB::table('payables')->where('bur', $bur)->first();
        $particulars = DB::table('particular')->where('BUR', $bur)->sum('ParticularAmount');
        if ($payable) {
            $set('BUR', $bur);
            $set('payee', $payable->SupplierName);
            $set('amount', $particulars);
        }
    }
}

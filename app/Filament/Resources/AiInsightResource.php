<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiInsightResource\Pages;
use App\Models\AiInsight;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AiInsightResource extends Resource
{
    protected static ?string $model = AiInsight::class;

    protected static ?string $navigationIcon =
        'heroicon-o-sparkles';

    protected static ?string $navigationLabel =
        'AI Insights';

    protected static ?string $navigationGroup =
        'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Textarea::make(
                    'hasil_analisis'
                )

                    ->label(
                        'Hasil Analisis AI'
                    )

                    ->rows(12)

                    ->required()

                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([

                Tables\Columns\TextColumn::make(
                    'id'
                )

                    ->label('ID')

                    ->sortable(),

                Tables\Columns\TextColumn::make(
                    'hasil_analisis'
                )

                    ->label(
                        'Hasil Analisis AI'
                    )

                    ->limit(100)

                    ->wrap()

                    ->searchable(),

                Tables\Columns\TextColumn::make(
                    'created_at'
                )

                    ->label(
                        'Tanggal Analisis'
                    )

                    ->dateTime()

                    ->sortable(),

            ])

            ->filters([
                //
            ])

            ->actions([

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

            ])

            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),

                ]),

            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [

            'index' =>
                Pages\ListAiInsights::route('/'),

            'create' =>
                Pages\CreateAiInsight::route('/create'),

            'edit' =>
                Pages\EditAiInsight::route('/{record}/edit'),

        ];
    }
}
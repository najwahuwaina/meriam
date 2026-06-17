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
        'Analisis Penjualan AI';

    protected static ?string $navigationGroup =
        'Laporan';

    protected static ?string $modelLabel =
        'Analisis AI';

    protected static ?string $pluralModelLabel =
        'Riwayat Analisis AI';

    public static function getNavigationBadge(): ?string
    {
        return (string) AiInsight::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Textarea::make(
                    'hasil_analisis'
                )
                    ->label(
                        '📊 Hasil Analisis AI'
                    )
                    ->rows(25)
                    ->readOnly()
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([

                Tables\Columns\TextColumn::make(
                    'hasil_analisis'
                )
                    ->label(
                        '📊 Hasil Analisis AI'
                    )
                    ->limit(200)
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make(
                    'created_at'
                )
                    ->label(
                        '📅 Tanggal Analisis'
                    )
                    ->dateTime('d F Y H:i')
                    ->badge()
                    ->color('success')
                    ->sortable(),

            ])

            ->filters([
                //
            ])

            ->actions([

                Tables\Actions\ViewAction::make()
            ->slideOver()
            ->modalHeading('📊 Detail Analisis Penjualan AI')
            ->modalWidth('7xl'),

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
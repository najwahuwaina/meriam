<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AkunResource\Pages;
use App\Models\Akun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AkunResource extends Resource
{
    protected static ?string $model = Akun::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Akun';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $modelLabel = 'Akun';

    protected static ?string $pluralModelLabel = 'Daftar Akun';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->description('Masukkan data akun dengan lengkap')
                    ->icon('heroicon-o-banknotes')
                    ->schema([

                        Forms\Components\TextInput::make('kode_akun')
                            ->label('Kode Akun')
                            ->placeholder('Contoh: 1101')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->prefixIcon('heroicon-o-hashtag'),

                        Forms\Components\TextInput::make('nama_akun')
                            ->label('Nama Akun')
                            ->placeholder('Contoh: Kas')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-document-text'),

                        Forms\Components\Select::make('header_akun')
                            ->label('Kategori Akun')
                            ->options([
                                'Aset' => 'Aset',
                                'Liabilitas' => 'Liabilitas',
                                'Ekuitas' => 'Ekuitas',
                                'Pendapatan' => 'Pendapatan',
                                'Beban' => 'Beban',
                            ])
                            ->searchable()
                            ->native(false)
                            ->required(),

                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('kode_akun')
            ->columns([
                Tables\Columns\TextColumn::make('kode_akun')
                    ->label('Kode Akun')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('nama_akun')
                    ->label('Nama Akun')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('header_akun')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aset' => 'success',
                        'Liabilitas' => 'warning',
                        'Ekuitas' => 'primary',
                        'Pendapatan' => 'info',
                        'Beban' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('header_akun')
                    ->label('Kategori')
                    ->options([
                        'Aset' => 'Aset',
                        'Liabilitas' => 'Liabilitas',
                        'Ekuitas' => 'Ekuitas',
                        'Pendapatan' => 'Pendapatan',
                        'Beban' => 'Beban',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make()
                    ->color('warning'),

                Tables\Actions\DeleteAction::make()
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Data Akun')
            ->emptyStateDescription('Silakan tambahkan data akun terlebih dahulu.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAkuns::route('/'),
            'create' => Pages\CreateAkun::route('/create'),
            'edit' => Pages\EditAkun::route('/{record}/edit'),
        ];
    }
}
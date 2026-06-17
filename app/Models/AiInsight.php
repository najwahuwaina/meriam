<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiInsight extends Model
{
    use HasFactory;

    protected $fillable = [
        'hasil_analisis',
    ];

    /**
     * Versi teks bersih dari hasil_analisis, tanpa simbol markdown
     * (**, ###, ---, dll), dipakai untuk ditampilkan di tabel & detail.
     */
    public function getHasilAnalisisBersihAttribute(): string
    {
        $text = $this->hasil_analisis ?? '';

        // Hilangkan heading markdown (###, ##, #)
        $text = preg_replace('/^\s{0,3}#{1,6}\s*/m', '', $text);

        // Hilangkan bold/italic (**text**, *text*)
        $text = preg_replace('/\*\*(.*?)\*\*/s', '$1', $text);
        $text = preg_replace('/\*(.*?)\*/s', '$1', $text);

        // Hilangkan garis pembatas (---, ***, ___)
        $text = preg_replace('/^\s*([-*_]){3,}\s*$/m', '', $text);

        // Rapikan bullet list "* item" jadi "- item"
        $text = preg_replace('/^\s*\*\s+/m', '- ', $text);

        // Rapikan baris kosong berlebih
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        return trim($text);
    }

    /**
     * Ringkasan singkat (1 kalimat / sekian karakter) untuk preview di tabel.
     */
    public function getRingkasanAnalisisAttribute(): string
    {
        $clean = $this->hasil_analisis_bersih;

        // Ambil sampai akhir kalimat pertama yang masuk akal, atau potong 150 char
        $clean = str_replace("\n", ' ', $clean);
        $clean = preg_replace('/\s+/', ' ', $clean);

        return mb_substr($clean, 0, 150) . (mb_strlen($clean) > 150 ? '...' : '');
    }
}
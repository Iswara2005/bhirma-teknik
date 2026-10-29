<?php

namespace App\Support;

final class Whatsapp
{
    /** Link WhatsApp dengan sapaan otomatis: "Halo {perusahaan}, {pesan}". */
    public static function link(string $message): string
    {
        $text = 'Halo '.config('company.name').', '.$message;

        return 'https://wa.me/'.config('company.wa').'?text='.urlencode($text);
    }

    /** Link WhatsApp untuk menanyakan satu topik: "Halo {perusahaan}, saya ingin menanyakan {topik}." */
    public static function ask(string $topic): string
    {
        return self::link("saya ingin menanyakan {$topic}.");
    }
}
<?php

namespace App\Libraries\Cells;

/**
 * membuat breadcrumb navigation
 */
class Breadcrumb
{
    /**
     * @param array $items
     * array yang mengandung informasi uri dan textnya, berurutan dari kiri ke kanan
     * item array terakhir tidak membutuhkan atribut uri, hanya teks saja
     * 
     * contoh: [['text' => 'Indeks Kegiatan', 'uri' => 'personal'], ['text' => 'Kegiatan Saya']] 
     */
    public function build(array $items)
    {
        return view('cells/breadcrumb', ['items' => $items]);
    }
}

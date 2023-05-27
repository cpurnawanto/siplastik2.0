<?php

namespace App\Libraries\Cells;

/**
 * membuat view cell untuk opsi di navbar
 * navbar akan bergantung dengan tipe user yang login
 * navbar bisa mengetahui tipe user dengan memanfaatkan informasi atribut user di objek request
 * atribut user sebelumnya ditambahkan di App\Filters\PegawaiLoggedInFilter
 */
class Navbar
{
    public function build()
    {
        $request = \Config\Services::request();

        if (isset($request->user)) {
            return view('cells/navbar', ['user' => $request->user]);
        } else {
            return view('cells/empty');
        }
    }
}

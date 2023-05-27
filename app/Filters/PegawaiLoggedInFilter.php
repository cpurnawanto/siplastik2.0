<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * filter untuk menentukan apakah pegawai log in atau tidak
 * akan menambahkan atribut detail_pegawai pada objek RequestInterface $request jika berhasil login
 * dan dapat digunakan sesuai kebutuhan dengan memanggil \Config\Services::request()->user;
 */
class PegawaiLoggedInFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $pegawai_model = new \App\Models\PegawaiModel();
        $user = $pegawai_model->getPegawaiSession();

        if (!$user) {
            /**
             * Jika sesi tidak valid, maka sesi akan dihancurkan dan diarahkan ke halaman login
             */
            $pegawai_model->logoutPegawaiSession();
            return redirect()->to(base_url());
        } else {
            /**
             * Jika sesi valid, $request akan ditambahkan atribut user yang berisi detail pegawai 
             */
            $request->user = $user;
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}

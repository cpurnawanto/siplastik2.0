<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * filter untuk menentukan apakah pegawai yang log in mempunyai jabatan struktural atau tidak
 * memanfaatkan objek $request dari App\Filters\PegawaiLoggedInFilter untuk mendapatkan keterangan user login
 * filter ini HARUS diletakkan SETELAH App\Filters\PegawaiLoggedInFilter
 * jika tidak, akan ada error $request->user tidak ada meskipun admin sedang login
 */
class StrukturalFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!$request->user['eselon']) {
            /**
             * Jika tidak mempunyai jabatan struktural (tidak di dalam eselon), maka akan diarahkan ke halaman utama
             */
            return redirect()->to(base_url());
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}

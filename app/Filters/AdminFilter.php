<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * filter untuk menentukan apakah pegawai yang log in seorang admin atau tidak
 * memanfaatkan objek $request dari App\Filters\PegawaiLoggedInFilter untuk mendapatkan keterangan user login
 * filter ini HARUS diletakkan SETELAH App\Filters\PegawaiLoggedInFilter
 * jika tidak, akan ada error $request->user tidak ada meskipun admin sedang login
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!$request->user['is_admin']) {
            /**
             * Jika bukan admin, maka akan diarahkan ke halaman utama
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

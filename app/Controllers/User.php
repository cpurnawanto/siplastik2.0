<?php

namespace App\Controllers;

/**
 * Controller untuk route yang berhubungan dengan user ke sistem
 */
class User extends BaseController
{
    /**
     * Membuka halaman login
     * request login akan diarahkan ke /user/do-login
     */
    public function login()
    {
        /**
         * Jika sebelumnya sudah login, langsung arahkan ke halaman detail pegawai
         */
        $pegawai_model = new \App\Models\PegawaiModel();
        if ($pegawai_model->getPegawaiSession()) {
            return redirect()->to(base_url('berkas'));
        }


        return view('user/login', ['title' => 'Login']);
    }

    /**
     * Memproses request login
     * Jika login berhasil, maka akan diarahkan ke halaman personal
     */
    public function do_login()
    {
        /** dapatkan post data dari halaman login */
        $post_data = $this->request->getPost();

        $detail_pegawai = false;

        if (isset($post_data['login'])) {
            $pegawai_model = new \App\Models\PegawaiModel();
            $detail_pegawai = $pegawai_model->checkLogin(
                $post_data['login']['username'],
                $post_data['login']['password']
            );

            if ($detail_pegawai) {
                /** Jika berhasil login, maka sesi akan dibuat
                 * dan langsung diarahkan ke halaman berkas
                 */
                $pegawai_model->setPegawaiSession($detail_pegawai);
                return redirect()->to(base_url('berkas'));
            }
        }

        /**
         * Jika gagal login, diarahkan ke halaman utama (login)
         */
        $this->session->setFlashdata('errors', ['Kombinasi username dan password salah']);
        return redirect()->to(base_url());
    }

    /**
     * Jika route ini dipanggil, otomatis sesi hancur dan langsung diarahkan ke halaman login
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url());
    }
}

<?php

namespace App\Controllers\Personal;

use App\Controllers\BaseController;

class Profil extends BaseController
{
    public function index()
    {
        return view('personal/profil', [
            'title' => 'Profil Saya',
            'pegawai' => $this->request->user
        ]);
    }

    public function do_ganti_password()
    {
        $post_request = $this->request->getPost();
        if (isset($post_request['password'])) {
            $pegawai_model = new \App\Models\PegawaiModel();
            $form = [
                'password' => $post_request['password']['password'],
                'password2' => $post_request['password']['password2'],
            ];
            if ($pegawai_model->update($this->request->user['id'], $form)) {
                $this->session->setFlashdata('success', 'Password berhasil diganti');
            } else {
                $this->session->setFlashdata('errors', $pegawai_model->errors());
            }
        }

        return redirect()->to(base_url('personal/profil'));
    }
}

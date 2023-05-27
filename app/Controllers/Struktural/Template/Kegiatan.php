<?php

namespace App\Controllers\Struktural\Template;

use App\Controllers\BaseController;

class Kegiatan extends BaseController
{
    public function index()
    {
        $all_template_kegiatan = (new \App\Models\TemplateKegiatanModel())->getTemplateKegiatanMany();
        return view('struktural/indeks_template_kegiatan', [
            'title' => 'Master Template Kegiatan',
            'template_kegiatan' => $all_template_kegiatan
        ]);
    }

    public function pakai($id_template_kegiatan)
    {
        $template_kegiatan_model = new \App\Models\TemplateKegiatanModel();

        $template_kegiatan  = $template_kegiatan_model->find($id_template_kegiatan);
        if (empty($template_kegiatan)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Template kegiatan dengan id = ' . $id_template_kegiatan . ' tidak ada');
        }

        $this->session->setFlashdata('success', 'Form tambah kegiatan menggunakan template kegiatan ' . $template_kegiatan['nama_kegiatan']);
        $this->session->setFlashdata('input', $template_kegiatan);
        return redirect()->to(base_url('struktural/kegiatan/tambah'));
    }

    public function tambah()
    {
        return view('struktural/tambah_template_kegiatan', ['title' => 'Tambah Template Kegiatan']);
    }

    public function do_tambah()
    {
        $post_request = $this->request->getPost();

        if (isset($post_request['kegiatan'])) {
            $template_kegiatan_model = new \App\Models\TemplateKegiatanModel();
            if ($post_request['kegiatan']['hubungkan_ak'] === 'false') {
                unset($post_request['kegiatan']['id_kredit_ahli']);
                unset($post_request['kegiatan']['id_kredit_terampil']);
            } else {
                if (empty($post_request['kegiatan']['id_kredit_ahli'])) {
                    $post_request['kegiatan']['id_kredit_ahli'] = null;
                }

                if (empty($post_request['kegiatan']['id_kredit_terampil'])) {
                    $post_request['kegiatan']['id_kredit_terampil'] = null;
                }
            }

            $kegiatan = $post_request['kegiatan'];

            $kegiatan['is_tampil'] = 1;
            $kegiatan['is_ckp'] = 1;
            $kegiatan['is_usulan'] = 1;
            $kegiatan['is_lock'] = 0;
            $kegiatan['id_pegawai_pembuat'] = $this->request->user['id'];

            if ($template_kegiatan_model->save($kegiatan)) {
                $this->session->setFlashdata('success', 'Input Template Kegiatan ' . $post_request['kegiatan']['nama_kegiatan'] . ' Sukses.');
                $insert_id = $template_kegiatan_model->getInsertID();
                return redirect()->to(base_url('struktural/template/kegiatan/ubah/' . $insert_id));
            } else {
                $this->session->setFlashdata('errors', $template_kegiatan_model->errors());
                $this->session->setFlashdata('input', $post_request['kegiatan']);
                return redirect()->to(base_url('struktural/template/kegiatan/tambah'));
            }
        }
    }

    public function ubah($id_template_kegiatan)
    {
        $template_kegiatan_model = new \App\Models\TemplateKegiatanModel();

        $template_kegiatan  = $template_kegiatan_model->find($id_template_kegiatan);
        if (empty($template_kegiatan)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Template kegiatan dengan id = ' . $id_template_kegiatan . ' tidak ada');
        }
        return view('struktural/ubah_template_kegiatan', ['title' => 'Ubah Template Kegiatan ' . esc($template_kegiatan['nama_kegiatan']), 'template_kegiatan' => $template_kegiatan]);
    }

    public function do_ubah($id_template_kegiatan)
    {
        $post_request = $this->request->getPost();

        if (isset($post_request['kegiatan'])) {
            $template_kegiatan_model = new \App\Models\TemplateKegiatanModel();
            if ($post_request['kegiatan']['hubungkan_ak'] === 'false') {
                $post_request['kegiatan']['id_kredit_ahli'] = null;
                $post_request['kegiatan']['id_kredit_terampil'] = null;
            } else {
                if (empty($post_request['kegiatan']['id_kredit_ahli'])) {
                    $post_request['kegiatan']['id_kredit_ahli'] = null;
                }

                if (empty($post_request['kegiatan']['id_kredit_terampil'])) {
                    $post_request['kegiatan']['id_kredit_terampil'] = null;
                }
            }

            $template_kegiatan = $post_request['kegiatan'];
            unset($post_request['kegiatan']['hubungkan_ak']);
            /**
             * TODO cek alokasi kalau berubah
             */
            if ($template_kegiatan_model->update($id_template_kegiatan, $template_kegiatan)) {
                $this->session->setFlashdata('success', 'Template kegiatan sukses diubah.');
            } else {
                $this->session->setFlashdata('errors', $template_kegiatan_model->errors());
            }

            return redirect()->to(base_url('struktural/template/kegiatan/ubah/' . $id_template_kegiatan));
        }
    }

    public function do_hapus($id_template_kegiatan)
    {
        $post_request = $this->request->getPost();

        if (isset($post_request['kegiatan'])) {
            /**
             * memastikan agar request hapus hanya dilakukan oleh form konfirmasi hapus
             */
            $template_kegiatan = $post_request['kegiatan'];

            if ($id_template_kegiatan == $template_kegiatan['id_template']) {
                $template_kegiatan_model = new \App\Models\TemplateKegiatanModel();
                if ($template_kegiatan_model->delete($template_kegiatan['id_template'])) {
                    $this->session->setFlashdata('success', 'Template Kegiatan sukses dihapus');
                } else {
                    $this->session->setFlashdata('errors', $template_kegiatan_model->errors());
                }
            }


            return redirect()->to(base_url('struktural/template/kegiatan'));
        }

        /**
         * Harusnya kalau valid, tidak sampai sini
         */
        return redirect()->to(base_url('struktural/template/kegiatan/ubah/' . $id_template_kegiatan));
    }
}

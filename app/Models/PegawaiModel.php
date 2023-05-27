<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table = 'master_pegawai';
    protected $beforeInsert = ['hashPassword', 'formatDb'];
    protected $beforeUpdate = ['hashPassword', 'formatDb'];
    protected $allowedFields = [
        'id',
        //'nip_baru',
        //'nip_lama',
        'nama_pegawai',
        'nama_singkat',
        'username',
        'password',
        'password2',
        //'id_golongan',
        //'id_wilayah',
        //'id_unit_kerja',
        //'id_eselon',
        //'id_fungsional',
        'is_aktif',
        'is_admin'
    ];

    /**
     * Jangan pakai soft delete, harusnya foreign key bisa bekerja bagus
     */
    protected $useSoftDeletes = false;

    protected $useTimestamps = true;

    /**
     * Aturan validasi input data pegawai.
     * HARAP CEK BAIK2 $validationMessages juga, jangan sampai tidak sinkron
     */
    protected $validationRules = [
        //'nip_baru' => 'numeric|is_unique[master_pegawai.nip_baru,id,{id}]|required|exact_length[18]',
        //'nip_lama' => 'numeric|is_unique[master_pegawai.nip_lama,id,{id}]|required|exact_length[9]',
        'nama_pegawai' => 'alpha_numeric_punct|min_length[3]|required',
        'nama_singkat' => 'alpha_numeric_punct|min_length[3]|required',
        // setara dengan alpha_dash atau kosong
        'username' => 'regex_match[/^$|^[A-Za-z0-9_]{3,}$/]|is_unique[master_pegawai.username,id,{id}]',
        'password' => 'regex_match[/^$|^.{6,}$/]',
        'password2' => 'required_with[password]|matches[password]',
        //'id_golongan' => 'is_natural|is_not_unique[master_golongan.id]',
        //'id_wilayah' => 'is_natural|is_not_unique[master_wilayah.id]',
        //'id_unit_kerja' => 'is_natural|is_not_unique[master_unit_kerja.id]',
        //'id_eselon' => 'is_natural|less_than_equal_to[4]',
        //'id_fungsional' => 'is_natural|is_not_unique[master_fungsional.id]',
        // boolean dengan true false
        'is_aktif' => 'in_list[false,true]|required',
        'is_admin' => 'in_list[false,true]|required'
    ];

    /**
     * Pesan jika validasi gagal.
     * HARAP CEK BAIK2 $validationRules juga, jangan sampai tidak sinkron
     */
    protected $validationMessages = [
        /* 'nip_baru' => [
            'numeric' => 'NIP baru harus berupa angka',
            'is_unique' => 'NIP baru sudah ada',
            'required' => 'NIP baru kosong',
            'exact_length' => 'NIP baru harus 18 digit'
        ],
        'nip_lama' => [
            'numeric' => 'NIP lama harus berupa angka',
            'is_unique' => 'NIP lama sudah ada',
            'required' => 'NIP lama kosong',
            'exact_length' => 'NIP lama harus 9 digit'
        ], */
        'nama_pegawai' => [
            'alpha_numeric_punct' => 'Nama pegawai mengandung simbol yang dilarang',
            'min_length' => 'Nama pegawai terlalu pendek',
            'required' => 'Nama pegawai kosong'
        ],
        'nama_singkat' => [
            'alpha_numeric_punct' => 'Nama singkat mengandung simbol yang dilarang',
            'min_length' => 'Nama singkat terlalu pendek',
            'required' => 'Nama singkat kosong'
        ],
        'username' => [
            'regex_match' => 'Username hanya boleh mengandung angka, huruf, dan _ (minimal 3 karakter)',
            'is_unique' => 'Username sudah terpakai',
        ],
        'password' => [
            'regex_match' => 'Mohon masukkan password minimal 6 karakter'
        ],
        'password2' => [
            'required_with' => 'Mohon masukkan konfirmasi password',
            'matches' => 'Kedua password tidak sama, mohon cek kembali'
        ],
        /* 'id_golongan' => [
            'is_natural' => 'ID golongan tidak benar',
            'is_not_unique' => 'ID golongan tidak ditemukan'
        ],
        'id_wilayah' => [
            'is_natural' => 'ID wilayah tidak benar',
            'is_not_unique' => 'ID wilayah tidak ditemukan'
        ],
        'id_unit_kerja' => [
            'is_natural' => 'ID unit kerja tidak benar',
            'is_not_unique' => 'ID unit kerja tidak ditemukan'
        ],
        'id_eselon' => [
            'is_natural' => 'ID unit kerja tidak benar',
            'less_than_equal_to' => 'Eselon hanya boleh diisi 0, 1, 2, 3, atau 4'
        ],
        'id_fungsional' => [
            'is_natural' => 'ID fungsional tidak benar',
            'is_not_unique' => 'ID fungsional tidak ditemukan'
        ], */
        'is_aktif' => [
            'in_list' => 'Isian is_aktif salah',
            'required' => 'Isian is_aktif seharusnya diisi'
        ],
        'is_admin' =>
        [
            'in_list' => 'Isian is_admin salah',
            'required' => 'Isian is_admin seharusnya diisi'
        ],
    ];


    /**
     * Inser dan update harus melalui validasi
     */
    protected $skipValidation = false;

    protected function hashPassword(array $data)
    {
        /**
         * Jika tidak ada password, maka hapus data password
         * Ini berguna untuk mencegah update password yang tidak disengaja
         */
        if (empty($data['data']['password'])) {
            unset($data['data']['password']);
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);

        return $data;
    }
    /**
     * Memformat field tabel sebelum dimasukkan ke database 
     */
    protected function formatDb(array $data)
    {
        if (isset($data['data']['is_admin'])) {
            $data['data']['is_admin'] = $data['data']['is_admin'] == 'true' ? true : false;
        }
        if (isset($data['data']['is_aktif'])) {
            $data['data']['is_aktif'] = $data['data']['is_aktif'] == 'true' ? true : false;
        }
        /**
         * Mencegah perubahan username yang tidak disengaja
         */
        if (empty($data['data']['username'])) {
            unset($data['data']['username']);
        }

        unset($data['data']['password2']);

        return $data;
    }

    /**
     * Dapatkan semua detail pegawai untuk daftar pegawai
     * @return array[array]
     * detail pegawai-pegawai
     */
    public function getIndexPegawai()
    {
        //$this->join('master_fungsional', 'master_pegawai.id_fungsional = master_fungsional.id ', 'left');
        //$this->join('master_golongan', 'master_pegawai.id_golongan = master_golongan.id ', 'left');
        //$this->join('master_unit_kerja', 'master_pegawai.id_unit_kerja = master_unit_kerja.id ', 'left');
        //$this->join('master_wilayah', 'master_pegawai.id_wilayah = master_wilayah.id ', 'left');
        //$this->select('master_pegawai.*, id_eselon AS eselon, golongan, fungsional, pangkat, unit_kerja, wilayah');
        $this->select('master_pegawai.*');
        return  $this->findAll();
    }

    /**
     * mendapatkan detail pegawai-pegawai lengkap berdasarkan query where
     * @param array $where
     * array asosiatif where (['kolom' => 'query_pencarian'])
     * 
     * @return array[array]
     * detail pegawai-pegawai jika ada
     */
    public function getPegawaiMany(array $where = [])
    {
        $this->where($where);
        //$this->join('master_fungsional', 'master_pegawai.id_fungsional = master_fungsional.id ', 'left');
        //$this->join('master_golongan', 'master_pegawai.id_golongan = master_golongan.id ', 'left');
        //$this->join('master_unit_kerja', 'master_pegawai.id_unit_kerja = master_unit_kerja.id ', 'left');
        //$this->join('master_wilayah', 'master_pegawai.id_wilayah = master_wilayah.id ', 'left');
        //$this->select('master_pegawai.*, id_eselon AS eselon, golongan, fungsional, pangkat, unit_kerja, wilayah');
        $this->select('master_pegawai.*');
        $this->orderBy('master_pegawai.nama_pegawai');
        return  $this->findAll();
    }

    /**
     * mendapatkan detail pegawai lengkap berdasarkan query where
     * hanya mengembalikan satu pegawai dengan nip_baru tertinggi
     * 
     * @param array $where
     * array asosiatif where (['kolom' => 'query_pencarian'])
     * 
     * @return array
     * detail satu pegawai jika ada
     */
    public function getPegawaiOne(array $where)
    {
        $this->where($where);
        //$this->join('master_fungsional', 'master_pegawai.id_fungsional = master_fungsional.id ', 'left');
        //$this->join('master_golongan', 'master_pegawai.id_golongan = master_golongan.id ', 'left');
        //$this->join('master_unit_kerja', 'master_pegawai.id_unit_kerja = master_unit_kerja.id ', 'left');
        //$this->join('master_wilayah', 'master_pegawai.id_wilayah = master_wilayah.id ', 'left');
        //$this->select('master_pegawai.*, id_eselon AS eselon, golongan, fungsional, pangkat, unit_kerja, wilayah');
        $this->select('master_pegawai.*');
        /** hanya pegawai teratas yang dikembalikan */
        $this->orderBy('username');
        return  $this->first();
    }

    /**
     * Cek apakah kombinasi login username dan password ada di database
     * 
     * @return array||false
     * Jika sukses login maka akan return array detail pegawai, jika tidak maka akan return false
     */
    public function checkLogin(string $username, string $password)
    {
        $detail_pegawai = false;

        $user = $this->getPegawaiOne([
            'username' => $username,
            'is_aktif' => true
        ]);
        // pengecekan password
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $detail_pegawai = $user;
            }
        }

        return $detail_pegawai;
    }

    /**
     * Mengeset nilai $_SESSION['user'] dengan data yang didapatkan dari getPegawaiOne
     * 
     * @param array $detail_pegawai
     * array yang didapatkan dari checkLogin atau checkUserSession
     * 
     */
    public function setPegawaiSession(array $detail_pegawai)
    {
        $session = \Config\Services::session();

        $session_data = [
            'id' => $detail_pegawai['id'],
            'username' => $detail_pegawai['username']
        ];

        $session->set('user', $session_data);

        return $detail_pegawai;
    }

    /**
     * Mengecek apakah data dalam session merupakan data yang valid
     * Pengecekan idealnya dilakukan saat SETIAP request di protected route dipanggil
     * 
     * @return array||false
     * mengembalikan detail pegawai jika session di $_SESSION['user'] merupakan session yang valid,
     * false jika sebaliknya
     */
    public function getPegawaiSession()
    {
        $session = \Config\Services::session();
        $user_session = $session->get('user');

        if (!$user_session) {
            return false;
        }

        $detail_pegawai = false;

        $user = $this->getPegawaiOne([
            'username' => $user_session['username'],
            'master_pegawai.id' => $user_session['id'],
            'is_aktif' => true
        ]);

        if ($user) {
            $detail_pegawai = $user;
        }

        return $detail_pegawai;
    }

    /**
     * Logout pegawai dan hancurkan sesi
     */
    public function logoutPegawaiSession()
    {
        $session = \Config\Services::session();
        $session->destroy();
    }
}

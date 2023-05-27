<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FGUWSeeder extends Seeder
{
    /**
     * Seeder untuk tabel-tabel:
     * master_kecamatan,
     * master_desa,
     * master_sls,
     * master_jenis
     * 
     */
    public function run()
    {
        $kecamatan_data = [
            ['kdkecamatan' => 010, 'nmkecamatan' => 'Sokan'],
            ['kdkecamatan' => 020, 'nmkecamatan' => 'Tanah Pinoh'],
            ['kdkecamatan' => 021, 'nmkecamatan' => 'Tanah Pinoh Barat'],
            ['kdkecamatan' => 030, 'nmkecamatan' => 'Sayan'],
            ['kdkecamatan' => 040, 'nmkecamatan' => 'Belimbing'],
            ['kdkecamatan' => 041, 'nmkecamatan' => 'Belimbing Hulu'],
            ['kdkecamatan' => 050, 'nmkecamatan' => 'Nanga Pinoh'],
            ['kdkecamatan' => 051, 'nmkecamatan' => 'Pinoh Selatan'],
            ['kdkecamatan' => 052, 'nmkecamatan' => 'Pinoh Utara'],
            ['kdkecamatan' => 060, 'nmkecamatan' => 'Ella Hilir'],
            ['kdkecamatan' => 070, 'nmkecamatan' => 'Menukung'],
        ];
        $this->db->table('master_kecamatan')->insertBatch($kecamatan_data);


        $golongan_data = [
            ['id' => 11, 'golongan' => 'I.a', 'pangkat' => null],
            ['id' => 12, 'golongan' => 'I.b', 'pangkat' => null],
            ['id' => 13, 'golongan' => 'I.c', 'pangkat' => null],
            ['id' => 14, 'golongan' => 'I.d', 'pangkat' => null],
            ['id' => 21, 'golongan' => 'II.a', 'pangkat' => 'Pengatur Muda'],
            ['id' => 22, 'golongan' => 'II.b', 'pangkat' => 'Pengatur Muda Tk. I'],
            ['id' => 23, 'golongan' => 'II.c', 'pangkat' => 'Pengatur'],
            ['id' => 24, 'golongan' => 'II.d', 'pangkat' => 'Pengatur Tk. I'],
            ['id' => 31, 'golongan' => 'III.a', 'pangkat' => 'Penata Muda'],
            ['id' => 32, 'golongan' => 'III.b', 'pangkat' => 'Penata Muda Tk. I'],
            ['id' => 33, 'golongan' => 'III.c', 'pangkat' => 'Penata'],
            ['id' => 34, 'golongan' => 'III.d', 'pangkat' => 'Penata Tk. I'],
            ['id' => 41, 'golongan' => 'IV.a', 'pangkat' => 'Pembina'],
            ['id' => 42, 'golongan' => 'IV.b', 'pangkat' => null],
            ['id' => 43, 'golongan' => 'IV.c', 'pangkat' => null],
            ['id' => 44, 'golongan' => 'IV.d', 'pangkat' => null],
            ['id' => 45, 'golongan' => 'IV.e', 'pangkat' => null],
        ];
        $this->db->table('master_golongan')->insertBatch($golongan_data);

        $unit_kerja_data = [
            ['id' => 9200, 'unit_kerja' => 'BPS Provinsi'],
            ['id' => 9280, 'unit_kerja' => 'BPS Kab/Kota'],
            ['id' => 9281, 'unit_kerja' => 'Subbag Tata Usaha'],
            ['id' => 9282, 'unit_kerja' => 'Seksi Statistik Sosial'],
            ['id' => 9283, 'unit_kerja' => 'Seksi Statistik Produksi'],
            ['id' => 9284, 'unit_kerja' => 'Seksi Statistik Distribusi'],
            ['id' => 9285, 'unit_kerja' => 'Seksi Nerwilis'],
            ['id' => 9286, 'unit_kerja' => 'Seksi IPDS'],
            ['id' => 9287, 'unit_kerja' => 'KSK'],
            ['id' => 9288, 'unit_kerja' => 'Mitra'],
        ];
        $this->db->table('master_unit_kerja')->insertBatch($unit_kerja_data);

        $wilayah_data = [
            ['id' => 3300, 'wilayah' => 'Jawa Tengah'],
            ['id' => 3301, 'wilayah' => 'Cilacap'],
            ['id' => 3302, 'wilayah' => 'Banyumas'],
            ['id' => 3303, 'wilayah' => 'Purbalingga'],
            ['id' => 3304, 'wilayah' => 'Banjarnegara'],
            ['id' => 3305, 'wilayah' => 'Kebumen'],
            ['id' => 3306, 'wilayah' => 'Purworejo'],
            ['id' => 3307, 'wilayah' => 'Wonosobo'],
            ['id' => 3308, 'wilayah' => 'Magelang'],
            ['id' => 3309, 'wilayah' => 'Boyolali'],
            ['id' => 3310, 'wilayah' => 'Klaten'],
            ['id' => 3311, 'wilayah' => 'Sukoharjo'],
            ['id' => 3312, 'wilayah' => 'Wonogiri'],
            ['id' => 3313, 'wilayah' => 'Karanganyar'],
            ['id' => 3314, 'wilayah' => 'Sragen'],
            ['id' => 3315, 'wilayah' => 'Grobogan'],
            ['id' => 3316, 'wilayah' => 'Blora'],
            ['id' => 3317, 'wilayah' => 'Rembang'],
            ['id' => 3318, 'wilayah' => 'Pati'],
            ['id' => 3319, 'wilayah' => 'Kudus'],
            ['id' => 3320, 'wilayah' => 'Jepara'],
            ['id' => 3321, 'wilayah' => 'Demak'],
            ['id' => 3322, 'wilayah' => 'Semarang'],
            ['id' => 3323, 'wilayah' => 'Temanggung'],
            ['id' => 3324, 'wilayah' => 'Kendal'],
            ['id' => 3325, 'wilayah' => 'Batang'],
            ['id' => 3326, 'wilayah' => 'Pekalongan'],
            ['id' => 3327, 'wilayah' => 'Pemalang'],
            ['id' => 3328, 'wilayah' => 'Tegal'],
            ['id' => 3329, 'wilayah' => 'Brebes'],
            ['id' => 3371, 'wilayah' => 'Kota Magelang'],
            ['id' => 3372, 'wilayah' => 'Kota Surakarta'],
            ['id' => 3373, 'wilayah' => 'Kota Salatiga'],
            ['id' => 3374, 'wilayah' => 'Kota Semarang'],
            ['id' => 3375, 'wilayah' => 'Kota Pekalongan'],
            ['id' => 3376, 'wilayah' => 'Kota Tegal']
        ];
        $this->db->table('master_wilayah')->insertBatch($wilayah_data);
    }
}

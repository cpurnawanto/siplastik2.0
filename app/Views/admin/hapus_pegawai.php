<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => '[Admin] Daftar Pengguna', 'uri' => 'admin/pegawai'], ['text' => 'Hapus Data Pegawai']]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h3>Apakah anda ingin coba menghapus data pengguna ini?</h3>
<p>Ingat bahwa anda bisa mengatur status pengguna menjadi tidak aktif daripada menghapus datanya secara permanen. <!-- <br>
    <span class="text-danger"> Data Pegawai tidak akan terhapus dari database jika pegawai memiliki kegiatan yang dialokasikan. </span> -->
</p>
<div class="row">
    <div class="col-lg-8">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th>Nama Lengkap</th>
                    <td><?= $input['nama_pegawai'] ?></td>
                </tr>
<!--                 <tr>
                    <th>NIP Lama</th>
                    <td><?= $input['nip_lama'] ?></td>
                </tr>
                <tr>
                    <th>NIP Baru</th>
                    <td><?= $input['nip_baru'] ?></td>
                </tr> -->
            </tbody>
        </table>
    </div>
</div>
<form action="<?= base_url('admin/pegawai/do-hapus/' . $input['id']) ?>" method="POST">
    <?= csrf_field() ?>
    <div class="form-group">
        <input type="hidden" name="id_hapus" value="<?= $input['id'] ?>">
        <a href="<?= base_url('admin/pegawai/ubah/' . $input['id']) ?>" class="btn btn-secondary px-3"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
        <button type="submit" class="btn btn-danger px-3"> <i class="fas fa-trash mr-1"></i> Hapus</button>

    </div>
</form>
<?= $this->endSection() ?>
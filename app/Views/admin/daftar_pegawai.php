<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => '[Admin] Daftar Pengguna']]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a href="<?= base_url('admin/pegawai/tambah') ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Pengguna</a>
<a href="<?= base_url('admin/pegawai/import') ?>" class="btn btn-secondary"><i class="fas fa-upload mr-1"></i> Import Pengguna</a>
<?= $this->endSection() ?>

<?= $this->section('table') ?>
<div class="table-responsive">
    <table id="basic-datatables" class="display table table-head-bg-primary table-striped table-hover mb-3" >
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Level User</th>
                <th>Nama Pengguna</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($pegawai as $peg) : ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $peg['username'] ?></td>
                    <td><?= $peg['is_admin'] ? 'Administrator' : ($peg['is_aktif'] ? 'User Aktif' : 'Nonaktif') ?></td>
                    <td><?= $peg['nama_pegawai'] ?></td>
                    <td><a href="<?= base_url('admin/pegawai/ubah/' . $peg['id']) ?>"><i class="fas fa-edit"></i></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
    $(function() {
        $('table').DataTable();
    })
</script>
<?= $this->endSection() ?>
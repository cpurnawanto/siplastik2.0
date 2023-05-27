<?= $this->extend('layouts/content_layout') ?>
<?php if (!isset($not_admin)) : ?>
    <?= $this->section('breadcrumb') ?>
    <?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Administrator', 'uri' => 'admin'], ['text' => 'Indeks Kredit Kegiatan']]) ?>
    <?= $this->endSection() ?>

    <?= $this->section('title_widget') ?>
    <a href="<?= base_url('admin/kredit-kegiatan/import') ?>" class="btn btn-secondary"><i class="fas fa-upload mr-1"></i> Import Kegiatan</a>
    <?= $this->endSection() ?>
<?php else : ?>
    <?= $this->section('breadcrumb') ?>
    <?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Personal', 'uri' => 'personal'], ['text' => 'Indeks Kredit Kegiatan']]) ?>
    <?= $this->endSection() ?>
<?php endif; ?>

<?= $this->section('table') ?>
<small><i>*) Klik untuk melihat detail kegiatan</i> <br><br></small>
<div class="overflow-auto">
    <table id="kk" class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Tingkat</th>
                <th>Kode Perka</th>
                <th>Kegiatan</th>
                <th>Uraian Singkat</th>
                <th>Satuan Hasil</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
    /** hover biar ada cursornya */
    #kk tr {
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(function() {
        var table = $('#kk').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "<?= base_url('api/data-tables/kredit-kegiatan') ?>",
            "columns": [{
                    "data": "id"
                }, {
                    "data": "kode"
                },
                {
                    "data": "nama_tingkat"
                },
                {
                    "data": "kode_perka"
                },
                {
                    "data": "kegiatan"
                },
                {
                    "data": "uraian_singkat"
                },
                {
                    "data": "satuan_hasil"
                }
            ]
        });

        /** onclick baris untuk melihat detail */
        $('#kk tbody').on('click', 'tr', function() {
            var data = table.row(this).data();
            if (data.id) {
                window.location.href = '<?= isset($not_admin) ? base_url('personal/fungsional/kredit-kegiatan') : base_url('admin/kredit-kegiatan/lihat') ?>/' + data.id;
            }
        });
    })
</script>
<?= $this->endSection() ?>
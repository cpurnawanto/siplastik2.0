<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Struktural', 'uri' => 'struktural'], ['text' => 'Verifikasi Realisasi Kegiatan']]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<small><i>*) Klik baris untuk lihat detail realisasi sebelum memverifikasi</i> <br><br></small>
<div class="overflow-auto">
    <table class="table table-hover" id="verifikasi">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kegiatan</th>
                <th class="text-nowrap">Nama Pegawai</th>
                <th class="text-nowrap">Tgl Realisasi</th>
                <th class="text-nowrap">Jml Realisasi</th>
                <th class="text-nowrap">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $c = 0;
            foreach ($daftar_realisasi as $realisasi) : ?>
                <tr data-id-realisasi="<?= $realisasi['id_realisasi'] ?>">
                    <td><?= ++$c ?></td>
                    <td><?= esc($realisasi['nama_kegiatan']) ?></td>
                    <td><?= esc($realisasi['nama_pegawai']) ?></td>
                    <td><?= $realisasi['tanggal_realisasi'] ?></td>
                    <td><?= $realisasi['jumlah_realisasi'] ?></td>
                    <td><?= esc(!empty($realisasi['keterangan_realisasi']) ? $realisasi['keterangan_realisasi'] : '-') ?></td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(function() {
        $('#verifikasi').DataTable();
        $('#verifikasi tbody').on('click', 'tr', function() {
            var data = $(this).data();
            if (data.idRealisasi) {
                window.location.href = '<?= base_url('struktural/realisasi/detail-verifikasi') ?>/' + data.idRealisasi;
            }
        });
    })
</script>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
    /** hover biar ada cursornya */
    #verifikasi tr {
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>
<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Personal', 'uri' => 'personal'], ['text' => 'Realisasi Bulanan']]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<?php
$prev_month = $bulan - 1;
$prev_year = $tahun;
$next_month = $bulan + 1;
$next_year = $tahun;

if ($prev_month === 0) {
    $prev_year--;
    $prev_month = 12;
}

if ($next_month === 13) {
    $next_year++;
    $next_month = 1;
}
?>
<a class="btn btn-primary btn-sm" href="<?= base_url('personal/realisasi/bulanan/' . $prev_year . '/' . $prev_month) ?>"><i class="fas fa-backward  mx-2"></i></a>
<a class="btn btn-primary btn-sm" href="<?= base_url('personal/realisasi/bulanan/'  . $tahun . '/' . $bulan) ?>"> <?= esc($bulan_tahun) ?></a>
<a class="btn btn-primary btn-sm" href="<?= base_url('personal/realisasi/bulanan/' . $next_year . '/' . $next_month) ?>"><i class="fas fa-forward mx-2"></i></a>

<?= $this->endSection() ?>


<?= $this->section('table') ?>
<small><i>*) Klik untuk melihat detail kegiatan</i> <br><br></small>
<div class="overflow-auto">
    <table class="table table-hover" id="list-realisasi">
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal Realisasi</th>
                <th>Jumlah Realisasi</th>
                <th>Nama Kegiatan</th>
                <th>Keterangan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $c = 0;
            foreach ($realisasi_pegawai as $realisasi) : ?>
                <tr data-id-kegiatan="<?= $realisasi['id_kegiatan'] ?>" class="<?= $realisasi['waktu_acc'] ? 'table-success' : 'table-warning clickable' ?>">
                    <td><?= ++$c ?></td>
                    <td class="text-nowrap <?= in_array(date("N", strtotime($realisasi['tanggal_realisasi'])), [6, 7]) ? 'text-warning' : '' ?>"><?= date("l, d M Y", strtotime($realisasi['tanggal_realisasi'])) ?></td>
                    <td class="text-nowrap"><?= esc($realisasi['jumlah_realisasi']) . ' ' . esc($realisasi['satuan_target']) ?></td>
                    <td><?= esc($realisasi['nama_kegiatan']) ?></td>
                    <td><?= esc($realisasi['keterangan']) ?></td>
                    <td><?= $realisasi['waktu_acc'] ? '<i class="fas fa-check"></i> Telah Diverifikasi' : '<i class="fas fa-clock"></i> Belum diverifikasi' ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
        $('#list-realisasi').DataTable();
        $('#list-realisasi tbody').on('click', 'tr', function() {
            var data = $(this).data();
            if (data.idKegiatan) {
                window.location.href = '<?= base_url('personal/kegiatan/detail') ?>/' + data.idKegiatan;
            }
        });
    })
</script>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
    #list-realisasi tr {
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>
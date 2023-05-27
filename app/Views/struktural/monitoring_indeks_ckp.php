<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Struktural'], ['text' => 'Monitoring'], ['text' => 'Indeks CKP']]) ?>
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

<a class="btn btn-primary btn-sm" href="<?= base_url('struktural/monitoring/ckp/' . $prev_year . '/' . $prev_month) ?>"><i class="fas fa-backward  mx-2"></i></a>
<a class="btn btn-primary btn-sm" href="<?= base_url('struktural/monitoring/ckp/' . $tahun . '/' . $bulan) ?>"> <?= esc($bulan_tahun) ?></a>
<a class="btn btn-primary btn-sm" href="<?= base_url('struktural/monitoring/ckp/' . $next_year . '/' . $next_month) ?>"><i class="fas fa-forward mx-2"></i></a>

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<table id="tabel-ckp" class="table table-hover">
    <thead>
        <tr>
            <th> Nama Pegawai</th>
            <th> Jabatan/Unit Kerja</th>
            <th class="text-right"> Jml Kegiatan</th>
            <th class="text-right"> Kuantitas</th>
            <th class="text-right"> Kualitas</th>
            <th class="text-right"> &Sigma; Beban Realisasi / Alokasi</th>
            <th class="text-right"> Nilai CKP</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($pegawai as $peg) : ?>
            <tr data-id="<?= $peg['id'] ?>">
                <td><?= esc($peg['nama_pegawai']) ?></td>
                <td><?= ($peg['id_eselon'] ? 'Ketua ' : '') . $peg['unit_kerja'] ?></td>
                <td class="text-right"><?= $ckp[$peg['id']]['jumlah_kegiatan'] ?></td>
                <td class="text-right"><?= $ckp[$peg['id']]['avg_kuantitas'] ?></td>
                <td class="text-right"><?= $ckp[$peg['id']]['avg_kualitas'] ?></td>
                <td class="text-right"><?= $ckp[$peg['id']]['jumlah_bobot_realisasi'] ?> / <?= $ckp[$peg['id']]['jumlah_bobot_target'] ?> (<?= $ckp[$peg['id']]['persentase_bobot'] ?>%)</td>
                <td class="text-right"><?= number_format($ckp[$peg['id']]['ckp'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
    $(function() {
        $('#tabel-ckp').DataTable();
        $('#tabel-ckp tbody').on('click', 'tr', function() {
            var data = $(this).data();
            if (data.id) {
                window.location.href = '<?= base_url('struktural/monitoring/pegawai') ?>/' + data.id + '<?= '/' . $tahun . '/' . $bulan ?>/';
            }
        });
    })
</script>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
    #tabel-ckp tr {
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>
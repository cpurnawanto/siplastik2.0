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

<a class="btn btn-primary btn-sm" href="<?= base_url('struktural/monitoring/sebaran-unit-kerja/' . $prev_year . '/' . $prev_month) ?>"><i class="fas fa-backward  mx-2"></i></a>
<a class="btn btn-primary btn-sm" href="<?= base_url('struktural/monitoring/sebaran-unit-kerja/' . $tahun . '/' . $bulan) ?>"> <?= esc($bulan_tahun) ?></a>
<a class="btn btn-primary btn-sm" href="<?= base_url('struktural/monitoring/sebaran-unit-kerja/' . $next_year . '/' . $next_month) ?>"><i class="fas fa-forward mx-2"></i></a>

<?= $this->endSection() ?>


<?= $this->section('content') ?>
<table id="tabel-sebaran" class="table table-striped ">
    <thead>
        <tr>
            <th class="text-nowrap"> Nama Pegawai</th>
            <?php foreach ($unit_kerja as $uker) : ?>
                <th class="text-right"><?= esc($uker['unit_kerja']) ?></th>
            <?php endforeach; ?>
            <th class="text-right text-nowrap"> &Sigma; Beban <br> (Jml Keg.)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($pegawai as $peg) : ?>
            <tr>
                <td class="text-nowrap"><?= esc($peg['nama_pegawai']) ?></td>
                <?php foreach ($unit_kerja as $uker) : ?>
                    <td class="text-right text-nowrap"><?= $matriks[$peg['id']]['unit_kerja'][$uker['id']]['jumlah_bobot_target'] ?> (<?= $matriks[$peg['id']]['unit_kerja'][$uker['id']]['jumlah_kegiatan'] ?>)</td>
                <?php endforeach; ?>
                <td class="text-right text-nowrap"><?= $matriks[$peg['id']]['jumlah_bobot_target'] ?> (<?= $matriks[$peg['id']]['jumlah_kegiatan'] ?>)</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
    $(function() {
        $('#tabel-sebaran').DataTable();
    })
</script>
<?= $this->endSection() ?>
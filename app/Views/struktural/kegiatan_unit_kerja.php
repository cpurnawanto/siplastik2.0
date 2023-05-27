<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Struktural', 'uri' => 'struktural'], ['text' => 'Kegiatan Unit Kerja']]) ?>
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
<?php if (!empty($daftar_unit_kerja)) : ?>
    <span class="dropdown open">
        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="triggerId" data-toggle="dropdown">
            Unit Kerja
        </button>
        <div class="dropdown-menu">
            <?php foreach ($daftar_unit_kerja as $unit_kerja) : ?>
                <a class="dropdown-item" href="<?= base_url('struktural/kegiatan/unit-kerja/' . $unit_kerja['id'] . '/' . $tahun . '/' . $bulan) ?>"><?= $unit_kerja['unit_kerja'] ?></a>
            <?php endforeach; ?>
        </div>
    </span>
<?php endif; ?>
<a class="btn btn-primary btn-sm" href="<?= base_url('struktural/kegiatan/tambah') ?>"><i class="fas fa-plus"></i> Tambah</a>
<a class="btn btn-primary btn-sm" href="<?= base_url('struktural/kegiatan/unit-kerja/' . $id_unit_kerja . '/' . $prev_year . '/' . $prev_month) ?>"><i class="fas fa-backward  mx-2"></i></a>
<a class="btn btn-primary btn-sm" href="<?= base_url('struktural/kegiatan/unit-kerja/' . $id_unit_kerja . '/' . $tahun . '/' . $bulan) ?>"> <?= esc($bulan_tahun) ?></a>
<a class="btn btn-primary btn-sm" href="<?= base_url('struktural/kegiatan/unit-kerja/' . $id_unit_kerja . '/' . $next_year . '/' . $next_month) ?>"><i class="fas fa-forward mx-2"></i></a>
<?= $this->endSection() ?>

<?= $this->section('table') ?>
<small><i>*) Klik untuk melihat detail kegiatan</i> <br><br></small>
<div class="overflow-auto">
    <table class="table table-hover" id="list-kegiatan">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kegiatan</th>
                <th>Jadwal</th>
                <th>Target</th>
                <th class="text-nowrap">&Sigma; Bobot</th>
                <th class="text-nowrap">Progress Kegiatan</th>
                <th>Telat</th>
                <th>Dinilai</th>
                <th>Alokasi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $c = 0;
            foreach ($list_kegiatan as $kegiatan) : ?>
                <tr data-id-kegiatan="<?= $kegiatan['id'] ?>">
                    <td><?= ++$c ?></td>
                    <td><?= esc($kegiatan['nama_kegiatan']) ?></td>
                    <td class="text-nowrap"><?= date("d M", strtotime($kegiatan['tgl_mulai'])) . ' - ' . date("d M Y", strtotime($kegiatan['tgl_selesai'])) ?></td>
                    <td class="text-nowrap"><?= esc($kegiatan['jumlah_target']) . ' ' . esc($kegiatan['satuan_target']) ?></td>
                    <td class="text-right">
                        <span class="d-none"><?= str_pad(number_format($kegiatan['jumlah_target'] * $kegiatan['bobot'], 4), 8, 0, STR_PAD_LEFT) ?></span>
                        <?= number_format($kegiatan['jumlah_target'] * $kegiatan['bobot'], 2)  ?>
                    </td>
                    <td>
                        <?php
                        $real_target = $realisasi_target[$kegiatan['id']] ?? 0;
                        $real_target_verif = $realisasi_target_terverifikasi[$kegiatan['id']] ?? 0;
                        $real_target_terlambat = $realisasi_target_terlambat[$kegiatan['id']] ?? 0;
                        $alokasi_target = $target_pegawai[$kegiatan['id']] ?? 0;
                        $target_dinilai = $target_dinilai[$kegiatan['id']] ?? 0;

                        $percent_real_target = $real_target / $kegiatan['jumlah_target'] * 100;
                        $percent_real_target_verif = $real_target_verif / $kegiatan['jumlah_target'] * 100;
                        $percent_real_target_terlambat = $real_target_terlambat / $kegiatan['jumlah_target'] * 100;
                        $percent_alokasi_target = $alokasi_target / $kegiatan['jumlah_target'] * 100;
                        $percent_target_dinilai = $target_dinilai / $kegiatan['jumlah_target'] * 100;
                        ?>
                        <span class="d-none"><?= str_pad(number_format($percent_real_target, 2), 6, 0, STR_PAD_LEFT) . str_pad(number_format($percent_real_target_verif, 2), 6, 0, STR_PAD_LEFT) ?></span>
                        <div class="progress" data-toggle="tooltip" title="Realisasi : <?= $real_target ?> (<?= number_format($percent_real_target, 2) ?>%), Diverifikasi : <?= $real_target_verif ?> (<?= number_format($percent_real_target_verif, 2) ?>%)">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percent_real_target_verif ?>%"></div>
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $percent_real_target -  $percent_real_target_verif ?>%"></div>
                        </div>
                    </td>
                    <td class="text-nowrap text-center">
                        <span class="d-none"><?= str_pad(number_format($percent_real_target_terlambat, 2), 6, 0, STR_PAD_LEFT) ?></span>
                        <?= $real_target_terlambat ?> (<?= number_format($percent_real_target_terlambat, 1) ?>%)
                    </td>

                    <td class="text-nowrap text-center">
                        <span class="d-none"><?= str_pad(number_format($percent_target_dinilai, 2), 6, 0, STR_PAD_LEFT) ?></span>
                        <?php if ($percent_target_dinilai < 100) : ?>
                            <i class="fas fa-clock text-warning"></i>
                        <?php else : ?>
                            <i class="fas fa-check text-success"></i>
                        <?php endif; ?>
                        <?= number_format($percent_target_dinilai, 1) ?>%
                    </td>
                    <td class="text-nowrap text-center">
                        <span class="d-none"><?= str_pad(number_format($percent_alokasi_target, 2), 6, 0, STR_PAD_LEFT) ?></span>
                        <?php if ($percent_alokasi_target < 100) : ?>
                            <i class="fas fa-clock text-warning"></i>
                        <?php else : ?>
                            <i class="fas fa-check text-success"></i>
                        <?php endif; ?>
                        <?= number_format($percent_alokasi_target, 1) ?>%
                    </td>
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
        $('#list-kegiatan').DataTable();
        $('#list-kegiatan tbody').on('click', 'tr', function() {
            var data = $(this).data();
            if (data.idKegiatan) {
                window.location.href = '<?= base_url('struktural/kegiatan/detail') ?>/' + data.idKegiatan;
            }
        });
    })
</script>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
    #list-kegiatan tr {
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>
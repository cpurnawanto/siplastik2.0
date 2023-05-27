<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Personal', 'uri' => 'personal'], ['text' => 'Kegiatan Saya']]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<?php
if (isset($bulan)) :
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
    <a class="btn btn-primary btn-sm" href="<?= base_url('personal/kegiatan/bulanan/' . $prev_year . '/' . $prev_month) ?>"><i class="fas fa-backward  mx-2"></i></a>
    <a class="btn btn-primary btn-sm" href="<?= base_url('personal/kegiatan/bulanan/'  . $tahun . '/' . $bulan) ?>"> <?= esc($bulan_tahun) ?></a>
    <a class="btn btn-primary btn-sm" href="<?= base_url('personal/kegiatan/bulanan/' . $next_year . '/' . $next_month) ?>"><i class="fas fa-forward mx-2"></i></a>
<?php else : ?>
    <a class="btn btn-secondary btn-sm" href="<?= base_url('personal/kegiatan/tahunan/') ?>"> <i class="fas fa-download ml-1"></i> Excel</a>
    <a class="btn btn-primary btn-sm" href="<?= base_url('personal/kegiatan/tahunan/' . ($tahun - 1)) ?>"><i class="fas fa-backward  mx-2"></i></a>
    <a class="btn btn-primary btn-sm" href="<?= base_url('personal/kegiatan/tahunan/'  . $tahun) ?>"> Tahun <?= $tahun ?></a>
    <a class="btn btn-primary btn-sm" href="<?= base_url('personal/kegiatan/tahunan/' . ($tahun + 1)) ?>"><i class="fas fa-forward mx-2"></i></a>
<?php endif; ?>
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
                <th class="text-nowrap">Progress Kegiatan</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $c = 0;
            foreach ($target_pegawai as $kegiatan) : ?>
                <tr data-id-kegiatan="<?= $kegiatan['id_kegiatan'] ?>">
                    <td><?= ++$c ?></td>
                    <td><?= esc($kegiatan['nama_kegiatan']) ?></td>
                    <td class="text-nowrap"><?= date("d M Y", strtotime($kegiatan['tgl_mulai'])) . ' - ' . date("d M Y", strtotime($kegiatan['tgl_selesai'])) ?></td>
                    <td class="text-nowrap"><?= esc($kegiatan['target_pegawai']) . ' ' . esc($kegiatan['satuan_target']) ?></td>
                    <td>
                        <?php
                        $real_target = $realisasi_target[$kegiatan['id_kegiatan']] ?? 0;
                        $real_target_verif = $realisasi_target_terverifikasi[$kegiatan['id_kegiatan']] ?? 0;
                        $percent_real_target = $real_target / $kegiatan['target_pegawai'] * 100;
                        $percent_real_target_verif = $real_target_verif / $kegiatan['target_pegawai'] * 100;
                        ?>
                        <div class="row" data-toggle="tooltip" title="Realisasi : <?= $real_target ?> (<?= number_format($percent_real_target, 2) ?>%), Diverifikasi : <?= $real_target_verif ?> (<?= number_format($percent_real_target_verif, 2) ?>%)">
                            <div class="col-10">
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percent_real_target_verif ?>%"></div>
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $percent_real_target -  $percent_real_target_verif ?>%"></div>
                                </div>
                            </div>
                            <div class="col-2 px-0">
                                <?php if ($percent_real_target_verif < 100) : ?>
                                    <i class="fas fa-clock text-warning"></i>
                                <?php else : ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="text-nowrap">
                        <?php if ($kegiatan['persen_kualitas']) : ?>
                            <?= $kegiatan['persen_kualitas'] ?> <i class="fas fa-check text-success"></i>

                        <?php else : ?>
                            Belum <i class="fas fa-clock text-warning"></i>
                        <?php endif; ?>
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
                window.location.href = '<?= base_url('personal/kegiatan/detail') ?>/' + data.idKegiatan;
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
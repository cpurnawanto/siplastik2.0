<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Personal', 'uri' => 'personal'], ['text' => 'Rincian Fungsional']]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<?= $this->endSection() ?>


<?= $this->section('table') ?>

<table>
    <tbody>
        <tr>
            <th>Periode</th>
            <td>
                <?php
                function opt($value, $bulan)
                {
                    if ($value == $bulan) {
                        return "selected";
                    }
                    return "";
                }
                ?>
                <div class="form-inline my-1">
                    <select id="bulan1" class="form-control mx-3">
                        <option value="1" <?= opt(1, $bulan1) ?>>Januari</option>
                        <option value="2" <?= opt(2, $bulan1) ?>>Februari</option>
                        <option value="3" <?= opt(3, $bulan1) ?>>Maret</option>
                        <option value="4" <?= opt(4, $bulan1) ?>>April</option>
                        <option value="5" <?= opt(5, $bulan1) ?>>Mei</option>
                        <option value="6" <?= opt(6, $bulan1) ?>>Juni</option>
                        <option value="7" <?= opt(7, $bulan1) ?>>Juli</option>
                        <option value="8" <?= opt(8, $bulan1) ?>>Agustus</option>
                        <option value="9" <?= opt(9, $bulan1) ?>>September</option>
                        <option value="10" <?= opt(10, $bulan1) ?>>Oktober</option>
                        <option value="11" <?= opt(11, $bulan1) ?>>November</option>
                        <option value="12" <?= opt(12, $bulan1) ?>>Desember</option>
                    </select>
                    &nbsp;-&nbsp;
                    <select id="bulan2" class="form-control mx-3">
                        <option value="1" <?= opt(1, $bulan2) ?>>Januari</option>
                        <option value="2" <?= opt(2, $bulan2) ?>>Februari</option>
                        <option value="3" <?= opt(3, $bulan2) ?>>Maret</option>
                        <option value="4" <?= opt(4, $bulan2) ?>>April</option>
                        <option value="5" <?= opt(5, $bulan2) ?>>Mei</option>
                        <option value="6" <?= opt(6, $bulan2) ?>>Juni</option>
                        <option value="7" <?= opt(7, $bulan2) ?>>Juli</option>
                        <option value="8" <?= opt(8, $bulan2) ?>>Agustus</option>
                        <option value="9" <?= opt(9, $bulan2) ?>>September</option>
                        <option value="10" <?= opt(10, $bulan2) ?>>Oktober</option>
                        <option value="11" <?= opt(11, $bulan2) ?>>November</option>
                        <option value="12" <?= opt(12, $bulan2) ?>>Desember</option>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <th>Tahun</th>
            <td>
                <div class="form-inline my-1">
                    <input type="number" id="tahun1" class="form-control mx-3" min="2019" max="2100" value="<?= $tahun1 ?>">
                    <button id="tampilkan-button" role="button" class="btn btn-primary">Tampilkan</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<br>
<small><i>*) Klik untuk melihat detail kegiatan</i> <br><br></small>
<div class="overflow-auto">
    <table class="table table-hover" id="list-rincian-fungsional">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kegiatan</th>
                <th>Keterangan</th>
                <th>Kode Kredit</th>
                <th>Tanggal</th>
                <th>Satuan</th>
                <th class="text-right">Volume</th>
                <th class="text-right">Angka Kredit</th>
                <th class="text-right">Jumlah AK</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $c = 0;
            foreach ($target_pegawai as $target) :
                $kredit = $kredit_kegiatan[$target['id_kredit_kegiatan']];
                $kode = $kredit['kode']  . ' - ' . $kredit['nama_tingkat'] . ' ' . $kredit['kode_perka'];
            ?>
                <tr data-id-kegiatan="<?= $target['id_kegiatan'] ?>">
                    <td><?= ++$c ?></td>
                    <td><?= esc($target['nama_kegiatan']) ?></td>
                    <td><?= esc($target['keterangan_target']) ?></td>
                    <td><?= esc($kode) ?><br><?= esc($target['fungsional_angka_kredit_target']) ?></td>
                    <td><?= date("d M Y", strtotime($target['tgl_mulai'])) . ' - <br>' . date("d M Y", strtotime($target['tgl_selesai'])) ?></td>
                    <td><?= esc($kredit['satuan_hasil']) ?></td>
                    <td class="text-right"><?= esc($realisasi_target[$target['id_kegiatan']]) ?></td>
                    <td class="text-right"><?= esc($target['angka_kredit_target'] ?? 0) ?></td>
                    <td class="text-right"><?= esc($target['angka_kredit_target'] * $realisasi_target[$target['id_kegiatan']]) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
    $(function() {
        $('#list-rincian-fungsional').DataTable();
        $('#list-rincian-fungsional tbody').on('click', 'tr', function() {
            var data = $(this).data();
            if (data.idKegiatan) {
                window.location.href = '<?= base_url('personal/kegiatan/detail') ?>/' + data.idKegiatan;
            }
        });

        $('#tampilkan-button').click(function(ev) {
            ev.preventDefault();
            var bulan1 = $('#bulan1').val();
            var bulan2 = $('#bulan2').val();
            var tahun1 = $('#tahun1').val();
            var tahun2 = tahun1;

            window.location.href = `<?= base_url('personal/fungsional/rincian') ?>/${tahun1}/${bulan1}/${tahun2}/${bulan2}`;
        })
    })
</script>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
    #list-rincian-fungsional tr {
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>
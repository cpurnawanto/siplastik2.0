<?php

/**
 * Fungsi untuk mempermudah ekstraksi $input
 */
function inp(string $input_key, $input, $default = '')
{
    return !empty($input[$input_key]) ? esc($input[$input_key]) : $default;
}

function inpSelect(string $input_key, string $input_val, $input)
{
    if (!empty($input[$input_key])) {
        return $input[$input_key] == $input_val ? 'selected' : '';
    }
    return '';
}
?>
<form action="<?= base_url($uri) ?>" method="POST">
    <?= csrf_field() ?>

    <?php if ($target_pegawai) : ?>
        <div class="form-group">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <label for="id_pegawai">Pegawai *</label>
                </div>
                <div class="col-lg-9">
                    <select class="form-control" name="realisasi[id_pegawai]" id="id_pegawai">
                        <?php foreach ($target_pegawai as $peg) : ?>
                            <option value="<?= $peg['id_pegawai'] ?>" <?= inpSelect('id_pegawai', $peg['id_pegawai'], $input) ?>><?= esc($peg['nama_pegawai']) ?> (Target : <?= $peg['target_pegawai'] . ' ' . esc($peg['satuan_target']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <label for="tanggal_realisasi">Tanggal Realisasi *</label>
            </div>
            <div class="col-lg-9">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-calendar"></i> </div>
                    </div>
                    <input type="text" class="form-control datepicker" name="realisasi[tanggal_realisasi]" id="tanggal_realisasi" placeholder="Klik untuk tanggal realisasi" required autocomplete="off" value="<?= inp('tanggal_realisasi', $input) ?>">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Contoh: 2021-02-21 </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <label for="jumlah_realisasi">Jumlah Realisasi *</label>
            </div>
            <div class="col-lg-9">
                <input type="number" class="form-control" name="realisasi[jumlah_realisasi]" id="jumlah_realisasi" placeholder="Masukkan jumlah realisasi" required autocomplete="off" value="<?= inp('jumlah_realisasi', $input) ?>">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-lg-3">
                <label for="keterangan">Keterangan</label>
            </div>
            <div class="col-lg-9">
                <textarea name="realisasi[keterangan]" id="keterangan" cols="30" rows="5" class="form-control" autocomplete="off"><?= inp('keterangan', $input) ?></textarea>
            </div>
        </div>
    </div>
    <?php if ($struktural) : ?>
        <div class="form-group">
            <div class="row">
                <div class="col-lg-3">
                    <label for="keterangan">Verifikasi</label>
                </div>
                <div class="col-lg-9">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="realisasi[verifikasi]" value="true" <?= !empty($input['waktu_acc']) ? 'checked' : '' ?>> Ya
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="realisasi[verifikasi]" value="false" <?= empty($input['waktu_acc']) ? 'checked' : '' ?>> Tidak
                        </label>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <button type="submit" class="btn btn-primary px-4"> <i class="fas fa-save mr-1"></i>Simpan</button>
    <br>
    <br>
    <small>*) Wajib diisi</small>
</form>
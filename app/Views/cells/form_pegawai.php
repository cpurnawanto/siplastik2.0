<?php

/**
 * 
 * 'nip_baru',
 * 'nip_lama',
 * 'nama_pegawai',
 * 'nama_singkat',
 * 'username',
 * 'password',
 * 'password2',
 * 'id_golongan',
 * 'id_wilayah',
 * 'id_unit_kerja',
 * 'id_eselon',
 * 'id_fungsional',
 * 'is_admin',
 * 'is_aktif',
 * 
 */

/**
 * Fungsi untuk mempermudah ekstraksi $input
 */
function inp(string $input_key, $input)
{
    return !empty($input[$input_key]) ? esc($input[$input_key]) : '';
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
    <!-- <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="nip_baru">NIP Baru *</label>
            </div>
            <div class="col-lg-8">
                <input type="text" class="form-control" name="pegawai[nip_baru]" id="nip_baru" autocomplete="off" value="<?= inp('nip_baru', $input) ?>" required maxlength="18">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="nip_lama">NIP Lama *</label>
            </div>
            <div class="col-lg-8">
                <input type="text" class="form-control" name="pegawai[nip_lama]" id="nip_lama" autocomplete="off" value="<?= inp('nip_lama', $input) ?>" required maxlength="9">
            </div>
        </div>
    </div> -->
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="nama_pegawai">Nama Lengkap *</label>
            </div>
            <div class="col-lg-8">
                <input type="text" class="form-control" name="pegawai[nama_pegawai]" id="nama_pegawai" autocomplete="off" value="<?= inp('nama_pegawai', $input) ?>" required>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="nama_singkat">Nama Singkat *</label>
            </div>
            <div class="col-lg-8">
                <input type="text" class="form-control" name="pegawai[nama_singkat]" id="nama_singkat" autocomplete="off" value="<?= inp('nama_singkat', $input) ?>" required>
            </div>
        </div>
    </div>
    <!-- <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="id_golongan">Golongan</label>
            </div>
            <div class="col-lg-8">
                <select class="form-control" name="pegawai[id_golongan]" id="id_golongan">
                    <?php foreach ($list['id_golongan'] as $el) : ?>
                        <option value="<?= $el['id'] ?>" <?= inpSelect('id_golongan', $el['id'], $input) ?>><?= $el['golongan'] . ' (' . $el['id'] . ')' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="id_eselon">Eselon</label>
            </div>
            <div class="col-lg-8">
                <select class="form-control" name="pegawai[id_eselon]" id="id_eselon">
                    <option value="0" <?= inpSelect('id_eselon', 0, $input) ?>>0</option>
                    <option value="4" <?= inpSelect('id_eselon', 4, $input) ?>>4</option>
                    <option value="3" <?= inpSelect('id_eselon', 3, $input) ?>>3</option>
                    <option value="2" <?= inpSelect('id_eselon', 2, $input) ?>>2</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="id_fungsional">Jabatan Fungsional</label>
            </div>
            <div class="col-lg-8">
                <select class="form-control" name="pegawai[id_fungsional]" id="id_fungsional">
                    <?php foreach ($list['id_fungsional'] as $el) : ?>
                        <option value="<?= $el['id'] ?>" <?= inpSelect('id_fungsional', $el['id'], $input) ?>><?= $el['fungsional'] . ' (' . $el['id'] . ')' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="id_unit_kerja">Unit Kerja</label>
            </div>
            <div class="col-lg-8">
                <select class="form-control" name="pegawai[id_unit_kerja]" id="id_unit_kerja">
                    <?php foreach ($list['id_unit_kerja'] as $el) : ?>
                        <option value="<?= $el['id'] ?>" <?= inpSelect('id_unit_kerja', $el['id'], $input) ?>><?= $el['unit_kerja'] . ' (' . $el['id'] . ')' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="id_wilayah">Wilayah</label>
            </div>
            <div class="col-lg-8">
                <select class="form-control" name="pegawai[id_wilayah]" id="id_wilayah">
                    <?php foreach ($list['id_wilayah'] as $el) : ?>
                        <option value="<?= $el['id'] ?>" <?= inpSelect('id_wilayah', $el['id'], $input) ?>><?= $el['wilayah'] . ' (' . $el['id'] . ')' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div> -->
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="is_admin">Jadikan admin</label>
            </div>
            <div class="col-lg-8">
                <select class="form-control" name="pegawai[is_admin]" id="is_admin">
                    <option value="false" <?= inpSelect('is_admin', 0, $input) ?>>Tidak</option>
                    <option value="true" <?= inpSelect('is_admin', 1, $input) ?>>Ya</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="is_aktif">Aktif</label>
            </div>
            <div class="col-lg-8">
                <select class="form-control" name="pegawai[is_aktif]" id="is_aktif">
                    <option value="true" <?= inpSelect('is_aktif', 1, $input) ?>>Ya</option>
                    <option value="false" <?= inpSelect('is_aktif', 0, $input) ?>>Tidak</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="username">Username Akun</label>
            </div>
            <div class="col-lg-8">
                <input type="text" class="form-control" name="pegawai[username]" id="username" autocomplete="off" value="<?= inp('username', $input) ?>">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="password">Password Baru</label>
            </div>
            <div class="col-lg-8">
                <input type="password" class="form-control" name="pegawai[password]" id="password" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <label for="password2">Ulangi Password</label>
            </div>
            <div class="col-lg-8">
                <input type="password" class="form-control" name="pegawai[password2]" id="password2" autocomplete="off">
            </div>
        </div>
    </div>
    <br>
    <button type="submit" class="btn btn-primary px-4"> <i class="fas fa-save mr-1"></i>Simpan</button>
    <br>
    <br>
    <small>*) Wajib diisi</small>
</form>
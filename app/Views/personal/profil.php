<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Profil Saya']]) ?>
<?= $this->endSection() ?>

<?= $this->section('table') ?>
<table class="table table-sm table-borderless table-responsive">
    <tbody>
        <tr>
            <th>Username</th>
            <td><?= esc($pegawai['username']) ?></td>
        </tr>
        <tr>
            <th>Level User</th>
            <td><?= $pegawai['is_admin'] ? 'Administrator' : ($pegawai['is_aktif'] ? 'User Aktif' : 'Nonaktif') ?></td>
        </tr>
        <tr>
            <th>Nama</th>
            <td><?= esc($pegawai['nama_pegawai']) ?></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button class="btn btn-primary" role="button" data-toggle="modal" data-target="#modal-ubah-password"><i class="fas fa-lock"></i> Ganti Password</button>
            </td>
        </tr>
    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="modal-ubah-password" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('personal/profil/do-ganti-password') ?>" method="post" id="form-password">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="password">Masukkan Password Baru *</label>
                        <input type="password" class="form-control" name="password[password]" id="password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="password2">Masukkan Password Baru Kembali *</label>
                        <input type="password" class="form-control" name="password[password2]" id="password2" required minlength="6">
                    </div>
                    <div id="error-message-password">
                        <small class="text-danger">Pastikan kedua password sama dan minimal 6 karakter</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="submit-button" class="btn btn-primary">Ganti</button>
                </div>
            </form>
        </div>
    </div>
</div>
<br>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
    $(function() {
        var confirmPassword = false;

        function disablePasswordForm() {
            $('#modal-ubah-password').on('submit', function(ev) {
                ev.preventDefault();
            })
            $('#error-message-password').show();
            $('#submit-button').prop('disabled', true);
        }

        function enablePasswordForm() {
            $('#modal-ubah-password').off('submit');
            $('#error-message-password').hide();
            $('#submit-button').prop('disabled', false);
        }

        function togglePasswordValidation() {
            if (($('#password').val() === $('#password2').val()) && $('#password').val().length > 5 && $('#password2').val().length > 5) {
                enablePasswordForm();
            } else {
                disablePasswordForm();
            }
        }

        disablePasswordForm();

        $('#password').on('change input', togglePasswordValidation)

        $('#password2').on('change input', togglePasswordValidation)
    })
</script>
<?= $this->endSection() ?>
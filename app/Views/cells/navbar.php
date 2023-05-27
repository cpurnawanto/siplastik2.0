<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main" aria-controls="navbar-main" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbar-main">
    <ul class="navbar-nav ml-auto">
        <?php // Menu unggah khusus admin
        if ($user['is_admin']) : ?>
            <li class="nav-item dropdown">
                <a class="nav-link" href="<?= base_url('berkas/create') ?>" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa fa-upload"></i> Unggah Peta </a>
            </li>
        <?php endif; ?>

        <li class="nav-item dropdown">
            <a class="nav-link" href="<?= base_url('berkas/import') ?>" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i> Unduh Peta </a>
        </li>

        <?php
        //Menu akun 
        ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user mr-1"></i> <?= esc($user['nama_pegawai']) ?></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="<?= base_url('personal/profil') ?>"><i class="fas fa-user mr-1"></i>Profil Saya</a>
                <div class="dropdown-divider"></div>
                <?php // Lihat daftar pegawai khusus admin
                if ($user['is_admin']) : ?>
                <a class="dropdown-item" href="<?= base_url('admin/pegawai') ?>"><i class="fas fa-list mr-1"></i>Daftar Pengguna</a>
                <div class="dropdown-divider"></div>
                <?php endif; ?>
                <a class="dropdown-item" href="<?= base_url('user/logout') ?>"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
            </div>
        </li>
    </ul>
</div>
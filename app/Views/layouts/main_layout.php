<?php

/**
 * Struktur layout utama
 * 
 * Render parameter :
 * head
 * title (controller)
 * content
 * script
 * 
 */

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?= isset($title) ? $title . ' | SIPlastik' : 'SIPlastik' ?></title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.7/css/all.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script><?php function cari_desa($val) {
        alert("$val");
    }
    ?>
    </script>
	<?= $this->renderSection('head') ?>
</head>
<script>
    <?php //untuk menampung script yang dijalankan oleh cells 
    ?>
    var cellScripts = [];
</script>

<body>
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">SIPlastik</a>
            <?=
            /** Memanggil view_cell navbar*/
            view_cell('\\App\\Libraries\\Cells\\Navbar::build')
            ?>
        </div>
    </nav>
    <div class="py-4 mt-4"></div>
    <?= $this->renderSection('content') ?>
    <div class="py-4 mt-4"></div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <?= $this->renderSection('script') ?>
    <script>
        $(function() {
            //Semua yang pakai .datepicker jadi datepicker
            $('.datepicker').datepicker({
                showAnim: "fadeIn",
                dateFormat: "yy-mm-dd"
            })
            //Semua script di cell dijalankan

            cellScripts.forEach(function(fun) {
                fun();
            })
        })
    </script>
</body>
</html>
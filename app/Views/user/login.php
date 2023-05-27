<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Login | SIPlastik</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

	<!-- Fonts and icons -->
	<script src="assets/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['assets/css/fonts.min.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
	
	<!-- CSS Files -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/atlantis.css">
</head>
<body class="login">
	<div class="wrapper wrapper-login wrapper-login-full p-0">
		<div class="login-aside w-50 d-flex flex-column align-items-center justify-content-center text-center bg-primary-gradient">
			<h1 class="title fw-bold text-white mb-3">Sistem Informasi Manajemen Peta Wilayah Kerja Statistik</h1>
			<p class="subtitle text-white op-7">Sepraktis dan seefektif plastik</p>
		</div>
		<div class="login-aside w-50 d-flex align-items-center justify-content-center bg-white">
			<div class="container container-login container-transparent animated fadeIn">
				<h3 class="text-center">Masuk ke SIPlastik</h3>
                    <form action="<?= base_url('user/do-login') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <div class="row align-items-center">
                                <div class="col-lg-3">
                                    <label for="username">Username </label>
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="login[username]" id="username" placeholder="Masukkan Username" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row align-items-center">
                                <div class="col-lg-3">
                                    <label for="password">Password </label>
                                </div>
                                <div class="col-lg-8">
                                    <input type="password" class="form-control" name="login[password]" id="password" placeholder="Masukkan Password" required>
                                    <div class="show-password">
								        <i class="icon-eye"></i>
							        </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group form-action-d-flex mb-3">
                            <input type="submit" value="Masuk" class="btn btn-primary col-md-5 center mt-3 mt-sm-0 fw-bold">
                        </div>
                    </form>
				</div>
			</div>			
		</div>
	</div>
	<script src="assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>
	<script src="assets/js/atlantis.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Stock Market">
    <meta name="keywords" content="Stock Market">
    <meta name="author" content="Stock Market">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Stock Market</title>
   <!-- Fevicon -->
	<link rel="shortcut icon" href="images/favicon/favicon.ico">
	<link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="images/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
	<link rel="manifest" href="images/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<!-- Start css -->
	<!-- Switchery css -->
	<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet">
	<!-- Apex css -->
	<link href="assets/plugins/apexcharts/apexcharts.css" rel="stylesheet">
	<!-- Slick css -->
	<link href="assets/plugins/slick/slick.css" rel="stylesheet">
	<link href="assets/plugins/slick/slick-theme.css" rel="stylesheet">
	<!-- jQuery Confirm css -->
	<link href="assets/plugins/jquery-confirm/css/jquery-confirm.css" rel="stylesheet" type="text/css">
	<!-- DataTables css -->
	<link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<!-- Responsive Datatable css -->
	<link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/icons.css" rel="stylesheet" type="text/css">
	<link href="assets/css/flag-icon.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/style.css" rel="stylesheet" type="text/css">
	<link href="assets/css/pikaday.css" rel="stylesheet" type="text/css">
	<link href="assets/css/jquery.toast.css" rel="stylesheet" type="text/css">
	<!-- End css -->
	<style>
	#loader
	{
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		width: 100%;
		background: rgba(0, 0, 0, 0.75) url(images/loading.gif) no-repeat center center;
		z-index: 10000;
	}
	</style>
</head>
<body class="vertical-layout">
    <!-- Start Containerbar -->
    <div id="containerbar" class="containerbar authenticate-bg">
        <!-- Start Container -->
        <div class="container">
            <div class="auth-box forgot-password-box">
                <!-- Start row -->
                <div class="row no-gutters align-items-center justify-content-center">
                    <!-- Start col -->
                    <div class="col-md-6 col-lg-5">
                        <!-- Start Auth Box -->
                        <div class="auth-box-right">
                            <div class="card">
                                <div class="card-body">
                                    <form  class="form-horizontal" id="recoverform" name="recoverform">
										<div class="alert alert-success" id="responsemessage" style="display:none;"><i class="ti-user"></i>
										   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
										</div>
                                        <div class="form-head">
                                            <a href="javascript:void(0)" class="logo"><img src="images/logo.jpg" class="img-fluid" alt="logo"></a>
                                        </div>
                                        <h4 class="text-primary my-4">Forgot Password ?</h4>
                                        <p class="mb-4">Enter the email address below to receive reset password instructions.</p>
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="recoveremail" name="recoveremail" placeholder="Enter Email here" required>
                                        </div>
                                      <button type="submit" class="btn btn-success btn-lg btn-block font-18" name="submit" id="submit">Send Email</button>
                                    </form>
                                    <p class="mb-0 mt-3">Remember Password? <a href="index.php">Log in</a></p>
                                </div>
                            </div>
                        </div>
                        <!-- End Auth Box -->
                    </div>
                    <!-- End col -->
                </div>
                <!-- End row -->
            </div>
        </div>
        <!-- End Container -->
    </div>
    <!-- End Containerbar -->
	<div id="loader"></div>
    <!-- Start js -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/modernizr.min.js"></script>
	<script src="assets/js/detect.js"></script>
	<script src="assets/js/jquery.slimscroll.js"></script>
	<script src="assets/js/vertical-menu.js"></script>
	<!-- Switchery js -->
	<script src="assets/plugins/switchery/switchery.min.js"></script>
	<!-- jQuery Confirm js -->
	<script src="assets/plugins/jquery-confirm/js/jquery-confirm.js"></script>
	<script src="assets/js/custom/custom-jquery-confirm.js"></script>
	<script type="application/javascript" src="assets/js/moment.js"></script>
	<script type="application/javascript" src="assets/js/pikaday.js"></script>
	<!-- Datatable js -->
	<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
	<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
	<script src="assets/plugins/datatables/jszip.min.js"></script>
	<script src="assets/plugins/datatables/pdfmake.min.js"></script>
	<script src="assets/plugins/datatables/vfs_fonts.js"></script>
	<script src="assets/plugins/datatables/buttons.html5.min.js"></script>
	<script src="assets/plugins/datatables/buttons.print.min.js"></script>
	<script src="assets/plugins/datatables/buttons.colVis.min.js"></script>
	<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
	<script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
	<script src="assets/js/custom/custom-table-datatable.js"></script>
	<script src="assets/js/jquery.toast.js"></script>
	<!-- Core js -->
	<script src="assets/js/core.js"></script>
	<!-- End js -->
	<script>
		$(document).ready(function() {
			var spinner = $('#loader');
			$('#recoverform').on('submit', function(e) {
				e.preventDefault();
				var recoveremail = document.getElementById("recoveremail").value;

				if(recoveremail == "" || recoveremail == null)
				{
					$.toast({
						heading: 'Error',
						text: 'Please Enter the Registered Email',
						showHideTransition: 'slide',
						position: 'top-right',
						icon: 'error'
					});
				}
				else
				{
					spinner.show();
					$.ajax({
						url: "phpfiles/forgotpassword.php",
						type: "POST",
						data: new FormData(this),
						contentType: false,
						processData: false,
						dataType: "json",
						success: function(response) {
							var message = response.message;
							var status = response.status;
							if(status == 1)
							{
								$.toast({
									heading: 'Success',
									text: message,
									showHideTransition: 'slide',
									position: 'top-right',
									icon: 'success'
								});
								spinner.hide();
								$("#recoverform")[0].reset();
								window.location.href = "index.php";
							}
							else
							{
								$.toast({
									heading: 'Error',
									text: message,
									showHideTransition: 'slide',
									position: 'top-right',
									icon: 'error'
								});
								spinner.hide();
							}
						}
					});
				}
			});
		});
	</script>
</body>
</html>
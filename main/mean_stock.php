<?php
session_start();
include '../dbconfig.php';
$pageurl = BASE_URL;
if (!isset($_SESSION['stock_user_name']) || !isset($_SESSION['stock_user_email']) || !isset($_SESSION['stock_user_role']))
{
	$checkingrole = 1;
}
else
{

	$sessionusername = $_SESSION['stock_user_name'];
	$sessionuseremail = $_SESSION['stock_user_email'];
	$sessionuserrole = $_SESSION['stock_user_role'];
	if($sessionuserrole == 1 || $sessionuserrole == 2)
	{
		$checkingrole = 0;
		try
		{
			$checkuserstmt = $conn->prepare("SELECT user_name,user_email from table_users where user_email = :user_email and user_status = 1");
			$checkuserstmt->bindParam(':user_email', $sessionuseremail);
			$checkuserstmt->execute();
			if ($checkuserstmt->rowcount() > 0)
			{
				$userrow = $checkuserstmt->fetch();
				$username = $userrow['user_name'];
			}
			else
			{
				$checkingrole = 1;
			}
		}
		catch(PDOException $e)
		{
			$checkingrole = 1;
		}
	}
	else
	{
		$checkingrole = 1;
	}
}
?>
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
    <link rel="shortcut icon" href="../images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="../images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon/favicon-16x16.png">
    <link rel="manifest" href="../images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Fevicon -->

    <!-- Start CSS -->
    <!-- Switchery css -->
    <link href="../assets/plugins/switchery/switchery.min.css" rel="stylesheet">
	<!-- Datepicker css -->
	<link href="../assets/plugins/datepicker/datepicker.min.css" rel="stylesheet" type="text/css">
    <!-- Select2 css -->
    <link href="../assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css">
    <!-- Tagsinput css -->
    <link href="../assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css">
    <link href="../assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/flag-icon.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">
	<link href="../assets/css/jquery.toast.css" rel="stylesheet" type="text/css">
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
		background: rgba(0, 0, 0, 0.75) url(../images/loading.gif) no-repeat center center;
		z-index: 10000;
	}
	.greenclass
	{
		color:green !important;
		animation: blinker 1s linear infinite;
		text-align:center !important;
	}
	.redclass
	{
		color:red !important;
		animation: blinker 1s linear infinite;
		text-align:center !important;
	}
	@keyframes blinker {
		50% {
			opacity: 0;
		}
	}

	</style>
</head>
<body class="vertical-layout">
    <!-- Start Infobar Setting Sidebar -->
    <div id="infobar-settings-sidebar" class="infobar-settings-sidebar">
        <div class="infobar-settings-sidebar-head d-flex w-100 justify-content-between">
            <h4>Settings</h4><a href="javascript:void(0)" id="infobar-settings-close" class="infobar-settings-close"><img src="assets/images/svg-icon/close.svg" class="img-fluid menu-hamburger-close" alt="close"></a>
        </div>
        <div class="infobar-settings-sidebar-body">
            <div class="custom-mode-setting">
                <div class="row align-items-center pb-3">
                    <div class="col-8"><h6 class="mb-0">Payment Reminders</h6></div>
                    <div class="col-4 text-right"><input type="checkbox" class="js-switch-setting-first" checked /></div>
                </div>
                <div class="row align-items-center pb-3">
                    <div class="col-8"><h6 class="mb-0">Stock Updates</h6></div>
                    <div class="col-4 text-right"><input type="checkbox" class="js-switch-setting-second" checked /></div>
                </div>
                <div class="row align-items-center pb-3">
                    <div class="col-8"><h6 class="mb-0">Open for New Products</h6></div>
                    <div class="col-4 text-right"><input type="checkbox" class="js-switch-setting-third" /></div>
                </div>
                <div class="row align-items-center pb-3">
                    <div class="col-8"><h6 class="mb-0">Enable SMS</h6></div>
                    <div class="col-4 text-right"><input type="checkbox" class="js-switch-setting-fourth" checked /></div>
                </div>
                <div class="row align-items-center pb-3">
                    <div class="col-8"><h6 class="mb-0">Newsletter Subscription</h6></div>
                    <div class="col-4 text-right"><input type="checkbox" class="js-switch-setting-fifth" checked /></div>
                </div>
                <div class="row align-items-center pb-3">
                    <div class="col-8"><h6 class="mb-0">Show Map</h6></div>
                    <div class="col-4 text-right"><input type="checkbox" class="js-switch-setting-sixth" /></div>
                </div>
                <div class="row align-items-center pb-3">
                    <div class="col-8"><h6 class="mb-0">e-Statement</h6></div>
                    <div class="col-4 text-right"><input type="checkbox" class="js-switch-setting-seventh" checked /></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-8"><h6 class="mb-0">Monthly Report</h6></div>
                    <div class="col-4 text-right"><input type="checkbox" class="js-switch-setting-eightth" checked /></div>
                </div>
            </div>
        </div>
    </div>
    <div class="infobar-settings-sidebar-overlay"></div>
    <!-- End Infobar Setting Sidebar -->
    <!-- Start Containerbar -->
    <div id="containerbar">
       <?php include 'header.php'; ?>
            <!-- Start Breadcrumbbar -->
            <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-12 col-lg-12">
						<h4 class="pull-left">Mean Stock</h4>
                    </div>
                </div>
            </div>
            <!-- End Breadcrumbbar -->
            <!-- Start Contentbar -->
            <div class="contentbar">
				<div class="row">
					 <!-- Start col -->
					 <div class="col-lg-12">
                        <div class="card m-b-30">
                            <div class="card-header">
                                <h5 class="card-title" id="stocklisttitle">Mean Stock</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="stocktable" style="border:1px solid black !important;">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Stock Name</th>
                                                <th scope="col">Mean Stock</th>
                                            </tr>
                                          </thead>
                                          <tbody id="dynamicitemtable">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End col -->
				</div>
            </div>
            <!-- End Contentbar -->
            <!-- Start Footerbar -->
            <?php include 'footer.php'; ?>
            <!-- End Footerbar -->
        </div>
        <!-- End Rightbar -->
    </div>
    <!-- End Containerbar -->
	<div id="loader"></div>



   <!-- Start js -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/modernizr.min.js"></script>
    <script src="../assets/js/detect.js"></script>
    <script src="../assets/js/jquery.slimscroll.js"></script>
    <script src="../assets/js/vertical-menu.js"></script>
    <!-- Switchery js -->
    <script src="../assets/plugins/switchery/switchery.min.js"></script>
    <!-- Select2 js -->
    <script src="../assets/plugins/select2/select2.min.js"></script>
    <!-- Tagsinput js -->
    <script src="../assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="../assets/plugins/bootstrap-tagsinput/typeahead.bundle.js"></script>
    <script src="../assets/js/custom/custom-form-select.js"></script>
	<script src="../assets/js/jquery.toast.js"></script>
	<!-- Datepicker JS -->
	<script src="../assets/plugins/datepicker/datepicker.min.js"></script>
    <script src="../assets/plugins/datepicker/i18n/datepicker.en.js"></script>
    <script src="../assets/js/custom/custom-form-datepicker.js"></script>
    <!-- Core js -->
    <script src="../assets/js/core.js"></script>
    <!-- End js -->
	 <script>


		$( document ).ready(function() {
            var checking = '<?php echo $checkingrole; ?>';
            var sno = 1;
            var usersessionemail = '<?php echo $sessionuseremail; ?>';
            var spinner = $('#loader');
            if(checking == 1)
            {
                alert("You are not allowed to use this page");
                window.location.href = "logout.php";
            }
            else
            {
                viewmeanstock(sno);
            }

        });


		var x = 0;
		var y = 5;

		function viewmeanstock(sno)
		{

			if(sno == 1)
			{
				$('#stocktable').addClass('loading');
			}

			$.ajax({
				url:"phpfiles/getmeanstockprice.php",
				data:{startingvalue:x,endingvalue:y,serial:sno},
				type:'POST',
				dataType: "json",
				success:function(response) {
					var message = response.message;
					var status = response.status;
					var resp = response.record;
					var serialno = response.totalserialno;
					if(status == 1)
					{

						$('#dynamicitemtable').append(resp);
						$('#stocktable').removeClass('loading');
						x = x + 5;
						viewmeanstock(serialno);
					}
					else
					{
						$('#stocktable').removeClass('loading');
					}
				}
			});
		}

	 </script>
</body>
</html>
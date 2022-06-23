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
			$checkuserstmt = $conn->prepare("SELECT id,user_name,user_email,user_wallet_amount,user_gender from table_users where user_email = :user_email and user_status = 1");
			$checkuserstmt->bindParam(':user_email', $sessionuseremail);
			$checkuserstmt->execute();
			if ($checkuserstmt->rowcount() > 0)
			{
				$userrow = $checkuserstmt->fetch();
                $usertableid = $userrow['id'];
				$username = $userrow['user_name'];
                $userwalletamount = $userrow['user_wallet_amount'];
                $usergender = $userrow['user_gender'];
                if($usergender == "" || $usergender == null || $usergender == 0)
                {
                    $malegender = "";
                    $femalegender = "";
                }
                elseif($usergender == 1)
                {
                    $malegender = "checked";
                    $femalegender = "";
                }
                elseif($usergender == 2)
                {
                    $malegender = "";
                    $femalegender = "checked";
                }
                else
                {
                    $malegender = "";
                    $femalegender = "";
                }
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
    <!-- Start css -->
    <!-- Switchery css -->
    <link href="../assets/plugins/switchery/switchery.min.css" rel="stylesheet">
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
	</style>
</head>
<body class="vertical-layout">
    <!-- Start Infobar Setting Sidebar -->
    <div id="infobar-settings-sidebar" class="infobar-settings-sidebar">
        <div class="infobar-settings-sidebar-head d-flex w-100 justify-content-between">
            <h4>Settings</h4><a href="javascript:void(0)" id="infobar-settings-close" class="infobar-settings-close"><img src="../assets/images/svg-icon/close.svg" class="img-fluid menu-hamburger-close" alt="close"></a>
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
                        <h4 class="page-title">My Account</h4>
                    </div>
                </div>
            </div>
            <!-- End Breadcrumbbar -->
            <!-- Start Contentbar -->
            <div class="contentbar">
                <!-- Start row -->
                <div class="row">
                    <!-- Start col -->
                    <div class="col-lg-5 col-xl-3">
                        <div class="card m-b-30">
                            <div class="card-header">
                                <h5 class="card-title mb-0">My Account</h5>
                            </div>
                            <div class="card-body">
                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link mb-2 active" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="feather icon-user mr-2"></i>My Profile</a>
                                    <a class="nav-link mb-2" id="v-pills-order-tab" data-toggle="pill" href="#v-pills-order" role="tab" aria-controls="v-pills-order" aria-selected="false"><i class="feather icon-package mr-2"></i>Stock History</a>
                                    <a class="nav-link mb-2" id="v-pills-wishlist-tab" data-toggle="pill" href="#v-pills-wishlist" role="tab" aria-controls="v-pills-wishlist" aria-selected="false"><i class="feather icon-heart mr-2"></i>Profits</a>
                                    <a class="nav-link mb-2" id="v-pills-wallet-tab" data-toggle="pill" href="#v-pills-wallet" role="tab" aria-controls="v-pills-wallet" aria-selected="true"><i class="feather icon-credit-card mr-2"></i>Wallet</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End col -->
                    <!-- Start col -->
                    <div class="col-lg-7 col-xl-9">
                        <div class="tab-content" id="v-pills-tabContent">
                              <!-- My Profile Start -->
                              <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <div class="card m-b-30">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Edit Profile Informations</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="profileupdateform" name="profileupdateform" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="username">Username</label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $sessionusername; ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="useremail">Email</label>
                                                    <input type="email" class="form-control" id="useremail" name="useremail" value = "<?php echo $sessionuseremail; ?>" readonly >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="currentpassword">Current Password</label>
                                                    <input type="password" class="form-control" id="currentpassword" name="currentpassword">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="newpassword">New Password</label>
                                                    <input type="password" class="form-control" id="newpassword" name="newpassword">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="usermale" name="usergender" class="custom-control-input" value="1" <?php echo $malegender; ?>>
                                                    <label class="custom-control-label" for="usermale">Male</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="userfemale" name="usergender" value="2" class="custom-control-input" <?php echo $femalegender; ?>>
                                                    <label class="custom-control-label" for="userfemale">Female</label>
                                                </div>
                                            </div>
                                            <input type="hidden" id="keyid" name="keyid" value="<?php echo $usertableid; ?>" />
                                            <button type="submit" class="btn btn-primary-rgba font-16"><i class="feather icon-save mr-2"></i>Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- My Profile End -->

                            <!-- My Orders Start -->
                            <div class="tab-pane fade" id="v-pills-order" role="tabpanel" aria-labelledby="v-pills-order-tab">
                                <div class="card m-b-30">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Stock History(PRICE IN INR)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="order-box">
                                            <div class="card border m-b-30">
                                                <div class="card-header">
                                                    <div class="row align-items-center">
                                                        <div class="col-sm-6">
                                                            <h5>Purchased History</h5>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <h6 class="mb-0">Total : <strong id="totalspentforpurchase"></strong></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-borderless" id="purchasetable">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col">Purchased Date</th>
                                                                    <th scope="col">Stock Name</th>
                                                                    <th scope="col">Stock Price</th>
                                                                    <th scope="col">Qty</th>
                                                                    <th scope="col">Total Price</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="dynamicpurchasetable">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="order-box">
                                            <div class="card border m-b-30">
                                                <div class="card-header">
                                                    <div class="row align-items-center">
                                                        <div class="col-sm-6">
                                                            <h5>Sell History</h5>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <h6 class="mb-0">Total : <strong id="totalearningsfromsell"></strong></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-borderless" id="soldtable">
                                                            <thead>
                                                            <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col">Sold Date</th>
                                                                    <th scope="col">Stock Name</th>
                                                                    <th scope="col">Stock Price</th>
                                                                    <th scope="col">Qty</th>
                                                                    <th scope="col">Total Price</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="dynamicsoldtable">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- My Orders End -->

                             <!-- My Wishlist Start -->
                             <div class="tab-pane fade" id="v-pills-wishlist" role="tabpanel" aria-labelledby="v-pills-wishlist-tab">
                                <div class="card m-b-30">
                                    <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-sm-8">
                                            <h5>Profit / Loss(PRICE IN INR) </h5>
                                        </div>
                                        <div class="col-sm-4">
                                            <h6 class="mb-0 pull-right">Total Profit / Loss: <strong id="totalprofitorloss"></strong></h6>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="wishlist-box">
                                            <div class="table-responsive">
                                                <table class="table table-borderless" id="profittable">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Stock Name</th>
                                                            <th scope="col">Total P.Qty</th>
                                                            <th scope="col">Total P.Purchase</th>
                                                            <th scope="col">Total S.Qty</th>
                                                            <th scope="col">Total S.Price</th>
                                                            <th scope="col">Profit</th>
                                                            <th scope="col">Loss</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="dynamicprofittable">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- My Wishlist End -->


                            <!-- My Wallet Start -->
                            <div class="tab-pane fade" id="v-pills-wallet" role="tabpanel" aria-labelledby="v-pills-wallet-tab">
                                <div class="card m-b-30">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">My Wallet</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-6 col-md-6 col-lg-4">
                                                <img src="../assets/images/ecommerce/wallet.svg" class="img-fluid" alt="wallet">
                                            </div>
                                        </div>
                                        <div class="wallet-box">
                                            <div class="row align-items-center">
                                                <div class="col-sm-6">
                                                    <h4 class="text-primary"><i class="feather icon-credit-card mr-2"></i><span id="walletbalance">YOUR BALANCE IS: </span><span class="font-20"><?php echo $userwalletamount; ?> INR.</span></h4>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="mb-0"><button class="btn btn-success-rgba font-16" onclick="openmoneymodal(<?php echo $usertableid; ?>)"><i class="feather icon-plus mr-2"></i>Add Money</button></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- My Wallet End -->




                        </div>
                    </div>
                    <!-- End col -->
                </div>
                <!-- End row -->
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

    <!-- Purchase modal Begin -->
	<div class="modal fade" id="addmoneymodal" role="dialog" aria-labelledby="varying-modal-label" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="varying-modal-label">Add Money to Your Wallet</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="addmoneyform" name="addmoneyform" method="post">
					<div class="modal-body">
							 <div class="form-group">
								<label for="recipient-name" class="col-form-label">Enter the Whole Amount(</label>
								<input type="text" class="form-control" id="addmoney" name="addmoney" placeholder="Add Money" onkeypress=" return isNumber(event)">
                                <span style="color:red !important;">Decimal amount not allowed. Ex: Not allowed 100.50 & Allowed: 100,200,..1000,...10000..</span>
							</div>
							<input type="hidden" id="stockusermodalid" name="stockusermodalid" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="addmoneybutton">Add Money</button>
					</div>
			   </form>
			   <div class="modal-footer">
					<p id="yourbalance"></p>
				</div>
			</div>
		</div>
	</div>
	<!-- Purchase modal end -->
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
    <!-- eCommerce My Account Page js -->
    <script src="../assets/js/custom/custom-ecommerce-myaccount.js"></script>
    <script src="../assets/js/jquery.toast.js"></script>
    <!-- Core js -->
    <script src="../assets/js/core.js"></script>
    <!-- End js -->

    <script>
         var usersessionemail = '<?php echo $sessionuseremail; ?>';
        var usertableid = '<?php echo $usertableid; ?>';
        var totalpurchaseprice = 0;
        var totalsoldprice = 0;
        var psno = 1;
        var ssno = 1;
        var profitsno = 1;
        var spinner = $('#loader');

        $( document ).ready(function() {
            var checking = '<?php echo $checkingrole; ?>';
            if(checking == 1)
            {
                alert("You are not allowed to use this page");
                window.location.href = "logout.php";
            }
            else
            {
                viewpurchasehistory(psno,totalpurchaseprice);
                viewsoldhistory(ssno,totalsoldprice);
                viewprofithistory(profitsno);
            }
        });

         function isNumber(evt)
		 {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
         }




        function openmoneymodal(val)
        {
            var moneymodalid = val;
            $("#stockusermodalid").val(moneymodalid);
            $("#addmoneymodal").modal();
        }

        $('#addmoneyform').on('submit', function(e) {
			e.preventDefault();
			var addmoney = document.getElementById("addmoney").value;

			if(addmoney == "" || addmoney == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Enter the Amount',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else
			{
				spinner.show();
				$.ajax({
					url: "phpfiles/addmoney.php",
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
							$("#addmoneyform")[0].reset();
							$('#addmoneymodal').modal('toggle');
                            location.reload();
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

        var x = 0;
		var y = 5;

        function viewpurchasehistory(psno,totalpurchaseprice)
		{
			if(psno == 1)
			{
				$('#purchasetable').addClass('loading');
			}

			$.ajax({
				url:"phpfiles/getpurchasehistory.php",
				data:{startingvalue:x,endingvalue:y,serial:psno,usertableid:usertableid,totalpurchaseprice:totalpurchaseprice},
				type:'POST',
				dataType: "json",
				success:function(response) {
					var message = response.message;
					var status = response.status;

					var resp = response.record;
					var serialno = response.totalserialno;
                    var purchaseprice = response.totalpurchaseprice;
					if(status == 1)
					{
						$('#dynamicpurchasetable').append(resp);
                        $('#totalspentforpurchase').text(purchaseprice);
						$('#purchasetable').removeClass('loading');
						x = x + 5;
						viewpurchasehistory(serialno,purchaseprice);
					}
					else
					{
						$('#purchasetable').removeClass('loading');

					}
				}
			});
		}

        var s = 0;
        var t = 5;

        function viewsoldhistory(ssno,totalsoldprice)
		{
			if(ssno == 1)
			{
				$('#soldtable').addClass('loading');
			}

			$.ajax({
				url:"phpfiles/getsoldhistory.php",
				data:{startingvalue:s,endingvalue:t,serial:ssno,usertableid:usertableid,totalsoldprice:totalsoldprice},
				type:'POST',
				dataType: "json",
				success:function(response) {
					var message = response.message;
					var status = response.status;
					var resp = response.record;
					var serialno = response.totalserialno;
                    var soldprice = response.totalsoldprice;
					if(status == 1)
					{
						$('#dynamicsoldtable').append(resp);
                        $('#totalearningsfromsell').text(soldprice);
						$('#soldtable').removeClass('loading');
						s = s + 5;
						viewsoldhistory(serialno,soldprice);
					}
					else
					{
						$('#soldtable').removeClass('loading');

					}
				}
			});
		}

        var k = 0;
        var l = 5;

        function viewprofithistory(profitsno)
		{
			if(profitsno == 1)
			{
				$('#profittable').addClass('loading');
			}

			$.ajax({
				url:"phpfiles/getprofithistory.php",
				data:{startingvalue:k,endingvalue:l,serial:profitsno,usertableid:usertableid},
				type:'POST',
				dataType: "json",
				success:function(response) {
					var message = response.message;
					var status = response.status;
					var resp = response.record;
					var serialno = response.totalserialno;
                    var totalproforloss = response.totalprofitorloss;
					if(status == 1)
					{
						$('#dynamicprofittable').append(resp);
						$('#profittable').removeClass('loading');
                        $('#totalprofitorloss').text(totalproforloss);
						s = s + 5;
						//viewprofithistory(serialno);
					}
					else
					{
						$('#profittable').removeClass('loading');
					}
				}
			});
		}

        $('#profileupdateform').on('submit', function(e) {
			e.preventDefault();
			var username = document.getElementById("username").value;
			var useremail = document.getElementById("useremail").value;

			if(username == "" || username == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Enter the User Name',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else if(useremail == "" || useremail == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Enter the Your Email',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else
			{
				spinner.show();
				$.ajax({
					url: "phpfiles/updateuserprofile.php",
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
                            location.reload();
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
    </script>
</body>
</html>
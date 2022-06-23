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
						<h4 class="pull-left">Stocks List</h4>
                    </div>
                </div>
            </div>
            <!-- End Breadcrumbbar -->
            <!-- Start Contentbar -->
            <div class="contentbar">
				<div class="row">
					<!-- End col -->
					<div class="col-lg-12">
                        <div class="card m-b-30">
							<div class="card-header">
                                <h5 class="card-title">Filter</h5>
                            </div>
                            <div class="card-body">
								<form id="filterform" name="filterform" method="post" autocomplete="off">
								<div class="col-md-2 pull-left">
									<select class="select2-single form-control" name="stocknamefilter" id="stocknamefilter">
										<option selected="selected" value="">---- Select Stock Name ----</option>
										<?php

											$stockdropdownres = $conn->prepare("SELECT id,stock_name from table_stocks GROUP BY stock_name");
											$stockdropdownres->execute();
											if ($stockdropdownres->rowcount() > 0)
											{
												$result = $stockdropdownres->fetchAll();
												foreach ($result as $row)
												{
										?>
													<option value="<?php echo $row['stock_name']; ?>"><?php echo $row['stock_name']; ?></option>
										<?php
												}
											}

										?>
									</select>
								</div>
								<div class="col-md-4 pull-left">
									<div class="input-group">
										<input type="text" id="range-date" class="datepicker-here form-control" placeholder="From Date - To Date" name="daterangefilter" aria-describedby="basic-addon7" autocomplete="off" />
										<div class="input-group-append">
											<span class="input-group-text" id="basic-addon7"><i class="feather icon-calendar"></i></span>
										</div>
									</div>
								</div>

								<div class="col-md-2 pull-left">
									<button type="submit" class="btn btn-primary"><i class="feather icon-search"></i> Search</button>
									<button type="button" class="btn btn-danger" onclick="clearfilter();"><i class="feather icon-delete"></i> Clear</button>
								</div>



								</form>
                            </div>
                        </div>
                    </div>
                    <!-- End col -->
					 <!-- Start col -->
					 <div class="col-lg-12">
                        <div class="card m-b-30">
                            <div class="card-header">
                                <h5 class="card-title" id="stocklisttitle">Stock List</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="stocktable" style="border:1px solid black !important;">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Stock Date</th>
                                                <th scope="col">Stock Name</th>
                                                <th scope="col">Stock Price</th>
												<th scope="col" class="text-center">Buy</th>
												<th scope="col" class="text-center">Sell</th>
												<th scope="col" id="bestdatecolumn" style="display:none;text-align:center !important;">Best Deals</th>
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
	<!-- Purchase modal Begin -->
	<div class="modal fade" id="buymodal" role="dialog" aria-labelledby="varying-modal-label" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="varying-modal-label"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="buymodalform" name="buymodalform" method="post">
					<div class="modal-body">
							 <div class="form-group">
								<label for="recipient-name" class="col-form-label">Stock Name</label>
								<input type="text" class="form-control" id="stockname" name="stockname" placeholder="Stock Name" readonly>
							</div>
							<div class="form-group">
								<label for="recipient-name" class="col-form-label">Stock Price</label>
								<input type="text" class="form-control" id="stockprice" name="stockprice" placeholder="Stock Price" readonly>
							</div>
							<div class="form-group">
								<label for="recipient-name" class="col-form-label">Volume</label>
								<input type="text" class="form-control" id="stockvolume" name="stockvolume" placeholder="Stock Volume" onchange="buyincreasepricefunction()">
							</div>
							<div class="form-group">
								<label for="recipient-name" class="col-form-label">Total Price</label>
								<input type="text" class="form-control" id="stocktotalprice" name="stocktotalprice" placeholder="Stock Total Price">
							</div>
							<input type="hidden" id="createdby" name="createdby" value="<?php echo $username; ?>" />
							<input type="hidden" id="inputuseremail" name="inputuseremail" value="<?php echo $sessionuseremail; ?>" />
							<input type="hidden" id="stockmodalid" name="stockmodalid" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="buymodalbutton">Buy Stocks</button>
					</div>
			   </form>
			   <div class="modal-footer">
					<p id="yourbalance"></p>
				</div>
			</div>
		</div>
	</div>
	<!-- Purchase modal end -->

	<!-- Sales modal Begin -->
	<div class="modal fade" id="sellmodal" role="dialog" aria-labelledby="sell_varying-modal-label" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="sell_varying-modal-label"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="sellmodalform" name="sellmodalform" method="post">
					<div class="modal-body">
							 <div class="form-group">
								<label for="recipient-name" class="col-form-label">Stock Name</label>
								<input type="text" class="form-control" id="sellstockname" name="sellstockname" placeholder="Stock Name" readonly>
							</div>
							<div class="form-group">
								<label for="recipient-name" class="col-form-label">Stock Price</label>
								<input type="text" class="form-control" id="sellstockprice" name="sellstockprice" placeholder="Stock Price" readonly>
							</div>
							<div class="form-group">
								<label for="recipient-name" class="col-form-label">Volume</label>
								<input type="text" class="form-control" id="sellstockvolume" name="sellstockvolume" placeholder="Stock Volume" onchange="sellincreasepricefunction()">
							</div>
							<div class="form-group">
								<label for="recipient-name" class="col-form-label">Total Price</label>
								<input type="text" class="form-control" id="sellstocktotalprice" name="sellstocktotalprice" placeholder="Stock Total Price">
							</div>
							<input type="hidden" id="sellcreatedby" name="sellcreatedby" value="<?php echo $username; ?>" />
							<input type="hidden" id="sellinputuseremail" name="sellinputuseremail" value="<?php echo $sessionuseremail; ?>" />
							<input type="hidden" id="sellstockmodalid" name="sellstockmodalid" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="sellmodalbutton">Sell Stocks</button>
					</div>
			   </form>
			   <div class="modal-footer">
					<p id="yourstockbalance"></p>
				</div>
			</div>
		</div>
	</div>
	<!-- Sales modal end -->


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
            if(checking == 1)
            {
                alert("You are not allowed to use this page");
                window.location.href = "logout.php";
            }

        });

		var sno = 1;
		var usersessionemail = '<?php echo $sessionuseremail; ?>';
		var spinner = $('#loader');

		$( document ).ready(function()
		{
			$("#stocknamedropdown").select2();
			viewstocks(sno);
		});

		function clearfilter()
		{
			location.reload();
		}



		$('#filterform').on('submit', function(e) {
			e.preventDefault();
			var stocknamefilter = document.getElementById("stocknamefilter").value;
			var daterangefilter = document.getElementById("range-date").value;



			if(stocknamefilter == "" || stocknamefilter == null || stocknamefilter == 0)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Select the Stock Name',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else if(daterangefilter == "" || daterangefilter == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Select the From and To Date',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else
			{
				var datechecking = daterangefilter;

				var datechecking1 = datechecking.includes('-');

				if(datechecking1 === true)
				{
					var datevalue = daterangefilter;

					var datevaluearray = datevalue.split('-');
					var fromdate = datevaluearray[0];
					var todate = datevaluearray[1];

					if(fromdate == "" || fromdate == null)
					{
						$.toast({
							heading: 'Error',
							text: 'Please Enter the From Date',
							showHideTransition: 'slide',
							position: 'top-right',
							icon: 'error'
						});
					}
					else if(todate == "" || todate == null)
					{
						$.toast({
							heading: 'Error',
							text: 'Please Enter the To Date',
							showHideTransition: 'slide',
							position: 'top-right',
							icon: 'error'
						});
					}
					else
					{

						spinner.show();
						$.ajax({
							url: "phpfiles/checkstocks.php",
							type: "POST",
							data: new FormData(this),
							contentType: false,
							processData: false,
							dataType: "json",
							success: function(response) {
								var message = response.message;
								var status = response.status;
								var resp = response.record;
								if(status == 1)
								{
									$('#dynamicitemtable').empty();
									$('#dynamicitemtable').append(resp);
									$('#stocklisttitle').text(response.stocklisttitle);
									$('#bestdatecolumn').show();
									$.toast({
										heading: 'Success',
										text: message,
										showHideTransition: 'slide',
										position: 'top-right',
										icon: 'success'
									});
									spinner.hide();
								}
								else if(status == 2)
								{
									$('#dynamicitemtable').empty();
									$('#dynamicitemtable').append(resp);
									$('#stocklisttitle').text(response.stocklisttitle);
									$('#bestdatecolumn').hide();
									$.toast({
										heading: 'Success',
										text: message,
										showHideTransition: 'slide',
										position: 'top-right',
										icon: 'success'
									});
									spinner.hide();
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
				}
				else
				{
					$.toast({
						heading: 'Error',
						text: 'Please Select the To Date',
						showHideTransition: 'slide',
						position: 'top-right',
						icon: 'error'
					});
				}
			}
		});

		var x = 0;
		var y = 5;

		function viewstocks(sno)
		{
			
			if(sno == 1)
			{
				$('#stocktable').addClass('loading');
			}

			$.ajax({
				url:"phpfiles/getstocklist.php",
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
						viewstocks(serialno);
					}
					else
					{
						$('#stocktable').removeClass('loading');
					}
				}
			});
		}




		function buystocks(val)
		{
			var stocktableids = val;
			spinner.show();
			$.ajax({
				url:"phpfiles/getstockmodal.php",
				data:{row_id:stocktableids,usersessionemail:usersessionemail,identify:1},
				type:'POST',
				dataType: "json",
				success:function(response)
				{
					var message = response.message;
					var status = response.status;
					if(status == 1)
					{
						$('#varying-modal-label').text("Buy "+response.data.stock_name);
						$("#stockname").val(response.data.stock_name);
						$("#stockprice").val(response.data.stock_price);
						$("#stockmodalid").val(response.data.id);
						$('#yourbalance').text("Balance "+response.userbalanceamount);
						$("#buymodal").modal();
						spinner.hide();
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

		function buyincreasepricefunction()
		{
			var pricevalue = $('#stockprice').val();
			var totalvolume = $('#stockvolume').val();

			var finalvolumeprice = pricevalue * totalvolume;

			$('#stocktotalprice').val(finalvolumeprice);
		}

		function sellincreasepricefunction()
		{
			var pricevalue1 = $('#sellstockprice').val();
			var totalvolume1 = $('#sellstockvolume').val();

			var finalvolumeprice1 = pricevalue1 * totalvolume1;

			$('#sellstocktotalprice').val(finalvolumeprice1);
		}

		$('#buymodalform').on('submit', function(e) {
			e.preventDefault();
			var stockname = document.getElementById("stockname").value;
			var stockprice = document.getElementById("stockprice").value;
			var stockvolume = document.getElementById("stockvolume").value;
			var stocktotalprice = document.getElementById("stocktotalprice").value;

			if(stockname == "" || stockname == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Enter the Stock Name',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else if(stockprice == "" || stockprice == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Enter the Stock Price',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else if(stockvolume == "" || stockvolume == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Enter the Stock Volume',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else if(stocktotalprice == "" || stocktotalprice == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Enter the Stock Total Price',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else
			{
				spinner.show();
				$.ajax({
					url: "phpfiles/savebuystocks.php",
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
							$("#buymodalform")[0].reset();
							$('#buymodal').modal('toggle');
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

		function sellstocks(val)
		{
			var sellstocktableids = val;
			spinner.show();
			$.ajax({
				url:"phpfiles/getstockmodal.php",
				data:{sellrow_id:sellstocktableids,sellusersessionemail:usersessionemail,identify:2},
				type:'POST',
				dataType: "json",
				success:function(response)
				{
					var message = response.message;
					var status = response.status;
					if(status == 1)
					{
						$('#sell_varying-modal-label').text("Sell "+response.data.stock_name);
						$("#sellstockname").val(response.data.stock_name);
						$("#sellstockprice").val(response.data.stock_price);
						$("#sellstockmodalid").val(response.data.id);
						$('#yourstockbalance').text("Available Stocks "+response.availablestock);
						$("#sellmodal").modal();
						spinner.hide();
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

		$('#sellmodalform').on('submit', function(e) {
			e.preventDefault();
			var sellstockname = document.getElementById("sellstockname").value;
			var sellstockprice = document.getElementById("sellstockprice").value;
			var sellstockvolume = document.getElementById("sellstockvolume").value;
			var sellstocktotalprice = document.getElementById("sellstocktotalprice").value;

			if(sellstockname == "" || sellstockname == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Enter the Stock Name',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else if(stockprice == "" || stockprice == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Enter the Stock Price',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else if(stockvolume == "" || stockvolume == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Enter the Stock Volume',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else if(stocktotalprice == "" || stocktotalprice == null)
			{
				$.toast({
					heading: 'Error',
					text: 'Please Enter the Stock Total Price',
					showHideTransition: 'slide',
					position: 'top-right',
					icon: 'error'
				});
			}
			else
			{
				spinner.show();
				$.ajax({
					url: "phpfiles/savesellstocks.php",
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
							$("#sellmodalform")[0].reset();
							$('#sellmodal').modal('toggle');
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
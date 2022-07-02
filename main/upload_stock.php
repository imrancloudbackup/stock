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

     <!-- Start css -->
    <?php include 'csslinks.php'; ?>
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
	 .file-upload1 {
            background-color: #ffffff;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .file-upload-btn1 {
            width: 100%;
            margin: 0;
            color: #fff;
            background: #1FB264;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #15824B;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .file-upload-btn1:hover {
            background: #1AA059;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .file-upload-btn1:active {
            border: 0;
            transition: all .2s ease;
        }

        .file-upload-content1 {
            display: none;
            text-align: center;
        }

        .file-upload-input1 {
            position: absolute;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            outline: none;
            opacity: 0;
            cursor: pointer;
        }

        .image-upload-wrap1 {
            margin-top: 20px;
            border: 4px dashed #1FB264;
            position: relative;
        }

        .image-dropping1,
        .image-upload-wrap1:hover {
            background-color: #1FB264;
            border: 4px dashed #ffffff;
        }

        .image-title-wrap1 {
            padding: 0 15px 15px 15px;
            color: #222;
        }

        .drag-text1 {
            text-align: center;
        }

        .drag-text1 h3 {
            font-weight: 100;
            text-transform: uppercase;
            color: #15824B;
            padding: 60px 0;
        }

        .file-upload-image1 {
            max-height: 200px;
            max-width: 200px;
            margin: auto;
            padding: 20px;
        }

        .remove-image1 {
            width: 200px;
            margin: 0;
            color: #fff;
            background: #cd4535;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #b02818;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .remove-image1:hover {
            background: #c13b2a;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .remove-image1:active {
            border: 0;
            transition: all .2s ease;
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
      <?php include 'header.php' ; ?>
            <!-- Start Breadcrumbbar -->
            <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-12 col-lg-12">
                        <h4 class="page-title">Upload your File</h4>
                    </div>
                </div>
            </div>
            <!-- End Breadcrumbbar -->
            <!-- Start Contentbar -->
            <div class="contentbar">
                <!-- Start row -->
                <div class="row">
                    <!-- Start col -->
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="card m-b-30">
                            <div class="card-header">
                                <h5 class="card-title">File upload</h5>
                            </div>
                            <div class="card-body">
                                <form class="card" id="uploadform" name="uploadform" method="post" enctype="multipart/form-data">
                                     <div class="col-md-12">
										<div class="file-upload1">
											<div id="drop-zone">
												<div class="image-upload-wrap1">
													<input class="file-upload-input1" type='file' id="drop-zone-file" name="file" accept=".csv" />
													<div class="drag-text1">
														<h3 id="totalimages">Drag and drop a file or Select a File</h3>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="text-center">
										<button type="submit" class="btn btn-primary" id="btnSubmit" name="submit">
											<i class="feather icon-upload"></i> Upload Your File
										</button>
									</div>
                                </form>
                            </div>
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
	 <!-- Start js -->
    <?php include 'scriptlinks.php'; ?>
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

	</script>

	<script>
	$(document).ready(function() {
		var spinner = $('#loader');
		$('#uploadform').on('submit', function(e) {
			e.preventDefault();

			if( document.getElementById("drop-zone-file").files.length == 0 )
			{
				$.toast({
                    heading: 'Error',
                    text: 'Please Select the File',
                    showHideTransition: 'slide',
                    position: 'top-right',
                    icon: 'error'
                });
			}
			else
			{
				spinner.show();
				$.ajax({
					url: "phpfiles/upload_stock_files.php",
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
							$("#uploadform")[0].reset();
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
                            location.reload();
						}
					}
				});
			}
		});
	});

    $('#drop-zone-file').on('change', function(e) {
            var files = $('#drop-zone-file')[0].files;
            if (files.length < 1)
            {
                document.getElementById('totalimages').innerHTML = "Drag and drop or Select a CSV File";
            }
            else
            {
                document.getElementById('totalimages').innerHTML = files.length + " File(s) Selected";
            }
        });
	</script>
</body>
</html>
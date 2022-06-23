<?php
session_start();
include '../dbconfig.php';

if (empty($_POST['useremail']) || empty($_POST['userpassword']))
{
    echo json_encode(array("status" => "0","message" => "Please fill all details"));
}
else
{
	$useremail =  trim(isset($_POST["useremail"]) ? $_POST["useremail"] : "");
	$userpassword = trim(isset($_POST["userpassword"]) ? $_POST["userpassword"] : "");
	$userpassword = md5($userpassword);
	$userpassword = trim($userpassword);

	try
	{
		$checkuserstmt = $conn->prepare("SELECT user_name,user_email,user_password,user_status,user_role from table_users where user_email = :user_email and user_password = :user_password and user_status = 1");
		$checkuserstmt->bindParam(':user_email', $useremail);
		$checkuserstmt->bindParam(':user_password', $userpassword);
		$checkuserstmt->execute();
		if ($checkuserstmt->rowcount() > 0)
		{
			$userrow = $checkuserstmt->fetch();
			$sessionusername  = $userrow['user_name'];
			$sessionuseremail = $userrow['user_email'];
			$sessionuserrole  = $userrow['user_role'];

			if(empty($sessionuserrole))
			{
				session_destroy();
				echo json_encode(array("status" => "0","message" => "Ask admin to assign the role for you."));
			}
			else if($sessionuserrole == 1)
			{
				$_SESSION['stock_user_name']  = $sessionusername;
				$_SESSION['stock_user_email'] = $sessionuseremail;
				$_SESSION['stock_user_role']  = $sessionuserrole;

				echo json_encode(array("status" => "1","message" => $sessionusername . "(Admin) Login Successfully","loginuserrole" => $sessionuserrole));
			}
			else if($sessionuserrole == 2)
			{
				$_SESSION['stock_user_name']  = $sessionusername;
				$_SESSION['stock_user_email'] = $sessionuseremail;
				$_SESSION['stock_user_role']  = $sessionuserrole;

				echo json_encode(array("status" => "1","message" => $sessionusername . "(User) Login Successfully","loginuserrole" => $sessionuserrole));
			}
			else
			{
				echo json_encode(array("status" => "0","message" => "Ask admin to assign the role for you"));
			}
		}
		else
		{
			echo json_encode(array("status" => "0","message" => "Ask admin to assign the role for you"));
		}
	}
	catch(PDOException $e)
	{
		echo json_encode(array("status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon"));
	}
}
?>
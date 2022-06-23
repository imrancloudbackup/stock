<?php
session_start();
include "../dbconfig.php";

if(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"]))
{
	echo json_encode(array("status" => "0","message" => "Please fill all details"));
}
else
{
	date_default_timezone_set('Indian/Maldives');
	$signup_datetime = date('d/m/Y h:i:s a', time());
	$username =  trim(isset($_POST["name"]) ? $_POST["name"] : "");
	$useremail =  trim(isset($_POST["email"]) ? $_POST["email"] : "");
	$userpassword =  trim(isset($_POST["password"]) ? $_POST["password"] : "");
	$userpassword = md5($userpassword);
	$userpassword = trim($userpassword);
	$userip = getenv('REMOTE_ADDR');
	$userclient= $_SERVER['HTTP_USER_AGENT'];
	$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$userip"));
	$city = $geo["geoplugin_city"];
	$region = $geo["geoplugin_regionName"];
	$country = $geo["geoplugin_countryName"];
	$userlocation="$city, $region, $country";
	$userstatus = 1;
	$usercreatedsource = 1;
	$todaypurchase = 0;
	$userrole = 1;

	try
	{
		$checkuserstmt = $conn->prepare("SELECT user_email from table_users where user_email = :user_email");
		$checkuserstmt->bindParam(':user_email', $useremail);
		$checkuserstmt->execute();
		if ($checkuserstmt->rowcount() > 0)
		{
			echo json_encode(array("status" => "1","message" => "User Already Exists"));
		}
		else
		{
			$stmt = $conn->prepare("INSERT INTO table_users(user_name,user_email,user_role,user_password,created_by,created_date,created_ip,created_location,user_status,user_created_source,today_purchase) VALUES (:user_name,:user_email,:user_role,:user_password,:created_by,:created_date,:created_ip,:created_location,:user_status,:user_created_source,:todaypurchase)");
			$stmt->bindParam(':user_name', $username);
			$stmt->bindParam(':user_email', $useremail);
			$stmt->bindParam(':user_role', $userrole);
			$stmt->bindParam(':user_password', $userpassword);
			$stmt->bindParam(':created_by', $username);
			$stmt->bindParam(':created_date', $signup_datetime);
			$stmt->bindParam(':created_ip', $userip);
			$stmt->bindParam(':created_location', $userlocation);
			$stmt->bindParam(':user_status', $userstatus);
			$stmt->bindParam(':user_created_source', $usercreatedsource);
			$stmt->bindParam(':todaypurchase', $todaypurchase);
			if($stmt->execute())
			{
				echo json_encode(array("status" => "1","message" => "Success"));
			}
			else
			{
				echo json_encode(array("status" => "0","message" => "Something went wrong please try again"));
			}
		}
	}
	catch(PDOException $e)
	{
		echo json_encode(array("status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon"));
	}
}
?>

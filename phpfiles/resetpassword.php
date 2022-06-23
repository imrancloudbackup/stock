<?php
session_start();

include '../dbconfig.php';

if (empty($_POST['password']) || empty($_POST['confirmpassword']) || empty($_POST['recovery']) || empty($_POST['token']))
{
   echo json_encode(array("status" => "0","message" => "Please fill all details"));
}
else
{

	$useremail= trim(isset($_POST["recovery"]) ? $_POST["recovery"] : "");
	$userpassword= trim(isset($_POST["password"]) ? $_POST["password"] : "");
	$userpassword = trim(md5($userpassword));
	$usertoken= trim(isset($_POST["token"]) ? $_POST["token"] : "");
	$useremail = base64url_decode($useremail);

	try
	{
		$checkuserstmt = $conn->prepare("SELECT user_email,security_token from table_users where user_email = :user_email and security_token = :security_token");
		$checkuserstmt->bindParam(':user_email', $useremail);
		$checkuserstmt->bindParam(':security_token', $usertoken);
		$checkuserstmt->execute();
		if ($checkuserstmt->rowcount() > 0)
		{
			$stmt = $conn->prepare("update table_users set user_password = :user_password where user_email = :user_email ");
			$stmt->bindParam(':user_email', $useremail);
			$stmt->bindParam(':user_password', $userpassword);
			if($stmt->execute())
			{
				echo json_encode(array("status" => "1","message" => "Password Changed Successfully"));
			}
			else
			{
				echo json_encode(array("status" => "0","message" => "Something went wrong! Please try again later"));
			}
		}
		else
		{
			echo json_encode(array("status" => "0","message" => "User not exist"));
		}
	}
	catch(PDOException $e)
	{
		echo json_encode(array("status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon"));
	}

}

function base64url_decode($data)
{
    return base64_decode(str_replace(array('-', '_'), array('+', '/'), $data));
}


?>


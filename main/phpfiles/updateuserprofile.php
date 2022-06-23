<?php
session_start();
include "../../dbconfig.php";

if(empty($_POST["username"]) || empty($_POST["useremail"]))
{
	echo json_encode(array("status" => "0","message" => "Please fill all details"));
}
else
{
	date_default_timezone_set('Indian/Maldives');
	$signup_datetime = date('d/m/Y h:i:s a', time());
	$username =  trim(isset($_POST["username"]) ? $_POST["username"] : "");
    $useremail =  trim(isset($_POST["useremail"]) ? $_POST["useremail"] : "");
	$usercurrentpassword =  trim($_POST["currentpassword"]);
    if($usercurrentpassword == "" || $usercurrentpassword == null)
    {
        $usercurrentpassword = "";
    }
    else
    {

        $usercurrentpassword = md5($usercurrentpassword);
        $usercurrentpassword = trim($usercurrentpassword);
    }


    $usernewpassword =  trim($_POST["newpassword"]);
    if($usernewpassword == "" || $usernewpassword == null)
    {
        $usernewpassword = "";

    }
    else
    {
        $usernewpassword = md5($usernewpassword);
        $usernewpassword = trim($usernewpassword);
    }


    $usergender =  trim(isset($_POST["usergender"]) ? $_POST["usergender"] : "");
    $keyid =  trim(isset($_POST["keyid"]) ? $_POST["keyid"] : "");

	$userip = getenv('REMOTE_ADDR');
	$userclient= $_SERVER['HTTP_USER_AGENT'];
	$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$userip"));
	$city = $geo["geoplugin_city"];
	$region = $geo["geoplugin_regionName"];
	$country = $geo["geoplugin_countryName"];
	$userlocation="$city, $region, $country";
	$userstatus = 1;
	$usercreatedsource = 1;

	try
	{
        if($usercurrentpassword != null || $usercurrentpassword != "" && $usernewpassword != null || $usernewpassword != "")
        {
            $checkuserstmt = $conn->prepare("SELECT id,user_email,user_password from table_users where user_email = :user_email and id = :id and user_password = :userpassword");
            $checkuserstmt->bindParam(':user_email', $useremail);
            $checkuserstmt->bindParam(':id', $keyid);
            $checkuserstmt->bindParam(':userpassword', $usercurrentpassword);
            $checkuserstmt->execute();
            if ($checkuserstmt->rowcount() > 0)
            {
                $updatestmt = $conn->prepare("UPDATE table_users set user_name = :user_name, user_password = :user_password, user_gender = :user_gender where id = :usertableid and user_email = :user_email");
                $updatestmt->bindParam(':user_name', $username);
                $updatestmt->bindParam(':user_password', $usernewpassword);
                $updatestmt->bindParam(':user_gender', $usergender);
                $updatestmt->bindParam(':usertableid', $keyid);
                $updatestmt->bindParam(':user_email', $useremail);
                if($updatestmt->execute())
                {
                    echo json_encode(array("status" => "1","message" => "Profile Updated Successfully"));
                }
                else
                {
                    echo json_encode(array("status" => "0","message" => "Please Check your details are Correct"));
                }
            }
            else
            {
                echo json_encode(array("status" => "0","message" => "Your Account Details Not Found1"));
            }
        }
        else
        {
            $checkuserstmt = $conn->prepare("SELECT id,user_email from table_users where user_email = :user_email and id = :id");
            $checkuserstmt->bindParam(':user_email', $useremail);
            $checkuserstmt->bindParam(':id', $keyid);
            $checkuserstmt->execute();
            if ($checkuserstmt->rowcount() > 0)
            {
                $updatestmt = $conn->prepare("UPDATE table_users set user_name = :user_name, user_gender = :user_gender where id = :usertableid and user_email = :user_email ");
                $updatestmt->bindParam(':user_name', $username);
                $updatestmt->bindParam(':user_gender', $usergender);
                $updatestmt->bindParam(':usertableid', $keyid);
                $updatestmt->bindParam(':user_email', $useremail);
                if($updatestmt->execute())
                {
                    echo json_encode(array("status" => "1","message" => "Profile Updated Successfully"));
                }
                else
                {
                    echo json_encode(array("status" => "0","message" => "Please Check your details are Correct"));
                }
            }
            else
            {
                echo json_encode(array("status" => "0","message" => "Your Account Details Not Found2"));
            }
        }
	}
	catch(PDOException $e)
	{
        echo json_encode(array( "status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon. Please try after some time" ) );
	}
}
?>

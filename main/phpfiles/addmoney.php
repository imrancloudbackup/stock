<?php
session_start();
include "../../dbconfig.php";

if(empty($_POST["addmoney"]))
{
	echo json_encode(array("status" => "0","message" => "Please fill all details"));
}
else
{
	date_default_timezone_set('Indian/Maldives');
	$signup_datetime = date('d/m/Y h:i:s a', time());
	$addmoney =  trim(isset($_POST["addmoney"]) ? $_POST["addmoney"] : "");
    $stockusermodalid =  trim(isset($_POST["stockusermodalid"]) ? $_POST["stockusermodalid"] : "");

	$userip = getenv('REMOTE_ADDR');
	$userclient= $_SERVER['HTTP_USER_AGENT'];
	$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$userip"));
	$city = $geo["geoplugin_city"];
	$region = $geo["geoplugin_regionName"];
	$country = $geo["geoplugin_countryName"];
	$userlocation="$city, $region, $country";

	try
	{
        $checkuserstmt = $conn->prepare("SELECT id,user_email,user_wallet_amount from table_users where id = :id");
        $checkuserstmt->bindParam(':id', $stockusermodalid);
        $checkuserstmt->execute();
        if ($checkuserstmt->rowcount() > 0)
        {
            $checkuserstmtrow = $checkuserstmt->fetch();
            $existinguserwalletamount = $checkuserstmtrow['user_wallet_amount'];

            $finalwalletamount =  $existinguserwalletamount + $addmoney;

            $updatestmt = $conn->prepare("update table_users set user_wallet_amount = :user_wallet_amount where id = :usertableid");
            $updatestmt->bindParam(':user_wallet_amount', $finalwalletamount);
            $updatestmt->bindParam(':usertableid', $stockusermodalid);
            if($updatestmt->execute())
            {
                echo json_encode(array("status" => "1","message" => "Amount Updated Successfully"));
            }
            else
            {
                echo json_encode(array("status" => "0","message" => "Please Check your details are Correct"));
            }
        }
        else
        {
            echo json_encode(array("status" => "0","message" => "Your Account Details Not Found"));
        }
	}
	catch(PDOException $e)
	{
        echo json_encode(array( "status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon. Please try after some time" ) );
	}
}
?>

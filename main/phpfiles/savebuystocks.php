<?php
session_start();
include "../../dbconfig.php";

if(empty($_POST["stockname"]) || empty($_POST["stockprice"]) || empty($_POST["stockvolume"]) || empty($_POST["stocktotalprice"]))
{
	echo json_encode(array("status" => "0","message" => "Please fill all details"));
}
else
{
	date_default_timezone_set('Indian/Maldives');
	$stockpurchaseddate = date("Y-m-d");
	$stockname =  trim(isset($_POST["stockname"]) ? $_POST["stockname"] : "");
	$stockprice =  trim(isset($_POST["stockprice"]) ? $_POST["stockprice"] : "");
	$stockvolume =  trim(isset($_POST["stockvolume"]) ? $_POST["stockvolume"] : "");
    $stocktotalprice =  trim(isset($_POST["stocktotalprice"]) ? $_POST["stocktotalprice"] : "");
    $stockmodalid =  trim(isset($_POST["stockmodalid"]) ? $_POST["stockmodalid"] : "");
    $inputuseremail =  trim(isset($_POST["inputuseremail"]) ? $_POST["inputuseremail"] : "");
	$userip = getenv('REMOTE_ADDR');
	$userclient= $_SERVER['HTTP_USER_AGENT'];
	$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$userip"));
	$city = $geo["geoplugin_city"];
	$region = $geo["geoplugin_regionName"];
	$country = $geo["geoplugin_countryName"];
	$userlocation="$city, $region, $country";
	$userstatus = 1;
	$usercreatedsource = 1;
    $updatepurchase = 1;

	try
	{
		$checkuserstmt = $conn->prepare("SELECT id,user_name,user_wallet_amount,user_email,today_purchase from table_users where user_email = :user_email");
		$checkuserstmt->bindParam(':user_email', $inputuseremail);
		$checkuserstmt->execute();
		if ($checkuserstmt->rowcount() > 0)
		{
            $userrow = $checkuserstmt->fetch();
            $usertableid  = $userrow['id'];
			$username  = $userrow['user_name'];
			$useremail = $userrow['user_email'];
			$userwalletamount  = $userrow['user_wallet_amount'];
            $todaypurchase  = $userrow['today_purchase'];

            if($todaypurchase == 1)
            {
                echo json_encode(array("status" => "0","message" => "Your Today Quota Finished. Please Try again Tommorrow"));
            }
            else
            {
                    $tablestocksstmt = $conn->prepare("SELECT * from table_stocks where id = :id");
                    $tablestocksstmt->bindParam(':id', $stockmodalid);
                    $tablestocksstmt->execute();
                    if ($tablestocksstmt->rowcount() > 0)
                    {
                        $stocktablerow = $tablestocksstmt->fetch();
                        $stocktableid  = $stocktablerow['id'];
                        $stocktablename  = $stocktablerow['stock_name'];
                        $stocktabledate = $stocktablerow['stock_date'];
                        $stocktableprice  = $stocktablerow['stock_price'];

                        $finalpriceforstockpurchase = $stocktableprice * $stockvolume;

                        if($userwalletamount < $finalpriceforstockpurchase)
                        {
                            echo json_encode(array("status" => "0","message" => "Your Balance is Low"));
                        }
                        else
                        {
                            $stmt = $conn->prepare("INSERT INTO table_purchase_stocks(user_id,stock_name,stock_price,stock_volume,stock_total_price,purchased_date,purchased_ip,purchased_location,stock_status,stock_created_source) VALUES (:user_id,:stock_name,:stock_price,:stock_volume,:stock_total_price,:purchased_date,:purchased_ip,:purchased_location,:stock_status,:stock_created_source)");
                            $stmt->bindParam(':user_id', $usertableid);
                            $stmt->bindParam(':stock_name', $stocktablename);
                            $stmt->bindParam(':stock_price', $stocktableprice);
                            $stmt->bindParam(':stock_volume', $stockvolume);
                            $stmt->bindParam(':stock_total_price', $finalpriceforstockpurchase);
                            $stmt->bindParam(':purchased_date', $stockpurchaseddate);
                            $stmt->bindParam(':purchased_ip', $userip);
                            $stmt->bindParam(':purchased_location', $userlocation);
                            $stmt->bindParam(':stock_status', $userstatus);
                            $stmt->bindParam(':stock_created_source', $usercreatedsource);
                            if($stmt->execute())
                            {
                                $finalbalance = $userwalletamount -$finalpriceforstockpurchase;
                                $updatestmt = $conn->prepare("update table_users set user_wallet_amount = :user_wallet_amount, today_purchase = :today_purchase where id = :usertableid ");
                                $updatestmt->bindParam(':usertableid', $usertableid);
                                $updatestmt->bindParam(':user_wallet_amount', $finalbalance);
                                $updatestmt->bindParam(':today_purchase', $updatepurchase);
                                if($updatestmt->execute())
                                {
                                    echo json_encode(array("status" => "1","message" => "Stock Purchased Successfully"));
                                }
                                else
                                {
                                    echo json_encode(array("status" => "0","message" => "Something went wrong! Please try again later"));
                                }
                            }
                            else
                            {
                                echo json_encode(array("status" => "0","message" => "Something went wrong please try again"));
                            }
                        }
                    }
                    else
                    {
                        echo json_encode(array("status" => "0","message" => "Stock Not Found. Please Try Again Later"));
                    }
            }
		}
		else
		{
            echo json_encode(array("status" => "0","message" => "User Not Found"));
		}
	}
	catch(PDOException $e)
	{
        echo json_encode(array( "status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon. Please try after some time" ) );
	}
}
?>

<?php
session_start();
include "../../dbconfig.php";

if(empty($_POST["sellstockname"]) || empty($_POST["sellstockprice"]) || empty($_POST["sellstockvolume"]) || empty($_POST["sellstocktotalprice"]))
{
	echo json_encode(array("status" => "0","message" => "Please fill all details"));
}
else
{
	date_default_timezone_set('Indian/Maldives');
	$stockpurchaseddate = date("Y-m-d");
	$stockname =  trim(isset($_POST["sellstockname"]) ? $_POST["sellstockname"] : "");
	$stockprice =  trim(isset($_POST["sellstockprice"]) ? $_POST["sellstockprice"] : "");
	$stockvolume =  trim(isset($_POST["sellstockvolume"]) ? $_POST["sellstockvolume"] : "");
    $stocktotalprice =  trim(isset($_POST["sellstocktotalprice"]) ? $_POST["sellstocktotalprice"] : "");
    $stockmodalid =  trim(isset($_POST["sellstockmodalid"]) ? $_POST["sellstockmodalid"] : "");
    $inputuseremail =  trim(isset($_POST["sellinputuseremail"]) ? $_POST["sellinputuseremail"] : "");
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
                echo json_encode(array("status" => "0","message" => "Your Today Quota is Finished. Please Try again Tommorrow"));
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

                    $purchasestockstmt = $conn->prepare("SELECT user_id, stock_name, SUM(stock_volume) as totalpurchasedvolume from table_purchase_stocks where stock_name = :stock_name and user_id = :user_id GROUP BY stock_name");
                    $purchasestockstmt->bindParam(':stock_name', $stockname);
                    $purchasestockstmt->bindParam(':user_id', $usertableid);
                    $purchasestockstmt->execute();
                    if ($purchasestockstmt->rowcount() > 0)
                    {
                        $purchasestockrow = $purchasestockstmt->fetch();
                        $totalpurchasedvolume  = $purchasestockrow['totalpurchasedvolume'];



                        $sellstockstmt = $conn->prepare("SELECT user_id, stock_name, SUM(stock_volume) as totalsoldvolume from table_sell_stocks where stock_name = :stock_name and user_id = :user_id GROUP BY stock_name");
                        $sellstockstmt->bindParam(':stock_name', $stockname);
                        $sellstockstmt->bindParam(':user_id', $usertableid);
                        $sellstockstmt->execute();
                        if ($sellstockstmt->rowcount() > 0)
                        {
                            $sellstockrow = $sellstockstmt->fetch();
                            $totalsoldvolume  = $sellstockrow['totalsoldvolume'];



                            $availablestock = $totalpurchasedvolume - $totalsoldvolume;



                            if($availablestock < $stockvolume)
                            {
                                echo json_encode(array( "status" => "0","message" => "Entered Volume is Not Available in your Account. Please Enter Low Volume"));
                            }
                            else
                            {
                                $finalpriceforstockpurchase = $stocktableprice * $stockvolume;

                                $stmt = $conn->prepare("INSERT INTO table_sell_stocks(user_id,stock_name,stock_price,stock_volume,stock_total_price,sell_date,sell_ip,sell_location,sell_status,sell_created_source) VALUES (:user_id,:stock_name,:stock_price,:stock_volume,:stock_total_price,:sell_date,:sell_ip,:sell_location,:sell_status,:sell_created_source)");
                                $stmt->bindParam(':user_id', $usertableid);
                                $stmt->bindParam(':stock_name', $stocktablename);
                                $stmt->bindParam(':stock_price', $stocktableprice);
                                $stmt->bindParam(':stock_volume', $stockvolume);
                                $stmt->bindParam(':stock_total_price', $finalpriceforstockpurchase);
                                $stmt->bindParam(':sell_date', $stockpurchaseddate);
                                $stmt->bindParam(':sell_ip', $userip);
                                $stmt->bindParam(':sell_location', $userlocation);
                                $stmt->bindParam(':sell_status', $userstatus);
                                $stmt->bindParam(':sell_created_source', $usercreatedsource);
                                if($stmt->execute())
                                {
                                    $finalbalance = $userwalletamount + $finalpriceforstockpurchase;
                                    $updatestmt = $conn->prepare("update table_users set user_wallet_amount = :user_wallet_amount,today_purchase = :today_purchase where id = :usertableid ");
                                    $updatestmt->bindParam(':usertableid', $usertableid);
                                    $updatestmt->bindParam(':user_wallet_amount', $finalbalance);
                                    $updatestmt->bindParam(':today_purchase', $updatepurchase);
                                    if($updatestmt->execute())
                                    {
                                        echo json_encode(array("status" => "1","message" => "Stock Sold Successfully"));
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
                            $availablestock = $totalpurchasedvolume;

                            if($availablestock < $stockvolume)
                            {
                                echo json_encode(array( "status" => "0","message" => "Entered Volume is Not Available in your Account. Please Enter Low Volume"));
                            }
                            else
                            {
                                $finalpriceforstockpurchase = $stocktableprice * $stockvolume;

                                $stmt = $conn->prepare("INSERT INTO table_sell_stocks(user_id,stock_name,stock_price,stock_volume,stock_total_price,sell_date,sell_ip,sell_location,sell_status,sell_created_source) VALUES (:user_id,:stock_name,:stock_price,:stock_volume,:stock_total_price,:sell_date,:sell_ip,:sell_location,:sell_status,:sell_created_source)");
                                $stmt->bindParam(':user_id', $usertableid);
                                $stmt->bindParam(':stock_name', $stocktablename);
                                $stmt->bindParam(':stock_price', $stocktableprice);
                                $stmt->bindParam(':stock_volume', $stockvolume);
                                $stmt->bindParam(':stock_total_price', $finalpriceforstockpurchase);
                                $stmt->bindParam(':sell_date', $stockpurchaseddate);
                                $stmt->bindParam(':sell_ip', $userip);
                                $stmt->bindParam(':sell_location', $userlocation);
                                $stmt->bindParam(':sell_status', $userstatus);
                                $stmt->bindParam(':sell_created_source', $usercreatedsource);
                                if($stmt->execute())
                                {
                                    $finalbalance = $userwalletamount + $finalpriceforstockpurchase;
                                    $updatestmt = $conn->prepare("update table_users set user_wallet_amount = :user_wallet_amount,today_purchase = :today_purchase where id = :usertableid ");
                                    $updatestmt->bindParam(':usertableid', $usertableid);
                                    $updatestmt->bindParam(':user_wallet_amount', $finalbalance);
                                    $updatestmt->bindParam(':today_purchase', $updatepurchase);
                                    if($updatestmt->execute())
                                    {
                                        echo json_encode(array("status" => "1","message" => "Stock Sold Successfully"));
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
                    }
                    else
                    {
                        echo json_encode(array( "status" => "0","message" => "Please Purchase the Stock First"));
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

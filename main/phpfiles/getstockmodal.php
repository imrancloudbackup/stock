<?php
include "../../dbconfig.php";
if(isset($_POST['identify']))
{
    $identifybuyorsell = trim(isset($_POST["identify"]) ? $_POST["identify"] : "");

    if($identifybuyorsell == 1)
    {
        if(isset($_POST['row_id']))
        {
            $row_id=$_POST['row_id'];
            $usersessionemail = $_POST['usersessionemail'];
            try
            {
                $checkuserstmt = $conn->prepare("SELECT * from table_stocks where id = :id");
                $checkuserstmt->bindParam(':id', $row_id);
                $checkuserstmt->execute();
                if ($checkuserstmt->rowcount() > 0)
                {
                    $checkuserrow = $checkuserstmt->fetch();
                    $userstmt = $conn->prepare("SELECT id,user_name,user_wallet_amount,user_email,today_purchase from table_users where user_email = :user_email");
                    $userstmt->bindParam(':user_email', $usersessionemail);
                    $userstmt->execute();
                    if ($userstmt->rowcount() > 0)
                    {
                        $userrow = $userstmt->fetch();
                        $usertableid  = $userrow['id'];
                        $userwalletamount  = $userrow['user_wallet_amount'];

                        if($userwalletamount == null || $userwalletamount == "")
                        {
                            $userwalletamount = 0;
                        }

                        echo json_encode(array( "status" => "1","message" => "Stock Details Retrived!" , "data" => $checkuserrow,"userbalanceamount"=> $userwalletamount));
                    }
                    else
                    {
                        echo json_encode(array( "status" => "0","message" => "User not Found" ) );
                    }
                }
                else
                {
                    echo json_encode(array( "status" => "0","message" => "Stock Not Found" ) );
                }
            }
            catch(PDOException $e)
            {
                echo json_encode(array( "status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon. Please try after some time" ) );
            }
        }
        else
        {
            echo json_encode(array( "status" => "0","message" => "Currently Stock Not Available. Please try again later" ) );
        }
    }
    elseif($identifybuyorsell == 2)
    {
        if(isset($_POST['sellrow_id']))
        {
            $sellrow_id=$_POST['sellrow_id'];
            $sellusersessionemail = $_POST['sellusersessionemail'];
            try
            {
                $checkuserstmt1 = $conn->prepare("SELECT * from table_stocks where id = :id");
                $checkuserstmt1->bindParam(':id', $sellrow_id);
                $checkuserstmt1->execute();
                if ($checkuserstmt1->rowcount() > 0)
                {
                    $checkuserrow1 = $checkuserstmt1->fetch();
                    $sellstockname = $checkuserrow1['stock_name'];


                    $userstmt1 = $conn->prepare("SELECT id,user_name,user_wallet_amount,user_email,today_purchase from table_users where user_email = :user_email");
                    $userstmt1->bindParam(':user_email', $sellusersessionemail);
                    $userstmt1->execute();
                    if ($userstmt1->rowcount() > 0)
                    {
                        $userrow1 = $userstmt1->fetch();
                        $usertableid1  = $userrow1['id'];



                        $purchasestockstmt = $conn->prepare("SELECT user_id, stock_name, SUM(stock_volume) as totalpurchasedvolume from table_purchase_stocks where stock_name = :stock_name and user_id = :user_id GROUP BY :stock_name");
                        $purchasestockstmt->bindParam(':stock_name', $sellstockname);
                        $purchasestockstmt->bindParam(':user_id', $usertableid1);
                        $purchasestockstmt->execute();
                        if ($purchasestockstmt->rowcount() > 0)
                        {
                            $purchasestockrow = $purchasestockstmt->fetch();
                            $totalpurchasedvolume  = $purchasestockrow['totalpurchasedvolume'];

                            $sellstockstmt = $conn->prepare("SELECT user_id, stock_name, SUM(stock_volume) as totalpurchasedvolume from table_sell_stocks where stock_name = :stock_name and user_id = :user_id GROUP BY :stock_name");
                            $sellstockstmt->bindParam(':stock_name', $sellstockname);
                            $sellstockstmt->bindParam(':user_id', $usertableid1);
                            $sellstockstmt->execute();
                            if ($sellstockstmt->rowcount() > 0)
                            {
                                $sellstockrow = $sellstockstmt->fetch();
                                $totalsoldvolume  = $sellstockrow['totalpurchasedvolume'];

                                $availablestock = $totalpurchasedvolume - $totalsoldvolume;

                                echo json_encode(array( "status" => "1","message" => "Stock Details Retrived!" , "data" => $checkuserrow1,"availablestock"=> $availablestock));

                            }
                            else
                            {
                                echo json_encode(array( "status" => "1","message" => "Stock Details Retrived!" , "data" => $checkuserrow1,"availablestock"=> $totalpurchasedvolume));
                            }
                        }
                        else
                        {
                            echo json_encode(array( "status" => "1","message" => "Stock Details Retrived!" , "data" => $checkuserrow1,"availablestock"=> "0"));
                        }
                    }
                    else
                    {
                        echo json_encode(array( "status" => "0","message" => "User not Found" ) );
                    }
                }
                else
                {
                    echo json_encode(array( "status" => "0","message" => "Stock Not Found" ) );
                }
            }
            catch(PDOException $e)
            {
                echo json_encode(array( "status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon. Please try after some time" ) );
            }
        }
        else
        {
            echo json_encode(array( "status" => "0","message" => "Currently Stock Not Available. Please try again later" ) );
        }
    }
    else
    {
        echo json_encode(array( "status" => "0","message" => "Please select buy or Sell" ) );
    }
}
else
{
    echo json_encode(array( "status" => "0","message" => "Identification Failed" ) );
}
?>
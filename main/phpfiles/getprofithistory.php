<?php
include "../../dbconfig.php";

if(isset($_POST['startingvalue']) && isset($_POST['endingvalue']) && isset($_POST['usertableid']))
{
	$startingvalue = $_POST['startingvalue'];
	$endingvalue = $_POST['endingvalue'];
	$serialnumber = $_POST['serial'];
    $usertableid = $_POST['usertableid'];
	$output = "";


    try
    {
        $serialnumber = $serialnumber;
        $totalprofitorloss = 0;

        $finaltotalpurchaseprice = 0;
        $finaltotalsellprice = 0;

        $purchasehistorystmt = $conn->prepare("SELECT user_id, stock_name, SUM(stock_volume) as totalpurchasestockvolume, SUM(stock_total_price) as totalpurchasespent from table_purchase_stocks where user_id = :user_id GROUP BY stock_name");
        $purchasehistorystmt->bindParam(':user_id', $usertableid);
        $purchasehistorystmt->execute();
        if ($purchasehistorystmt->rowcount() > 0)
        {
            $purchasehistorystmtresult = $purchasehistorystmt->fetchAll();
            foreach ($purchasehistorystmtresult as $purchasehistorystmtrow)
            {
                $purchasestockname =  $purchasehistorystmtrow['stock_name'];
                $totalpurchaseamount = $purchasehistorystmtrow['totalpurchasespent'];
                $finaltotalpurchaseprice = $finaltotalpurchaseprice + $totalpurchaseamount;
                $output = $output."<tr>
                    <th scope='row'>".$serialnumber."</th>
                    <td>".$purchasehistorystmtrow['stock_name']."</td>
                    <td>".$purchasehistorystmtrow['totalpurchasestockvolume']."</td>
                    <td>".$totalpurchaseamount."</td>";


                $sellhistorystmt = $conn->prepare("SELECT user_id, stock_name, SUM(stock_volume) as totalsoldstockvolume, SUM(stock_total_price) as totalsoldspent from table_sell_stocks where user_id = :user_id and stock_name = :stock_name GROUP BY stock_name");
                $sellhistorystmt->bindParam(':user_id', $usertableid);
                $sellhistorystmt->bindParam(':stock_name', $purchasestockname);
                $sellhistorystmt->execute();
                if ($sellhistorystmt->rowcount() > 0)
                {
                    $sellhistoryrow = $sellhistorystmt->fetch();
                    $totalsellamount = $sellhistoryrow['totalsoldspent'];

                    $finaltotalsellprice = $finaltotalsellprice + $totalsellamount;

                    if($totalpurchaseamount <= $totalsellamount)
                    {
                        $totalprofit = $totalsellamount - $totalpurchaseamount;
                        $totalloss = "-";
                    }
                    else
                    {
                        $totalprofit = "-";
                        $totalloss =  $totalsellamount - $totalpurchaseamount;
                    }

                    $totaldifference = $totalsellamount - $totalpurchaseamount;
                    $output = $output."
                        <td>".$sellhistoryrow['totalsoldstockvolume']."</td>
                        <td>".$totalsellamount."</td>
                        <td>".number_format((float)$totalprofit,2)."</td>
                        <td>".number_format((float)$totalloss,2)."</td>
                    </tr>";

                }
                else
                {
                    $output = $output."
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>";
                }

                $serialnumber++;
            }


            $totalprofitorloss = $finaltotalsellprice - $finaltotalpurchaseprice;
            $totalprofitorloss = number_format((float)$totalprofitorloss,2);





            echo json_encode(array("status" => "1","message" => "Record fetched", "record"=>$output,"totalserialno"=> $serialnumber, "totalprofitorloss" => $totalprofitorloss ));
        }
        else
        {
            echo json_encode(array( "status" => "0","message" => "No Profit History Found" ) );
        }
    }
    catch(PDOException $e)
    {
        echo json_encode(array( "status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon. Please try after some time" ) );
    }

 /*   try
    {
        $serialnumber = $serialnumber;
        $stockstmt = $conn->prepare("SELECT * from table_stocks GROUP BY stock_name limit $startingvalue, $endingvalue");
        $stockstmt->execute();
        if ($stockstmt->rowcount() > 0)
        {
            $stockstmtresult = $stockstmt->fetchAll();
            foreach ($stockstmtresult as $stockstmtrow)
            {
                $stocktable_stockname = $stockstmtrow['stock_name'];

                $purchasehistorystmt = $conn->prepare("SELECT user_id, stock_name, SUM(stock_volume) as totalpurchasestockvolume, SUM(stock_total_price) as totalpurchasespent from table_purchase_stocks where user_id = :user_id and stock_name = :stock_name GROUP BY stock_name");
                $purchasehistorystmt->bindParam(':user_id', $usertableid);
                $purchasehistorystmt->bindParam(':stock_name', $stocktable_stockname);
                $purchasehistorystmt->execute();
                if ($purchasehistorystmt->rowcount() > 0)
                {
                    $purchasehistoryrow = $purchasehistorystmt->fetch();
                    $totalpurchaseamount = $purchasehistoryrow['totalpurchasespent'];
                    $output = $output."<tr>
                        <th scope='row'>".$serialnumber."</th>
                        <td>".$purchasehistoryrow['stock_name']."</td>
                        <td>".$purchasehistoryrow['totalpurchasestockvolume']."</td>
                        <td>".$totalpurchaseamount."</td>";

                    $sellhistorystmt = $conn->prepare("SELECT user_id, stock_name, SUM(stock_volume) as totalsoldstockvolume, SUM(stock_total_price) as totalsoldspent from table_sell_stocks where user_id = :user_id and stock_name = :stock_name GROUP BY stock_name");
                    $sellhistorystmt->bindParam(':user_id', $usertableid);
                    $sellhistorystmt->bindParam(':stock_name', $stocktable_stockname);
                    $sellhistorystmt->execute();
                    if ($sellhistorystmt->rowcount() > 0)
                    {
                        $sellhistoryrow = $sellhistorystmt->fetch();
                        $totalsellamount = $sellhistoryrow['totalsoldspent'];

                        if($totalpurchaseamount <= $totalsellamount)
                        {
                            $totalprofit = $totalsellamount - $totalpurchaseamount;
                            $totalloss = "-";
                        }
                        else
                        {
                            $totalprofit = "-";
                            $totalloss =  $totalsellamount - $totalpurchaseamount;
                        }

                        $totaldifference = $totalsellamount - $totalpurchaseamount;
                        $output = $output."
                            <td>".$sellhistoryrow['totalsoldstockvolume']."</td>
                            <td>".$totalsellamount."</td>
                            <td>".$totalprofit."</td>
                            <td>".$totalloss."</td>
                        </tr>";

                    }
                    else
                    {
                        $output = $output."
                            <td>"-"</td>
                            <td>"-"</td>
                            <td>"-"</td>
                            <td>"-"</td>
                        </tr>";
                    }
                }
                $serialnumber++;
            }

             echo json_encode(array("status" => "1","message" => "Record fetched", "record"=>$output,"totalserialno"=> $serialnumber));
        }
        else
        {
            echo json_encode(array( "status" => "0","message" => "Stock Not Found" ) );
        }
    }
    catch(PDOException $e)
    {
        echo json_encode(array( "status" => "0","message" => "Something went wrong Please try again later" ) );
    }*/
}
else
{
    echo json_encode(array( "status" => "0","message" => "User Not Found" ) );
}
?>
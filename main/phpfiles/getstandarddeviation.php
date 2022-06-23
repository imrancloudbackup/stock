<?php
include "../../dbconfig.php";

if(isset($_POST['startingvalue']) && isset($_POST['endingvalue']))
{
	$startingvalue = $_POST['startingvalue'];
	$endingvalue = $_POST['endingvalue'];
	$serialnumber = $_POST['serial'];
	$output = "";

    try
    {
        $serialnumber = $serialnumber;
        $stockstmt = $conn->prepare("SELECT stock_name, SUM(stock_price) as totalprice from table_stocks GROUP BY stock_name limit $startingvalue, $endingvalue");
        $stockstmt->execute();
        if ($stockstmt->rowcount() > 0)
        {
            $stockstmtresult = $stockstmt->fetchAll();
            foreach ($stockstmtresult as $stockstmtrow)
            {
                $stocktable_stockname = $stockstmtrow['stock_name'];
                $stocktable_totalprice = $stockstmtrow['totalprice'];

                $totalsquarevalue = 0;
                $totalcount = 0;

                $standarddeviationstmt = $conn->prepare("SELECT * FROM table_stocks where stock_name = :stock_name");
                $standarddeviationstmt->bindParam(':stock_name', $stocktable_stockname);
                $standarddeviationstmt->execute();
                if ($standarddeviationstmt->rowcount() > 0)
                {
                    $totalcount = $standarddeviationstmt->rowcount();
                    $finalmeanstock = $stocktable_totalprice / $totalcount;

                    $standarddeviationstmtresult = $standarddeviationstmt->fetchAll();
                    foreach ($standarddeviationstmtresult as $standarddeviationstmtrow)
                    {
                        $standardtable_stockname = $standarddeviationstmtrow['stock_name'];
                        $standardtable_stockprice = $standarddeviationstmtrow['stock_price'];

                        $xminusxbar = $standardtable_stockprice - $finalmeanstock;
                        $xminusxbarsquare = pow($xminusxbar,2);

                        $totalsquarevalue = $totalsquarevalue + $xminusxbarsquare;






                    }

                    $totalcountminusone = $totalcount - 1;
                    $sdstep1 = $totalsquarevalue / $totalcountminusone;
                    $sdstep2 = sqrt($sdstep1);



                }
                else
                {
                    echo json_encode(array( "status" => "0","message" => "Something went wrong Please try again later" ) );
                }

                $output = $output."<tr>
                <th scope='row'>".$serialnumber."</th>
                <td>".$standardtable_stockname."</td>
                <td>".$sdstep2."</td>
                </tr>";

                /*

                 $output = $output."<tr>
                <th scope='row'>".$serialnumber."</th>
                <td>".$standardtable_stockname."</td>
                <td>".$standardtable_stockprice."</td>
                <td>".$finalmeanstock."</td>
                <td>".$xminusxbar."</td>
                <td>".$xminusxbarsquare."</td>
                <td>".$totalsquarevalue."</td>
                <td>".$totalcountminusone."</td>
                <td>".$sdstep1."</td>
                <td>".$sdstep2."</td>
                </tr>";

                */

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
        echo json_encode(array( "status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon. Please try after some time" ) );
    }
}
else
{
    echo json_encode(array( "status" => "0","message" => "User Not Found" ) );
}
?>
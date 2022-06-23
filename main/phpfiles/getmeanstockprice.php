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

                $standarddeviationstmt = $conn->prepare("SELECT * FROM table_stocks where stock_name = :stock_name");
                $standarddeviationstmt->bindParam(':stock_name', $stocktable_stockname);
                $standarddeviationstmt->execute();
                if ($standarddeviationstmt->rowcount() > 0)
                {
                    $totalcount = $standarddeviationstmt->rowcount();
                    $finalmeanstock = $stocktable_totalprice / $totalcount;


                    $output = $output."<tr>
                        <th scope='row'>".$serialnumber."</th>
                        <td>".$stocktable_stockname."</td>
                        <td>".$finalmeanstock."</td>
                        </tr>";
                }
                else
                {
                    echo json_encode(array( "status" => "0","message" => "Something went wrong Please try again later" ) );
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
        echo json_encode(array( "status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon. Please try after some time" ) );
    }
}
else
{
    echo json_encode(array( "status" => "0","message" => "User Not Found" ) );
}
?>
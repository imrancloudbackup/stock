<?php
include "../../dbconfig.php";

if(isset($_POST['startingvalue']) && isset($_POST['endingvalue']) && isset($_POST['usertableid']))
{
	$startingvalue = $_POST['startingvalue'];
	$endingvalue = $_POST['endingvalue'];
	$serialnumber = $_POST['serial'];
    $usertableid = $_POST['usertableid'];
    $totalpurchaseprice = $_POST['totalpurchaseprice'];
	$output = "";

	try
	{
		$purchasehistorystmt = $conn->prepare("SELECT * from table_purchase_stocks where user_id = :user_id limit $startingvalue, $endingvalue");
		$purchasehistorystmt->bindParam(':user_id', $usertableid);
		$purchasehistorystmt->execute();
		if ($purchasehistorystmt->rowcount() > 0)
		{
			$purchasehistoryresult = $purchasehistorystmt->fetchAll();
			$serialnumber = $serialnumber;
			$totalstockprice = $totalpurchaseprice;
			foreach ($purchasehistoryresult as $purchasehistoryrow)
			{
				$purchaseddate = $purchasehistoryrow['purchased_date'];
				$purchaseddate = date("d-m-Y", strtotime($purchaseddate));
				$totalstockprice = $totalstockprice + $purchasehistoryrow['stock_total_price'];

				$output = $output."<tr>
							<th scope='row'>".$serialnumber."</th>
							<td>".$purchaseddate."</td>
							<td>".$purchasehistoryrow['stock_name']."</td>
							<td>".$purchasehistoryrow['stock_price']."</td>
							<td>".$purchasehistoryrow['stock_volume']."</td>
							<td>".$purchasehistoryrow['stock_total_price']."</td>
						</tr>";
				$serialnumber++;
			}

			echo json_encode(array("status" => "1","message" => "Record fetched", "record"=>$output,"totalserialno"=> $serialnumber, "totalpurchaseprice" => $totalstockprice ));
		}
		else
		{
			echo json_encode(array("status" => "0","message" => "No Records Found"));
		}
	}
	catch(PDOException $e)
	{
		echo json_encode(array( "status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon. Please try after some time" ) );
	}
}
else
{
	echo json_encode(array("status" => "0","message" => "No Records Found"));
}
?>
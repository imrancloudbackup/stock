<?php
include "../../dbconfig.php";

if(isset($_POST['startingvalue']) && isset($_POST['endingvalue']) && isset($_POST['usertableid']))
{
	$startingvalue = $_POST['startingvalue'];
	$endingvalue = $_POST['endingvalue'];
	$serialnumber = $_POST['serial'];
    $usertableid = $_POST['usertableid'];
    $totalsoldprice = $_POST['totalsoldprice'];
	$output = "";

	try
	{
		$sellhistorystmt = $conn->prepare("SELECT * from table_sell_stocks where user_id = :user_id limit $startingvalue, $endingvalue");
		$sellhistorystmt->bindParam(':user_id', $usertableid);
		$sellhistorystmt->execute();
		if ($sellhistorystmt->rowcount() > 0)
		{
			$sellhistoryresult = $sellhistorystmt->fetchAll();
			$serialnumber = $serialnumber;
			$totalstockprice = $totalsoldprice;
			foreach ($sellhistoryresult as $sellhistoryrow)
			{
				$selldate = $sellhistoryrow['sell_date'];
				$selldate = date("d-m-Y", strtotime($selldate));
				$totalstockprice = $totalstockprice + $sellhistoryrow['stock_total_price'];

				$output = $output."<tr>
							<th scope='row'>".$serialnumber."</th>
							<td>".$selldate."</td>
							<td>".$sellhistoryrow['stock_name']."</td>
							<td>".$sellhistoryrow['stock_price']."</td>
							<td>".$sellhistoryrow['stock_volume']."</td>
							<td>".$sellhistoryrow['stock_total_price']."</td>
						  </tr>";
				$serialnumber++;
			}

			echo json_encode(array("status" => "1","message" => "Record fetched", "record"=>$output,"totalserialno"=> $serialnumber, "totalsoldprice" => $totalstockprice ));
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
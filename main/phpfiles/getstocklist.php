<?php
include "../../dbconfig.php";

if(isset($_POST['startingvalue']) && isset($_POST['endingvalue']))
{
	$startingvalue = $_POST['startingvalue'];
	$endingvalue = $_POST['endingvalue'];
	$serialnumber = $_POST['serial'];

	try
	{
		$stocklistres = $conn->prepare("SELECT id,stock_date,stock_name,stock_price from table_stocks ORDER BY stock_date ASC LIMIT $startingvalue, $endingvalue");
		$stocklistres->execute();
		if ($stocklistres->rowcount() > 0)
		{
			$stocklistresult = $stocklistres->fetchAll();
			$serialnumber = $serialnumber;
			foreach ($stocklistresult as $stocklistrow)
			{
				$finalstockdate = $stocklistrow['stock_date'];
				$finalstockdate = date("d-m-Y", strtotime($finalstockdate));
				$output = $output."<tr>
							<th scope='row'>".$serialnumber."</th>
							<td>".$finalstockdate."</td>
							<td>".$stocklistrow['stock_name']."</td>
							<td>".$stocklistrow['stock_price']."</td>
							<td class='text-center'><button type='button' class='btn btn-rounded btn-success' onclick='buystocks(\"".$stocklistrow['id']."\")'>Buy</button></td>
							<td class='text-center'><button type='button' class='btn btn-rounded btn-danger' onclick='sellstocks(\"".$stocklistrow['id']."\")'>Sell</button></td>
						  </tr>";
				$serialnumber++;
			}

			echo json_encode(array("status" => "1","message" => "Record fetched", "record"=>$output,"totalserialno"=> $serialnumber));
		}
		else
		{
			$stocklistres1 = $conn->prepare("SELECT id from table_stocks ORDER BY id ASC");
			$stocklistres1->execute();
			if ($stocklistres1->rowcount() > 0)
			{
				echo json_encode(array("status" => "0","message" => "No Records Found","record"=>""));
			}
			else
			{
				$output = $output."<tr>
				<td colspan='6' class='text-center'>Stock List not Available. Please click the link to upload the file. <a href='upload_stock.php'>Upload Stock</a></td>
				</tr>";
				echo json_encode(array("status" => "0","message" => "No Records Found","record"=>$output));
			}

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
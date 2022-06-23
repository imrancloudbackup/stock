<?php
include "../../dbconfig.php";
if($_FILES['file']['size'] == 0)
{
	echo json_encode(array("status" => "0","message" => "Please Upload The File"));
}
else
{
    try
	{
		if($_FILES["file"]["name"] != '')
		{
			$csv = array();
			 $allowed_extension = array('xls', 'csv', 'xlsx');
			 $file_array = explode(".", $_FILES["file"]["name"]);
			 $file_extension = end($file_array);
			 if(in_array($file_extension, $allowed_extension))
			 {
				 $target_dir = "../uploads/";
				 $temp = explode(".", $_FILES["file"]["name"]);
				 $newfilename = round(microtime(true)) . '.' . end($temp);
				 $target_file = $target_dir . $newfilename;
				 if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file))
				 {
					$file = $_FILES['file']['tmp_name'];
					$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/stock/main/uploads/'.$newfilename, "r");
					try
					{
						set_time_limit(0);

						  $row = 0;

						fgets($handle);

						while (($data = fgetcsv($handle, 10000, ',')) !== FALSE)
						{
							$col_count = count($data);

							$idrow = $data[0];
							$daterow =  $data[1];
							$namerow =  $data[2];
							$pricerow =  $data[3];

							$finalstockdate = $daterow;
							$finalstockdatehidden = date("Y-m-d", strtotime($daterow));
							$finalstockname = strtoupper($namerow);
							$finalstockprice = $pricerow;



							$stmt = $conn->prepare("INSERT INTO table_stocks(stock_date,stock_name,stock_price) VALUES (:stock_date,:stock_name,:stock_price)");
							$stmt->bindParam(':stock_date', $finalstockdatehidden);
							$stmt->bindParam(':stock_name', $finalstockname);
							$stmt->bindParam(':stock_price', $finalstockprice);

							$stmt->execute();
							$row++;
						}

						fclose($handle);

						echo json_encode(array("status" => "1","message" => "File is Uploaded Successfully"));

					}
					catch(PDOException $e)
					{
						echo json_encode(array("status" => "0","message" => "Failed1"));
					}
				 }
				 else
				 {
					echo json_encode(array("status" => "0","message" => "Failed2"));
				 }
			 }
			 else
			 {
				echo json_encode(array("status" => "0","message" => "Only .xls .csv or .xlsx file allowed"));
			 }
		}
		else
		{
			 echo json_encode(array("status" => "0","message" => "Please Upload The File"));
		}
	}
	catch(PDOException $e)
    {
        echo json_encode(array( "status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon. Please try after some time" ) );
    }
}
?>
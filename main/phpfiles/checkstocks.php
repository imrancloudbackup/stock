<?php
include "../../dbconfig.php";

if(isset($_POST['stocknamefilter']) && isset($_POST['daterangefilter']))
{
    $stocknamefilter =  trim(isset($_POST["stocknamefilter"]) ? $_POST["stocknamefilter"] : "");
    $daterangefilter =  isset($_POST["daterangefilter"]) ? $_POST["daterangefilter"] : "";
	$serialnumber = 1;
	$output = "";

    $middlestring = "-";

    $a = array();


    if(strpos($daterangefilter, $middlestring) !== false)
    {
        $splitingdate = explode("-", $daterangefilter);
        $fromdate = $splitingdate[0];
        $todate = $splitingdate[1];

        $fromdate = trim(str_replace ("/", "-", $fromdate));

        $todate = trim(str_replace ("/", "-", $todate));

        $fromdate = date("Y-m-d", strtotime($fromdate));

        $todate = date("Y-m-d", strtotime($todate));

        try
        {
            $stocklistres = $conn->prepare("SELECT * from table_stocks WHERE stock_name = :stock_name and stock_date BETWEEN :fromdate AND :todate ORDER BY stock_date ASC");
            $stocklistres->bindParam(':stock_name', $stocknamefilter);
            $stocklistres->bindParam(':fromdate', $fromdate);
            $stocklistres->bindParam(':todate', $todate);
            $stocklistres->execute();

            if ($stocklistres->rowcount() > 0)
            {
                $stocklistresult = $stocklistres->fetchAll();
                $stocklisttitle = "Filtered Stock List";

                foreach($stocklistresult as $stocklistrow)
                {
                    array_push($a,$stocklistrow['stock_price']);
                }

                $max_value = max($a);
                $min_value = min($a);

                foreach ($stocklistresult as $stocklistrow)
                {
                    $finalstockdate = $stocklistrow['stock_date'];
                    $finalstockdate = date("d-m-Y", strtotime($finalstockdate));
                    $stocklistprice = $stocklistrow['stock_price'];

                    if($stocklistrow['stock_price'] == $min_value)
                    {
                         $classname = "greenclass";
                         $blinktext = "Best date to buy";
                    }
                    else if($stocklistrow['stock_price'] == $max_value)
                    {
                         $classname = "redclass";
                         $blinktext = "Best date to sell";
                    }
                    else
                    {
                         $classname = "";
                         $blinktext = "";
                    }

                    $output = $output."<tr>
                                <th scope='row'>".$serialnumber."</th>
                                <td>".$finalstockdate."</td>
                                <td>".$stocklistrow['stock_name']."</td>
                                <td>".$stocklistrow['stock_price']."</td>
                                <td class='text-center'><button type='button' class='btn btn-rounded btn-success' onclick='buystocks(\"".$stocklistrow['id']."\")'>Buy</button></td>
                                <td class='text-center'><button type='button' class='btn btn-rounded btn-danger' onclick='sellstocks(\"".$stocklistrow['id']."\")'>Sell</button></td>
                                <td class='$classname'>".$blinktext."</td>
                                </tr>";

                    $serialnumber++;
                }

                echo json_encode(array("status" => "1","message" => "Record fetched", "record"=>$output,"stocklisttitle"=>$stocklisttitle));
            }
            else
            {
                $stocklistres1 = $conn->prepare("SELECT * from table_stocks WHERE stock_name = :stock_name and stock_date <= :todate ORDER BY stock_date DESC LIMIT 1 ");
                $stocklistres1->bindParam(':stock_name', $stocknamefilter);
                $stocklistres1->bindParam(':todate', $todate);
                $stocklistres1->execute();

                if ($stocklistres1->rowcount() > 0)
                {
                    $stocklistresult1 = $stocklistres1->fetchAll();
                    $stocklisttitle = "Stock List not available for the selected dates(Below list is suggested Stock List)";

                    foreach ($stocklistresult1 as $stocklistrow1)
                    {
                        $finalstockdate1 = $stocklistrow1['stock_date'];
                        $finalstockdate1 = date("d-m-Y", strtotime($finalstockdate1));

                        $output = $output."<tr>
                                    <th scope='row'>".$serialnumber."</th>
                                    <td>".$finalstockdate1."</td>
                                    <td>".$stocklistrow1['stock_name']."</td>
                                    <td>".$stocklistrow1['stock_price']."</td>
                                    <td class='text-center'><button type='button' class='btn btn-rounded btn-success' onclick='buystocks(\"".$stocklistrow1['id']."\")'>Buy</button></td>
                                    <td class='text-center'><button type='button' class='btn btn-rounded btn-danger' onclick='sellstocks(\"".$stocklistrow1['id']."\")'>Sell</button></td>
                                </tr>";
                        $serialnumber++;
                    }

                    echo json_encode(array("status" => "2","message" => "Record fetched", "record"=>$output,"stocklisttitle"=>$stocklisttitle));
                }
                else
                {
                    echo json_encode(array("status" => "0","message" => "No Records Found1"));
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
        echo json_encode(array("status" => "0","message" => "Please Check From and To Date"));
    }
}
else
{
	echo json_encode(array("status" => "0","message" => "No Records Found"));
}
?>
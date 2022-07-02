<?php
include "../../dbconfig.php";

if(isset($_POST['stocknamefilter']) && isset($_POST['daterangefilter']))
{
    $stocknamefilter =  trim(isset($_POST["stocknamefilter"]) ? $_POST["stocknamefilter"] : "");
    $daterangefilter =  isset($_POST["daterangefilter"]) ? $_POST["daterangefilter"] : "";
	$filteruserid =  isset($_POST["filteruserid"]) ? $_POST["filteruserid"] : "";
	$serialnumber = 1;

    $middlestring = "-";

    $a = array();
	$b = array();


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

				$stocklistres10 = $conn->prepare("SELECT * from table_stocks WHERE stock_name = :stock_name and stock_date BETWEEN :fromdate AND :todate ORDER BY stock_price DESC LIMIT 1");
				$stocklistres10->bindParam(':stock_name', $stocknamefilter);
				$stocklistres10->bindParam(':fromdate', $fromdate);
				$stocklistres10->bindParam(':todate', $todate);
				$stocklistres10->execute();



				if ($stocklistres10->rowcount() > 0)
				{

					$stocklistresrow10 = $stocklistres10->fetch();
                    $higheststockprice  = $stocklistresrow10['stock_price'];
					$higheststockpricedate  = $stocklistresrow10['stock_date'];
					$higheststockpriceid  = $stocklistresrow10['id'];

					$stocklistres20 = $conn->prepare("SELECT * from table_stocks WHERE stock_name = :stock_name and stock_date < :stock_date AND stock_price < :stock_price AND stock_date BETWEEN :fromdate AND :todate ORDER BY stock_price ASC LIMIT 1");
					$stocklistres20->bindParam(':stock_name', $stocknamefilter);
					$stocklistres20->bindParam(':stock_date', $higheststockpricedate);
					$stocklistres20->bindParam(':stock_price', $higheststockprice);
					$stocklistres20->bindParam(':fromdate', $fromdate);
					$stocklistres20->bindParam(':todate', $todate);
					$stocklistres20->execute();



					if ($stocklistres20->rowcount() > 0)
					{

						$stocklistresrow20 = $stocklistres20->fetch();
						$loweststockprice  = $stocklistresrow20['stock_price'];
						$loweststockpricedate  = $stocklistresrow20['stock_date'];
						$loweststockpriceid  = $stocklistresrow20['id'];

						$max_value = $higheststockprice;
						$min_value = $loweststockprice;

						$minvalueset = 0;
						$maxvalueset = 0;

						foreach ($stocklistresult as $stocklistrow)
						{
							$finalstockdate = $stocklistrow['stock_date'];
							$finalstockdate = date("d-m-Y", strtotime($finalstockdate));
							$stocklistprice = $stocklistrow['stock_price'];


							if($stocklistrow['stock_price'] == $min_value)
							{
								if($minvalueset == 0)
								{
								 $classname = "greenclass";
								 $blinktext = "Best date to buy";
								 $minvalueset = 1;
								}
								else
								{
									$classname = "";
									$blinktext = "";
								}
							}
							else if($stocklistrow['stock_price'] == $max_value)
							{
								if($maxvalueset == 0)
								{
								 $classname = "redclass";
								 $blinktext = "Best date to sell";
								 $maxvalueset = 1;
								}
								else
								{
									$classname = "";
									$blinktext = "";
								}
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

						$stocklistres30 = $conn->prepare("SELECT * from table_stocks WHERE stock_name = :stock_name and stock_price < :stock_price AND stock_date BETWEEN :fromdate AND :todate  ORDER BY stock_price ASC LIMIT 1");
						$stocklistres30->bindParam(':stock_name', $stocknamefilter);
						$stocklistres30->bindParam(':stock_price', $higheststockprice);
						$stocklistres30->bindParam(':fromdate', $fromdate);
						$stocklistres30->bindParam(':todate', $todate);
						$stocklistres30->execute();

						if ($stocklistres30->rowcount() > 0)
						{

							$stocklistresrow30 = $stocklistres30->fetch();
							$secondlowestprice  = $stocklistresrow30['stock_price'];
							$secondlowestpricedate  = $stocklistresrow30['stock_date'];
							$secondlowestpriceid  = $stocklistresrow30['id'];

							$stocklistres40 = $conn->prepare("SELECT * from table_stocks WHERE stock_name = :stock_name and stock_price > :stock_price AND stock_date > :stock_date AND stock_date BETWEEN :fromdate AND :todate ORDER BY stock_price desc LIMIT 1");
							$stocklistres40->bindParam(':stock_name', $stocknamefilter);
							$stocklistres40->bindParam(':stock_price', $secondlowestprice);
							$stocklistres40->bindParam(':stock_date', $secondlowestpricedate);
							$stocklistres40->bindParam(':fromdate', $fromdate);
							$stocklistres40->bindParam(':todate', $todate);
							$stocklistres40->execute();

							if ($stocklistres40->rowcount() > 0)
							{

								$stocklistresrow40 = $stocklistres40->fetch();
								$secondhighestprice  = $stocklistresrow40['stock_price'];
								$secondhighestpricedate  = $stocklistresrow40['stock_date'];
								$secondhighestpriceid  = $stocklistresrow40['id'];


								$max_value = $secondhighestprice;
								$min_value = $secondlowestprice;

								$minvalueset = 0;
								$maxvalueset = 0;

								foreach ($stocklistresult as $stocklistrow)
								{
									$finalstockdate = $stocklistrow['stock_date'];
									$finalstockdate = date("d-m-Y", strtotime($finalstockdate));
									$stocklistprice = $stocklistrow['stock_price'];

									if($stocklistrow['stock_price'] == $min_value)
									{
										if($minvalueset == 0)
										{
										 $classname = "greenclass";
										 $blinktext = "Best date to buy";
										 $minvalueset = 1;
										}
										else
										{
											$classname = "";
											$blinktext = "";
										}
									}
									else if($stocklistrow['stock_price'] == $max_value)
									{
										if($maxvalueset == 0)
										{
										 $classname = "redclass";
										 $blinktext = "Best date to sell";
										 $maxvalueset = 1;
										}
										else
										{
											$classname = "";
											$blinktext = "";
										}
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
								$max_value = $higheststockprice;
								$min_value = $secondlowestprice;

								$minvalueset = 0;
								$maxvalueset = 0;

								foreach ($stocklistresult as $stocklistrow)
								{
									$finalstockdate = $stocklistrow['stock_date'];
									$finalstockdate = date("d-m-Y", strtotime($finalstockdate));
									$stocklistprice = $stocklistrow['stock_price'];

									if($stocklistrow['stock_price'] == $max_value)
									{
										if($maxvalueset == 0)
										{
										 $classname = "greenclass";
										 $blinktext = "Best date to buy to minimize the loss";
										 $maxvalueset = 1;
										}
										else
										{
											$classname = "";
											$blinktext = "";
										}
									}
									else if($stocklistrow['stock_price'] == $min_value)
									{
										if($minvalueset == 0)
										{
										 $classname = "redclass";
										 $blinktext = "Best date to sell to minimize the loss";
										 $minvalueset = 1;
										}
										else
										{
											$classname = "";
											$blinktext = "";
										}
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
						}
						else
						{
							$max_value = 0;
							$min_value = 0;

							$minvalueset = 0;
							$maxvalueset = 0;

							foreach ($stocklistresult as $stocklistrow)
							{
								$finalstockdate = $stocklistrow['stock_date'];
								$finalstockdate = date("d-m-Y", strtotime($finalstockdate));
								$stocklistprice = $stocklistrow['stock_price'];

								if($stocklistrow['stock_price'] == $min_value)
								{
									if($minvalueset == 0)
									{
									 $classname = "greenclass";
									 $blinktext = "Best date to buy";
									 $minvalueset = 1;
									}
									else
									{
										$classname = "";
										$blinktext = "";
									}
								}
								else if($stocklistrow['stock_price'] == $max_value)
								{
									if($maxvalueset == 0)
									{
									 $classname = "redclass";
									 $blinktext = "Best date to sell";
									 $maxvalueset = 1;
									}
									else
									{
										$classname = "";
										$blinktext = "";
									}
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
					}
				}
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
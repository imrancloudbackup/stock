<?php
include "../dbconfig.php";
$todaypurchase = 0;
try
{
    $updatestmt = $conn->prepare("update table_users set today_purchase = :todaypurchase");
    $updatestmt->bindParam(':todaypurchase', $todaypurchase);
    if($updatestmt->execute())
    {
        if ($updatestmt->rowcount() > 0)
        {
            echo json_encode(array("status" => "1","message" => "Users Table Today Purchase Reset Successfully"));
        }
        else
        {
            echo json_encode(array("status" => "0","message" => "Cron Update Failed"));
        }
    }
    else
    {
        echo json_encode(array("status" => "0","message" => "Something went wrong! Please try again later"));
    }
}
catch(PDOException $e)
{
    echo json_encode(array("status" => "0","message" => "Fetch Failed"));
}

?>
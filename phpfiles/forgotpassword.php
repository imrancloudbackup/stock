<?php
session_start();
include '../dbconfig.php';
if (empty($_POST['recoveremail']))
{
   echo json_encode(array("status" => "0","message" => "Please fill all details"));
}
else
{
	$useremail =  trim(isset($_POST["email"]) ? $_POST["email"] : "");
	date_default_timezone_set('Indian/Maldives');
	$created = date("Y-m-d H:i:s");
	$expire_date = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($created)));
	try
	{
		$checkuserstmt = $conn->prepare("SELECT user_email from table_users where user_email = :user_email");
		$checkuserstmt->bindParam(':user_email', $useremail);
		$checkuserstmt->execute();
		if ($checkuserstmt->rowcount() > 0)
		{
			$userrow = $checkuserstmt->fetch();
			$fusername  = $userrow['user_name'];
			$fuseremail = $userrow['user_email'];

			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$token = substr(str_shuffle($permitted_chars), 0, 50);
			$recoveryuseremail = base64url_encode("imran98.jmc@gmail.com");
			$passwordresetlink = "https://localhost/stock/resetpassword.php?token=".$token."&u=".$recoveryuseremail;
			$stmt = $conn->prepare("update table_users set security_token = :security_token,expiry_time = :expiry_time where user_email = :user_email ");
			$stmt->bindParam(':security_token', $token);
			$stmt->bindParam(':expiry_time', $expire_date);
			$stmt->bindParam(':user_email', $fuseremail);
			if($stmt->execute())
			{
				$from = "Stock <no-reply@stock.com>";
				$to = $fuseremail;
				$subject = "Stock:Forgot password recovery";
				$message = "
				<div dir='ltr'>
				<br><br>
				<div class='gmail_quote'>
					<div>
						<table bgcolor='' border='0' cellpadding='0' cellspacing='0' width='100%'>
							<tbody>
							<tr>
								<td bgcolor='' class='m_2114530173431233196section-padding'>
									<table align='center' border='0' cellpadding='0' cellspacing='0' class='m_2114530173431233196wrapper'>
										<tbody>
										<tr>
											<td class='m_2114530173431233196header-mobile-wrapper'>
												<table border='0' cellpadding='0' cellspacing='0' width='100%'>
													<tbody>
													<tr>
														<td width='100%'>
															<table border='0' cellpadding='0' cellspacing='0' style='border-bottom:4px solid #ececec;margin:auto'>
																<tbody>
																<tr>
																	<td style='padding:20px 50px 15px'><a href='https://localhost/stock/' target='_blank'>
																	<img width='128' style='display:inline;float:none;text-align:center;width:128px' src='https://localhost/stock/images/logo.jpg' alt='Stock'>
																		</a>
																	</td>
																</tr>
																</tbody>
															</table>
														</td>
													</tr>
													</tbody>
												</table>
											</td>
										</tr>
										</tbody>
									</table>
								</td>
							</tr>
							</tbody>
						</table>
						<table bgcolor='' border='0' cellpadding='0' cellspacing='0' width='100%'>
							<tbody>
							<tr>
								<td bgcolor='' class='m_2114530173431233196section-padding'>
									<table align='center' border='0' cellpadding='0' cellspacing='0' class='m_2114530173431233196wrapper' style='width:70%;text-align:left'>
										<tbody>
										<tr>
											<td style='padding-top:20px'><span style='font-family:&#39;Helvetica Neue&#39;,Helvetica,Arial,sans-serif;font-size:17px;color:#000000;line-height:24px;font-weight:500'>Hi $fusername,</span>                            </td>
										</tr>
										<tr>
											<td style='padding-top:5px;padding-bottom:20px'>                                <span style='font-family:&#39;Helvetica Neue&#39;,Helvetica,Arial,sans-serif;font-size:15px;color:#4a4a4a;font-weight:normal;line-height:21px'>Use the below link to reset your password<br> <a href='$passwordresetlink'>Click here to change your password</a> <br>for your TE visitors account<br><br>  Please do not share this link with anyone for security reasons. This link is valid for next 1 hour.<br><br></span>                            </td>
										</tr>
										</tbody>
									</table>
								</td>
							</tr>
							</tbody>
						</table>
						<table bgcolor='' border='0' cellpadding='0' cellspacing='0' width='100%'>
							<tbody>
							<tr>
								<td bgcolor='' class='m_2114530173431233196section-padding'><br>                 </td>
							</tr>
							</tbody>
						</table>
						<table align='center' border='0' cellpadding='0' width='100%' cellspacing='0' style='background:rgb(238,238,238)'>
							<tbody>
							<tr>
								<td style='padding-top:25px'>                                <span style='font-family:&#39;Helvetica Neue&#39;,Helvetica,Arial,sans-serif;font-size:12px;color:rgb(131,131,131);line-height:16px;text-align:center;display:block'>©Stock 2022</span>                            </td>
							</tr>
							<tr>
								<td style='padding-bottom:25px;padding-top:5px'>                                <a href='https://localhost/stock/' style='font-family:&#39;Helvetica Neue&#39;,Helvetica,Arial,sans-serif;font-size:13px;color:rgb(68,138,255);line-height:16px;text-align:center;display:block;text-decoration:none' target='_blank'>www.stock.com</a>                            </td>
							</tr>
							</tbody>
						</table>
					</div>
					<p> <br></p>

				</div>
				</div>
				";

				$headers = "MIME-Version: 1.0" . "\r\n";

				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				$headers .= 'From:'.$from."\r\n";

				mail($to,$subject,$message,$headers);

			}
			else
			{
				echo json_encode(array("status" => "0","message" => "Something went wrong please try again"));
			}
		}
		else
		{
			echo json_encode(array("status" => "0","message" => "Email Not Found!. Please Create a New Account to Continue."));
		}
	}
	catch(PDOException $e)
	{
		echo json_encode(array("status" => "0","message" => "Sorry for the inconvenience. We will fix the problem soon"));
	}
}

function base64url_encode($data, $pad = null)
{
    $data = str_replace(array('+', '/'), array('-', '_'), base64_encode($data));
    if (!$pad)
	{
        $data = rtrim($data, '=');
    }
    return $data;
}

?>


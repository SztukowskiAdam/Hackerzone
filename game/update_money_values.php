<?php

session_start();

$userId = $_SESSION['id'];
$userMoney = $_SESSION['money'];
$userVipMoney = $_SESSION['vipmoney'];

	$allGood = true;

	//sprawdzanie id i expa
	if(intval($userMoney) != $userMoney or intval($userId) != $userId or intval($userVipMoney) != $userVipMoney) $allGood = false;
	if(!is_numeric($userVipMoney) or !is_numeric($userId) or !is_numeric($userMoney)) $allGood = false;
	if($userId <= 0 or $userMoney < 0 or $userVipMoney < 0) $allGood = false;
	if(is_float($userMoney) or is_float($userId) or is_float($userVipMoney)) $allGood = false;

if($allGood)
{
	require_once "../connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);

	try
	{
		$db_connect= new mysqli($host, $db_user, $db_password, $db_name);

		if($db_connect->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());	
			$allGood = false;			
		}
		else
		{
			$result = $db_connect -> query("UPDATE uzytkownicy SET money = '$userMoney', vipmoney = '$userVipMoney' WHERE id = '$userId'");
			if(!$result)
			{
				throw new Exception($db_connect->error);
			}
			else
			{
				echo '<script type="text/javascript"> $("#vipmoney_values").text("'.$_SESSION['vipmoney'].'"); </script>';
			}
		}	

	}
	catch(Exception $e)
	{
		echo $e;
	}

echo $_SESSION['money'];
}
else
{
	header('Location: character.php');
}
?>
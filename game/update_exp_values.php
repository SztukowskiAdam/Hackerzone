<?php

session_start();

$userId = $_SESSION['id'];
$userExp = $_SESSION['exp'];
$needExp = $_SESSION['needexp'];

	$allGood = true;

	//sprawdzanie id i expa
	if(intval($userExp) != $userExp or intval($userId) != $userId) $allGood = false;
	if(!is_numeric($userExp) or !is_numeric($userId)) $allGood = false;
	if($userExp < 0 or $userId <= 0) $allGood = false;
	if(is_float($userExp) or is_float($userId)) $allGood = false;

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
			if($userExp >= $needExp)
			{
				$_SESSION['level']++;
				$level = $_SESSION['level'];
				$_SESSION['exp'] = $userExp - $needExp;
				$userExp = $_SESSION['exp'];

				$result = $db_connect -> query("SELECT exp FROM experience WHERE id = '$level'");
				if(!$result)
				{
					throw new Exception($db_connect->error);
				}
				else
				{
					$line = $result -> fetch_assoc();
					$_SESSION['needexp'] = $line['exp'];
				}

				$result = $db_connect -> query("UPDATE uzytkownicy SET level = '$level' WHERE id = '$userId'");
				if(!$result)
				{
					throw new Exception($db_connect->error);
				}
				echo '<script type="text/javascript"> $("#level_values").text("Level: '.$_SESSION['level'].'"); </script>';
			}
			
			$result = $db_connect -> query("UPDATE uzytkownicy SET exp = '$userExp' WHERE id = '$userId'");
			if(!$result)
			{
				throw new Exception($db_connect->error);
			}

			$db_connect -> close();
		}
	}
	catch(Exception $e)
	{
		echo $e;
	}

echo "Exp: ".$_SESSION['exp']." / ".$_SESSION['needexp'];
}
else
{
	header('Location: character.php');
}
?>
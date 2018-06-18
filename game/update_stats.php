
<?php
	session_start();
	require_once "../connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);

	$allGood = true;
	$id = $_SESSION['id'];

	if(intval($id) != $id) $allGood = false;
	if(!is_numeric($id)) $allGood = false;
	if($id <= 0) $allGood = false;
	if(is_float($id)) $allGood = false;

if($allGood)
{
	try
	{
		$db_connect= new mysqli($host, $db_user, $db_password, $db_name);

		if($db_connect->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());			
		}
		else
		{
			$result = $db_connect -> query("SELECT SUM(i.bonus1), SUM(i.bonus2), SUM(i.bonus3), SUM(i.bonus4) FROM items AS i, player_items AS p WHERE i.id = p.item_id AND p.active = 1 AND p.player_id = '$id'");
			if(!$result)
			{
				throw new Exception($db_connect->error);
			}
			else
			{
				$line = $result -> fetch_assoc();

				$ask = $db_connect -> query("SELECT str, hnd, inte, chr FROM uzytkownicy WHERE id = '$id'");
				$row = $ask -> fetch_assoc();

				$str = $row['str']+$line['SUM(i.bonus1)'];
				$hnd = $row['hnd']+$line['SUM(i.bonus2)'];
				$inte = $row['inte']+$line['SUM(i.bonus3)'];
				$chr = $row['chr']+$line['SUM(i.bonus4)'];

				$_SESSION['full_str'] = $str;
				$_SESSION['full_hnd'] = $hnd;
				$_SESSION['full_inte'] = $inte;
				$_SESSION['full_chr'] = $chr;


				echo "Siła: ".$str.' ('.$row['str'].'+'.$line['SUM(i.bonus1)'].')</br>';
				echo "Zręczność: ".$hnd.' ('.$row['hnd'].'+'.$line['SUM(i.bonus2)'].')</br>';
				echo "Inteligencja: ".$inte.' ('.$row['inte'].'+'.$line['SUM(i.bonus3)'].')</br>';
				echo "Charyzma: ".$chr.' ('.$row['chr'].'+'.$line['SUM(i.bonus4)'].')</br>';
			}
			$db_connect -> close();
		}

	}
	catch(Exception $e)
	{
		$_SESSION['Exception']='<span style="color: red">Błąd serwera! Spróbuj później.</span>';
		echo $e;
	}
}
?>
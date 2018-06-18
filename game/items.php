<?php
session_start();

if(isset($_POST['firstItemId']))
{
	$firstItemId = $_POST['firstItemId'];
	$itemActive = $_POST['itemActive'];
	$userId = $_SESSION['id'];

	$allGood = true;


	//sprawdzanie id
	if(intval($firstItemId) != $firstItemId or intval($userId) != $userId) $allGood = false;
	if(!is_numeric($firstItemId) or !is_numeric($userId)) $allGood = false;
	if($firstItemId <= 0 or $userId <= 0) $allGood = false;
	if(is_float($firstItemId) or is_float($userId)) $allGood = false;

	//sprawdzanie itemActive
	 if($itemActive != 0 && $itemActive != 1) $allGood = false;

	if($allGood)
	{
		if($itemActive == 0) $reverse = 1;
		else $reverse = 0;

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
				$result = $db_connect -> query("SELECT id FROM items ORDER BY id DESC LIMIT 1");
				if($db_connect->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());	
					$allGood = false;			
				}
				else
				{
					$line = $result -> fetch_assoc();
					$maxId = $line['id'];

					//sprawdzanie czy id nie wykracza poza skalę
					if($firstItemId > $maxId)
					{
						$allGood = false;
					} 
					else
					{
						//sprawdzanie typu przedmiotu
						$result = $db_connect -> query("SELECT type FROM items WHERE id='$firstItemId'");
						if($db_connect->connect_errno!=0)
						{
							throw new Exception(mysqli_connect_errno());
							$allGood = false;				
						}
						else
						{
							$line = $result -> fetch_assoc();
							$newType = $line['type'];
							
							//sprawdzanie czy przedmiot jest aktywny
							$result = $db_connect -> query("SELECT p.active FROM player_items AS p, items AS i WHERE i.id = p.item_id AND p.player_id ='$userId' AND p.item_id = '$firstItemId' ORDER BY active DESC LIMIT 1");
							if($db_connect->connect_errno!=0)
							{
								throw new Exception(mysqli_connect_errno());
								$allGood = false;				
							}
							else
							{
							//sprawdzanie czy uzytkownik ma taki przedmiot

								$line = $result -> fetch_assoc();
								$newActive = $line['active'];

								//podmiana przedmiotów, ściąganie lub zakładanie
								if($itemActive != $newActive)
								{
									if($itemActive == 0)
									{
										// tylko ściągam przedmiot
										$result = $db_connect -> query("SELECT id FROM player_items WHERE player_id ='$userId' AND item_id = '$firstItemId' AND active = 1 ORDER BY active DESC LIMIT 1");

										if($db_connect->connect_errno!=0)
										{
											throw new Exception(mysqli_connect_errno());
											$allGood = false;				
										}
										else
										{
											$line = $result -> fetch_assoc();
											$newId = $line['id'];

											$result = $db_connect -> query("UPDATE player_items SET active = 0 WHERE id = '$newId' ");
											if($db_connect->connect_errno!=0)
											{
												throw new Exception(mysqli_connect_errno());
												$allGood = false;				
											}
										}
									}
									else
									{
										//znajduję ewentualną podmiankę i zakładam
										$result = $db_connect -> query("UPDATE player_items AS p, items AS i SET p.active = 0 WHERE p.item_id = i.id AND p.player_id = '$userId' AND p.active = 1 AND i.type = '$newType'");
										if($db_connect->connect_errno!=0)
										{
											throw new Exception(mysqli_connect_errno());
											$allGood = false;				
										}
										else
										{
											$result = $db_connect -> query("SELECT id FROM player_items WHERE player_id ='$userId' AND item_id = '$firstItemId' AND active = 0 ORDER BY id LIMIT 1");

											if($db_connect->connect_errno!=0)
											{
												throw new Exception(mysqli_connect_errno());
												$allGood = false;				
											}
											else
											{
												$line = $result -> fetch_assoc();
												$newId = $line['id'];

												$result = $db_connect -> query("UPDATE player_items SET active = 1 WHERE id = '$newId' ");
												if($db_connect->connect_errno!=0)
												{
													throw new Exception(mysqli_connect_errno());
													$allGood = false;				
												}
											}
										}
									}
								}
							}
						}
					}
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
	else $allGood = false;

	echo $allGood;
}
?>

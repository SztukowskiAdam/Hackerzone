<?php
session_start();

if(isset($_POST['msgId']) || isset($_POST['removeId']))
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
			$result = $db_connect -> query("SELECT id FROM messages ORDER BY id DESC LIMIT 1");
			if(!$result)
			{
				throw new Exception($db_connect->error);
			}
			else
			{
				$line = $result -> fetch_assoc();
				$maxId = $line['id'];

				// odznaczanie przeczytanej wiadomości
				if(isset($_POST['msgId']))
				{

					$msgId = $_POST['msgId'];
					$userName = $_SESSION['user'];

					$allGood = true;

					//sprawdzanie id
					if(intval($msgId) != $msgId) $allGood = false;
					if(!is_numeric($msgId)) $allGood = false;
					if($msgId <= 0) $allGood = false;
					if(is_float($msgId)) $allGood = false;
					if($maxId < $msgId) $allGood = false;


					// sprawdzanie do kogo należy wiadomość
					if($allGood)
					{
						$result = $db_connect -> query("SELECT adressed FROM messages WHERE id = '$msgId'");
						if(!$result)
						{
							throw new Exception($db_connect->error);
						}
						else
						{
							$line = $result -> fetch_assoc();

							if($userName == $line['adressed'])
							{
								$result = $db_connect -> query("UPDATE messages SET adressed_readed = 1 WHERE id = '$msgId'");
								if(!$result)
								{
									throw new Exception($db_connect->error);
								}
							}
						}
					}	
				}
				//usuwanie wiadmości
				else if(isset($_POST['removeId']))
				{
					$removeId = $_POST['removeId'];
					$userName = $_SESSION['user'];

					$allGood = true;

					//sprawdzanie id
					if(intval($removeId) != $removeId) $allGood = false;
					if(!is_numeric($removeId)) $allGood = false;
					if($removeId <= 0) $allGood = false;
					if(is_float($removeId)) $allGood = false;
					if($maxId < $removeId) $allGood = false;

					if($allGood)
					{
						$result = $db_connect -> query("SELECT sender_delete FROM messages WHERE id = '$removeId' AND adressed = '$userName'");
						if(!$result)
						{
							throw new Exception($db_connect->error);
						}
						else
						{
							$isOwner = $result -> num_rows;

							if($isOwner > 0)
							{
								$line = $result -> fetch_assoc();

								if($line['sender_delete'] == 1)
								{
									$result = $db_connect -> query("DELETE FROM messages WHERE id='$removeId' AND adressed = '$userName'");
									if(!$result)
									{
										throw new Exception($db_connect->error);
									}
								}
								else
								{
									$result = $db_connect -> query("UPDATE messages SET adressed_delete = 1 WHERE id = '$removeId'");
									if(!$result)
									{
										throw new Exception($db_connect->error);
									}
								}						
							}
							else
							{
								$result = $db_connect -> query("SELECT adressed_delete FROM messages WHERE id = '$removeId' AND sender = '$userName'");
								if(!$result)
								{
									throw new Exception($db_connect->error);
								}
								else
								{
									$isOwner = $result -> num_rows;

									if($isOwner > 0)
									{
										$line = $result -> fetch_assoc();

										if($line['adressed_delete'] == 1)
										{
											$result = $db_connect -> query("DELETE FROM messages WHERE id='$removeId' AND sender = '$userName'");
											if(!$result)
											{
												throw new Exception($db_connect->error);
											}
										}
										else
										{
											$result = $db_connect -> query("UPDATE messages SET sender_delete = 1 WHERE id = '$removeId'");
											if(!$result)
											{
												throw new Exception($db_connect->error);
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
?>

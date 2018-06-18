<?php

	session_start();

	if( ((!isset($_POST['login'])) || (!isset($_POST['pass']))))
	{
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);


	try
	{
		$db_connect= new mysqli($host, $db_user, $db_password, $db_name);

		if($db_connect->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());			
		}
		else
		{
			$login=$_POST['login'];
			$pass=$_POST['pass'];

			$login = htmlentities($login, ENT_QUOTES, "UTF-8");

			$result = $db_connect->query(sprintf("SELECT * FROM uzytkownicy WHERE user='%s'", mysqli_real_escape_string($db_connect, $login)));

			if(!$result)
			{
				throw new Exception($db_connect->error);
			}
			else
			{
				$how_many_users= $result->num_rows;
				if($how_many_users>0)
				{
					$line=$result->fetch_assoc();

					if(password_verify($pass, $line['pass']))
					{

						$_SESSION['logged'] = true;
						$_SESSION['id'] = $line['id'];
						$_SESSION['user'] = $line['user'];
						$_SESSION['level'] = $line['level'];
						$_SESSION['money'] = $line['money'];
						$_SESSION['vipmoney'] = $line['vipmoney'];
						$_SESSION['email'] = $line['email'];
						$_SESSION['premium'] = $line['premium'];
						$_SESSION['exp'] = $line['exp'];
						$_SESSION['str'] = $line['str'];
						$_SESSION['hnd'] = $line['hnd'];
						$_SESSION['inte'] = $line['inte'];
						$_SESSION['chr'] = $line['chr'];
						$level = $_SESSION['level'];
						$id = $_SESSION['id'];

						$result=$db_connect->query("SELECT exp FROM experience WHERE id='$level'");
						if(!$result)
						{
							throw new Exception($db_connect->error);
						}
						else
						{
							$line=$result->fetch_assoc();
							$_SESSION['needexp']=$line['exp'];

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

								$_SESSION['full_str'] = $row['str']+$line['SUM(i.bonus1)'];
								$_SESSION['full_hnd'] = $row['hnd']+$line['SUM(i.bonus2)'];
								$_SESSION['full_inte'] = $row['inte']+$line['SUM(i.bonus3)'];
								$_SESSION['full_chr'] = $row['chr']+$line['SUM(i.bonus4)'];
							}
						}
						header('Location: game/index.php');
					}
					else
					{
						$_SESSION['error']='<span style="color:red">Błędny login lub hasło!</span>';
						header('Location: index.php');
					}
				}
				else
				{
					$_SESSION['error']='<span style="color:red">Błędny login lub hasło!</span>';
					header('Location: index.php');
				}
			}
			$db_connect -> close();		
		}
	}
	catch(Exception $e)
	{
		$_SESSION['error']='<span style="color: red">Błąd serwera! Spróbuj później.</span>';
		header('Location: index.php');
	}
?>

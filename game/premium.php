<?php
	include("header.php");
	include("top.php");
	include("menu.php");

	require_once "../connect.php";
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
				$id = $_SESSION['id'];

				if(isset($_POST['sub']))
				{
					$result=$db_connect->query("SELECT level, exp FROM uzytkownicy WHERE id='$id'");
					if(!$result)
					{
						throw new Exception($db_connect->error);
					}
					else
					{

					$how_many_users= $result->num_rows;

					if($how_many_users==1)
					{
						$line=$result->fetch_assoc();
						$level=$line['level'];
						$playerexp=$line['exp'];
					
						$result=$db_connect->query("SELECT exp FROM experience WHERE id='$level'");
						if(!$result)
						{
							throw new Exception($db_connect->error);
						}
						else
						{
							$how_many_users= $result->num_rows;

							if($how_many_users==1)
							{
								$line=$result->fetch_assoc();
								$requiredexp=$line['exp'];

								$experien=3; //tutaj dajemy expa, który otrzymaliśmy

								$playerexp+=$experien;

								if($playerexp>=$requiredexp)
								{
									$newexp=$playerexp-$requiredexp;

									if(!$db_connect->query("UPDATE uzytkownicy SET level='$level'+1 WHERE id='$id'"))
									{
										throw new Exception($db_connect->error);
									}
									else if(!$db_connect->query("UPDATE uzytkownicy SET exp='$newexp' WHERE id='$id'"))
									{
										throw new Exception($db_connect->error);
									}
									else
									{
										$level++;
										$_SESSION['level']++;
										$_SESSION['exp']=$newexp;

										$result= $db_connect->query("SELECT exp FROM experience WHERE id='$level'");
										if(!$result)
										{
											throw new Exception($db_connect->error);
										}
										else
										{
											$line=$result->fetch_assoc();
											$_SESSION['needexp']=$line['exp'];

										}

									header('refresh: 0;');

									}
								}
								else
								{
									
									if(!$db_connect->query("UPDATE uzytkownicy SET exp='$playerexp' WHERE id='$id'"))
									{
										throw new Exception($db_connect->error);
									}
									else
									{
										$_SESSION['exp']=$playerexp;
										header('refresh: 0;');
									}
								}


							}
						}
					}

					}
				}
			}
		}
		catch(Exception $e)
		{
			$_SESSION['Exception']='<span style="color: red">Błąd serwera! Spróbuj później.</span>';
			echo $e;
		}
?>

<div class="game">
	<header>
	Premium
	</header>

	<article>
		Premium
	</article>
 </div>

<?php
	include("footer.php");
?>

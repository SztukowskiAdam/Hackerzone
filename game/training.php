<?php
	include("header.php");
	include("top.php");
	include("menu.php");
?>

<div class="game">
	<article>
	<?php
		require_once "../connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);

		try
		{
			$db_connect= new mysqli($host, $db_user, $db_password, $db_name);

			if($db_connect->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());				
			}
// pobieranie danych
			else
			{
				$id = $_SESSION['id'];
				$str=$_SESSION['str'];
				$hnd=$_SESSION['hnd'];
				$inte=$_SESSION['inte'];
				$chr=$_SESSION['chr'];
				$gainStr=4.6;
				$gainHnd=4.3;
				$gainInte=3.6;
				$gainChr=3;
					
// kiedy ktoś dodał umiejętność
				if(isset($_POST['skill']))
				{
					switch ($_POST['skill'])
					{
						case 1:

							if($_POST['cost']<=$_SESSION['money'])
							{
								$money=$_SESSION['money']-$_POST['cost'];

								if(!$db_connect->query("UPDATE uzytkownicy SET str='$str'+1 WHERE id='$id'"))
								{
									throw new Exception($db_connect->error);
								}
								else if (!$db_connect->query("UPDATE uzytkownicy SET money='$money' WHERE id='$id'"))

								{
									throw new Exception($db_connect->error);
								}
								else								
								{
									$_SESSION['str']++;
									$str++;

									$_SESSION['money']=$money;

									header('refresh: 0;');
								}
							}
						break;

						case 2:

							if($_POST['cost']<=$_SESSION['money'])
							{
								$money=$_SESSION['money']-$_POST['cost'];

								if(!$db_connect->query("UPDATE uzytkownicy SET hnd='$hnd'+1 WHERE id='$id'"))
								{
									throw new Exception($db_connect->error);
								}
								else if (!$db_connect->query("UPDATE uzytkownicy SET money='$money' WHERE id='$id'"))

								{
									throw new Exception($db_connect->error);
								}
								else								
								{
									$_SESSION['hnd']++;
									$hnd++;

									$_SESSION['money']=$money;

									header('refresh: 0;');
								}
							}

						break;

						case 3:

							if($_POST['cost']<=$_SESSION['money'])
							{
								$money=$_SESSION['money']-$_POST['cost'];

								if(!$db_connect->query("UPDATE uzytkownicy SET inte='$inte'+1 WHERE id='$id'"))
								{
									throw new Exception($db_connect->error);
								}
								else if (!$db_connect->query("UPDATE uzytkownicy SET money='$money' WHERE id='$id'"))

								{
									throw new Exception($db_connect->error);
								}
								else								
								{
									$_SESSION['inte']++;
									$inte++;

									$_SESSION['money']=$money;

									header('refresh: 0;');
								}
							}
						break;

						case 4:

							if($_POST['cost']<=$_SESSION['money'])
							{
								$money=$_SESSION['money']-$_POST['cost'];

								if(!$db_connect->query("UPDATE uzytkownicy SET chr='$chr'+1 WHERE id='$id'"))
								{
									throw new Exception($db_connect->error);
								}
								else if (!$db_connect->query("UPDATE uzytkownicy SET money='$money' WHERE id='$id'"))

								{
									throw new Exception($db_connect->error);
								}
								else								
								{
									$_SESSION['chr']++;
									$chr++;

									$_SESSION['money']=$money;

									header('refresh: 0;');
								}
							}

						break;
					}
				}
				$db_connect->close();
			}
		}
		catch(Exception $e)
		{
			$_SESSION['Exception']='<span style="color: red">Błąd serwera! Spróbuj później.</span>';
			echo $e;
		}

	?>

	<table>

		<tr>
			<td>
			<?php
				echo $_SESSION['str'];
			?>
			</td>
			<td>
			<form method="post">
				<div class="plus">
					<input type="hidden" name="skill" value="1" />

					<?php
						$strCost=$_SESSION['str']*$gainStr;
						$strCost=ceil($strCost);
						echo "Cena: "."$strCost";
						echo '<input type="hidden" name="cost" value="'."$strCost".'" />';


						if($_SESSION['money']>=$strCost)
						{
							echo '<input type="submit" value="&#xe807;" style="font-family:fontello;"/>';
						}
						else
						{
							echo '<input type="submit" value="&#xe807;" style="font-family:fontello; color:gray;" disabled/>';
						}
					?>
					
				</div>
			</form>
			</td>
		</tr>

		<tr>
			<td>
			<?php
				echo $_SESSION['hnd'];
			?>
			</td>
			<td>
			<form method="post">
				<div class="plus">
					<input type="hidden" name="skill" value="2" />

					<?php
						$hndCost=$_SESSION['hnd']*$gainHnd;
						$hndCost=ceil($hndCost);
						echo "Cena: "."$hndCost";
						echo '<input type="hidden" name="cost" value="'."$hndCost".'" />';


						if($_SESSION['money']>=$hndCost)
						{
							echo '<input type="submit" value="&#xe807;" style="font-family:fontello;"/>';
						}
						else
						{
							echo '<input type="submit" value="&#xe807;" style="font-family:fontello; color:gray;" disabled/>';
						}
					?>
					
				</div>
			</form>
			</td>
		</tr>

		<tr>
			<td>
			<?php
				echo $_SESSION['inte'];
			?>
			</td>
			<td>
			<form method="post">
				<div class="plus">
					<input type="hidden" name="skill" value="3" />

					<?php
						$inteCost=$_SESSION['inte']*$gainInte;
						$inteCost=ceil($inteCost);
						echo "Cena: "."$inteCost";
						echo '<input type="hidden" name="cost" value="'."$inteCost".'" />';


						if($_SESSION['money']>=$inteCost)
						{
							echo '<input type="submit" value="&#xe807;" style="font-family:fontello;"/>';
						}
						else
						{
							echo '<input type="submit" value="&#xe807;" style="font-family:fontello; color:gray;" disabled/>';
						}
					?>
					
				</div>
			</form>
			</td>
		</tr>

		<tr>
			<td>
			<?php
				echo $_SESSION['chr'];
			?>
			</td>
			<td>
			<form method="post">
				<div class="plus">
					<input type="hidden" name="skill" value="4" />

					<?php
						$chrCost=$_SESSION['chr']*$gainChr;
						$chrCost=ceil($chrCost);
						echo "Cena: "."$chrCost";
						echo '<input type="hidden" name="cost" value="'."$chrCost".'" />';


						if($_SESSION['money']>=$chrCost)
						{
							echo '<input type="submit" value="&#xe807;" style="font-family:fontello;"/>';
						}
						else
						{
							echo '<input type="submit" value="&#xe807;" style="font-family:fontello; color:gray;" disabled/>';
						}
					?>
					
				</div>
			</form>
			</td>
		</tr>


	</table>

	</article>
 </div>

<?php
	include("footer.php");
?>

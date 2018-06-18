<?php
	include("header.php");
	include("top.php");
	include("menu.php");
?>

<div class="game">
	<article>
<?php
// obsługa pracy

	require_once "../connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);

// łączenie z bazą
	try
	{	
		$db_connect= new mysqli($host, $db_user, $db_password, $db_name);

		if($db_connect->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());				
		}
// sprawdzanie czy ktoś już pracuje
		else
		{
			$id = $_SESSION['id'];

			$result=$db_connect->query("SELECT id FROM praca WHERE user='$id'");

			if(!$result) 
			{
				throw new Exception($db_connect->error);
			}
			else
			{
				$how_many_users= $result->num_rows;
// gdy ktoś nie pracuje
				if($how_many_users<1)
				{	
// gdy ktoś nie pracuje i kliknie na pracę
					if(isset($_POST['jobs']))
					{
						$time = $_POST['hours'];
						$job = $_POST['jobs'];
						$money = $_POST['money'];
						$prize=$time*$money;
						$_SESSION['prize']=$prize;


						$result=$db_connect->query("INSERT INTO praca VALUES(NULL,'$id',now() + INTERVAL '$time' MINUTE,'$prize')");

						if(!$result)
						{
							 throw new Exception($db_connect->error);
						}

					}
// gdy ktoś nie pracuje i nie kliknie na pracę
					else
					{
echo <<<END
	<div class="job">
		<table>
			<tr>
			<form method="post">
				<td>Stażysta</td> <td>40</td> 
				<input type="hidden" name="money" value="40" />
				<td>
				Godziny:
					<select name="hours">
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
						<option>6</option>
						<option>7</option>
						<option>8</option>
					</select>
				</td>
				<td>
					<input type="hidden" name="jobs" value="1" />
					<input type="submit" value="Pracuj!" />
				</td>
			</form>
			</tr>

			<tr>
			<form method="post">
				<td>Nauczyciel informatyki</td> <td>80</td> 
				<input type="hidden" name="money" value="80" />
				<td>
				Godziny:
					<select name="hours">
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
					</select>
				</td>
				<td>
					<input type="hidden" name="jobs" value="2" />
					<input type="submit" value="Pracuj!">
				</td>
			</form>
			</tr>


			<tr>
			<form method="post">
				<td>Freelancer</td> <td>120</td> 
				<input type="hidden" name="money" value="120" />
				<td>
				Godziny:
					<select name="hours">
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
						<option>6</option>
						<option>7</option>
						<option>8</option>
						<option>9</option>
						<option>10</option>
						<option>11</option>
						<option>12</option>
					</select>
				</td>
				<td>
				
					<input type="hidden" name="jobs" value="3" />
					<input type="submit" value="Pracuj!">
				</td>
			</form>
			</tr>


			<tr>
			<form method="post">
				<td>Hacker wojskowy</td> <td>4000</td> 
				<input type="hidden" name="money" value="4000" />
				<td>
				Godziny:
					<select name="hours">
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
						<option>6</option>
						<option>7</option>
					</select>
				</td>
				<td>
					<input type="hidden" name="jobs" value="4" />
					<input type="submit" value="Pracuj!">
				</td>
			</form>
			</tr>


END;

// prace do odblokowania z poziomem

						if($_SESSION['level']>30)
						{
echo <<<END
							<tr>
							<form method="post">
								<td>Dowódca zabezpieczeń cybernetycznych</td> <td>45000</td> 
								<input type="hidden" name="money" value="45000" />
								<td>
								Godziny:
									<select name="hours">
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
										<option>6</option>
										<option>7</option>
									</select>
								</td>
								<td>
									<input type="hidden" name="jobs" value="5" />
									<input type="submit" value="Pracuj!">
								</td>
							</form>
							</tr>				
END;
						}

echo "</table></div>";
					}

				}

//zegar odliczający czas pracy
				else
				{
					$result=$db_connect->query("SELECT time FROM praca WHERE user='$id'");

					if(!$result) 
					{
						throw new Exception($db_connect->error);
					}
					else
					{
						$line=$result->fetch_assoc();

						$_SESSION['time']=$line['time'];
						$event=$_SESSION['time'];
						$event=StrToTime($event);
						$now=Date('Y-m-d H:i:s');
						$now=StrToTime($now);
						$time=$event-$now;

						$hours = floor(($time/3600)) % 24;
   						$minutes = ($time/60) % 60;
    					$seconds = $time % 60;

						
						if($time>0)
						{
							
echo<<<END

Koniec pracy za: $hours h $minutes min $seconds s <br>

	<form method="post">
		<input type="submit" value="Zrezygnuj z pracy" name="resign">
	</form>
END;

// wciśnięcie rezygnacji
							if(isset($_POST['resign']))
							{
								if(!$db_connect->query("DELETE FROM praca WHERE user='$id'")) 
								{
									throw new Exception($db_connect->error);
								}
								header('refresh: 0;');
							}


						}
// dodawanie nagrody za pracę	
						else
						{						
echo<<<END
	<form method="post">
		<input type="submit" value="Odbierz nagrodę!" name="takeprize">
	</form>
END;
							if(isset($_POST['takeprize']))
							{
								$result=$db_connect->query("SELECT prize FROM praca WHERE user='$id'");

								if(!$result)
								{
									throw new Exception($db_connect->error);
								}
								else
								{
									$line=$result->fetch_assoc();
									$_SESSION['prize']=$line['prize'];
								}

								if(!$db_connect->query("DELETE FROM praca WHERE user='$id'")) 
								{
									throw new Exception($db_connect->error);
								}
								else
								{
									$newmoney=$_SESSION['money'];
									$prize=$_SESSION['prize'];
									$newmoney+=$prize;
								}

								if(!$db_connect->query("UPDATE uzytkownicy SET money='$newmoney' WHERE id='$id'")) 
								{
									throw new Exception($db_connect->error);
								}
								else
								{
									$_SESSION['money']+=$prize;
								}
								
								header('refresh: 0;');
							}
						}
					}
				}
			}
			$db_connect-> close();
		}
	}
	catch(Exception $e)
	{
		$_SESSION['Exception']='<span style="color: red">Błąd serwera! Spróbuj później.</span>';
		echo $e;
	}


?>

	</article>
 </div>

<?php
	include("footer.php");
?>

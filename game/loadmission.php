<?php


	if(!isset($_POST['quest_id']))
	{
		header('Location: character.php');
	}
	else
	{
		$questId = $_POST['quest_id'];
		$allGood = true;

		if(intval($questId) != $questId) $allGood = false;
		if(!is_numeric($questId)) $allGood = false;
		if($questId <= 0) $allGood = false;
		if(is_float($questId)) $allGood = false;

		if(!$allGood)
		{
			header('Location: character.php');
		}
		else
		{
			include("header.php");
			include("top.php");
			include("menu.php");
		}
	}

?>

<script type="text/javascript">

	function updateExpValues()
	{
		$(document).ready(function(){
			$("#exp_values").load("update_exp_values.php");
		});
	}

	function updateMoneyValues()
	{
		$(document).ready(function(){
			$("#money_values").load("update_money_values.php");
		});
	}

</script>

<div class="game">

<article>
	<div class = "quest_list">

	<?php

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
				$db_connect -> set_charset("utf8");
				$result = $db_connect -> query("SELECT level, bonus1, bonus2, bonus3, bonus4 FROM missions WHERE id = '$questId' ");
				if(!$result)
				{
					throw new Exception($db_connect->error);
				}
				else
				{
					$howManyMissions = $result -> num_rows;
					$userLevel = $_SESSION['level'];
					$userId = $_SESSION['id'];

					if($howManyMissions > 0)
					{
						$line = $result -> fetch_assoc();

						if($line['level'] <= $userLevel)
						{
							$userPoints = $_SESSION['full_str']+$_SESSION['full_hnd']+$_SESSION['full_inte']+$_SESSION['full_chr'];
							$missionPoints = $line['bonus1']+$line['bonus2']+$line['bonus3']+$line['bonus4'];

							$chance = floor(($userPoints/($userPoints + $missionPoints))*100);
							$success = rand(1,100);

							if($success <=$chance)
							{
								echo '<div class = "quest_success">Udało Ci się!</div>';
								echo '<div class = "quest_reward">';

								$itemChace = rand(1,2);

								if($itemChace == 1)
								{
									$result = $db_connect -> query("SELECT * FROM items WHERE level <= '$userLevel' ORDER BY level");
									if(!$result)
									{
										throw new Exception($db_connect->error);
									}
									else
									{
										$howManyItems = $result -> num_rows;

										$itemNumber = rand(1,$howManyItems);

										$iterator = 1;

										while($line = $result -> fetch_assoc())
										{
											if($iterator == $itemNumber)
											{
												$itemId = $line['id'];
												$itemType = $line['type'];

												$ask = $db_connect -> query("SELECT COUNT(i.id) AS howManyItems FROM items AS i, player_items AS p WHERE i.id = p.item_id AND p.player_id = '$userId' AND i.type = '$itemType'");
												if(!$ask)
												{
													throw new Exception($db_connect->error);
												}
												else
												{
													$row = $ask -> fetch_assoc();

													echo '<div class = "quest_reward_item">';

													if($row['howManyItems'] < 18)
													{
														$ask = $db_connect -> query("INSERT INTO player_items VALUES(NULL,'$userId', '$itemId',0)");
														if(!$ask)
														{
															throw new Exception($db_connect->error);
														}
														echo 'Przy okazji znalazłeś:</br></br>';
													}
													else echo 'Otrzymałeś ten przedmiot lecz nie posiadasz wolnego miejsca w ekwipunku:</br></br>';
												}
echo '<div onmouseover="check(this)" class="item" data-type="'.$line['type'].'"><img src="images/items/'.$line['id'].'.png" ><div class="tooltiptext"><span style="color: gold; font-weight: bold">'.$line['name'].'</span></br> <div class = "variety">bonus1: </br>bonus2: </br>bonus3: </br>bonus4: </br></br>Wartość: '.$line['price_gold'].'</div><div class="values">'.$line['bonus1'].'</br>'.$line['bonus2'].'</br>'.$line['bonus3'].'</br>'.$line['bonus4'].'</br></div><div style="clear:both"></div></div></div>';
												echo '</div>';
											}
											$iterator++;
										}
									}
								}

								$addExp = rand(ceil($_SESSION['level']/10),ceil($_SESSION['level']/3.7));
								$addMoney = rand(ceil($_SESSION['level']*10), ceil($_SESSION['level']*15));
								$addVipMoney = rand(1,100);
								
								if($addVipMoney >= 8) $addVipMoney = 0;
								else $addVipMoney = 1;

								echo '<div class = "quest_reward_exp">';
									echo '</br></br> Dostałeś '.$addExp.' pkt doświadczenia!';
									echo '</br> Dostałeś '.$addMoney.' $!';
									if($addVipMoney == 1) echo '</br> Dostałeś 1 vip $!';
								echo '</div>';

								$_SESSION['exp']+=$addExp;
								$_SESSION['money']+=$addMoney;
								$_SESSION['vipmoney']+=$addVipMoney;

								echo '</div>';
								echo '<script>updateExpValues(); updateMoneyValues()</script>';
							} 
							else echo '<div class = "quest_failure">Zjebałeś!</div>';


							echo '<div class = "quest_stats">';
								echo 'Szansa powodzenia: '.$chance.'%';
							echo '</div>';
						}
						else
						{
							header('Location: character.php');
						}
					}
					else
					{
						header('Location: character.php');
					}
				}

				$db_connect -> close();
			}
		}
		catch(Exception $e)
		{
			echo $e;
		}
	?>

	</div>
</article>
 </div>

<?php
	include("footer.php");
?>

<?php
	include("header.php");
	include("top.php");
	include("menu.php");
?>

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
						$db_connect->set_charset("utf8");
						$level = $_SESSION['level'];

						$result = $db_connect -> query("SELECT id, name, level, description FROM missions WHERE level <= '$level' ORDER BY level DESC limit 3");
						if(!$result)
						{
							throw new Exception($db_connect->error);
						}
						else
						{
							while($line = $result -> fetch_assoc())
							{
								echo '<div class = "quest">';
									echo '<div class = "quest_name">';
										echo $line['name'];
									echo '</div>';

									echo '<button class = "quest_info" onclick = "viewQuest(this)">Informacje</button>';

									echo '<div class = "quest_level">';
										echo 'Level: '.$line['level'];
									echo '</div>';

									echo '<div class = "quest_content">';
											echo '<img class = "quest_img" src = "images/quests/'.$line['id'].'.png">';
											
											echo '<div class = "quest_description">';
												echo $line['description'];
												echo '<div class = "quest_submit">';
													echo '<form action = "loadmission.php" method = "post">';
														echo '<input type = "hidden" name ="quest_id" value = "'.$line['id'].'">';
														echo '<input type = "submit" value = "Rozpocznij!">';
													echo '</form>';
												echo '</div>';
											echo '</div>';
									
									echo '</div>';



								echo '</div>';
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

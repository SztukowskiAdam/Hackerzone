<?php
	include("header.php");
	include("top.php");
	include("menu.php");
?>


<script type="text/javascript">

	$(document).ready(function(){
		$(".item").click(function(){
			put_on($(this));
		});
	});

	function changeContent(id)
	{
		$('#graphics_eq').css("display", "none");
		$('#hard_drive_eq').css("display", "none");
		$('#motherboard_eq').css("display", "none");
		$('#power_supply_eq').css("display", "none");
		$('#processor_eq').css("display", "none");
		$('#ram_eq').css("display", "none");
		<?php
			if($_SESSION['level']>=4)
			{
				echo '$("#phone_eq").css("display", "none");';
				echo '$("#watch_eq").css("display", "none");';
			}
		?>

		$("#"+id).css("display", "block");
	}

</script>

<div class="game">

	<article>
		<div class="left_side">
			<div id="avatar"></div>
			<?php
			if($_SESSION['level'] >= 4)
			{
				echo '<div id="phone"></div>';
				echo '<div id="watch"></div>';
				echo '<div style="clear: both"></div>';
			}
		?>
		</div>
		<div id="computer">
			<div id="power_supply"></div>
			<div id="processor"></div>
			<div id="ram"></div>

			<div style="clear: both"></div>

			<div id="graphics"></div>
			<div id="motherboard"></div>
			<div id="hard_drive"></div>

		</div>
		<div id="stats"></div>

		<div id="player_equipment">
			<div class="button" id="graphics_button" onclick="changeContent('graphics_eq')"></div>
			<div class="button" id="hard_drive_button" onclick="changeContent('hard_drive_eq')"></div>
			<div class="button" id="motherboard_button" onclick="changeContent('motherboard_eq')"></div>
			<div class="button" id="power_supply_button" onclick="changeContent('power_supply_eq')"></div>
			<div class="button" id="processor_button" onclick="changeContent('processor_eq')"></div>
			<div class="button" id="ram_button" onclick="changeContent('ram_eq')"></div>
			<?php
				if($_SESSION['level'] >= 4)
				{
					echo '<div class="button" id="phone_button" onclick="changeContent('.'\'phone_eq\''.')"></div>';
				echo '<div class="button" id="watch_button" onclick="changeContent('.'\'watch_eq\''.')"></div>';
				}
			?>
			<div id="equipment">


				<div id="graphics_eq">
<?php


	try
	{
		$db_connect= new mysqli($host, $db_user, $db_password, $db_name);

		if($db_connect->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());				
		}

		else
		{	
			$db_connect->set_charset("utf8");
			$id = $_SESSION['id'];
			$result = $db_connect->query("SELECT p.item_id, p.active FROM player_items AS p, items AS i WHERE p.player_id = '$id' AND i.id = p.item_id ORDER BY i.type, i.id");

			if(!$result)
			{
				throw new Exception($db_connect->error);
			}
			else
			{
				$previousType = 'graphics';
				$names = array('hard_drive', 'motherboard', 'power_supply', 'processor', 'ram', 'phone', 'watch' );

				while($row = $result->fetch_assoc())
				{
				 	$item_id=$row['item_id'];
				 	$active=$row['active'];

				 	$ask = $db_connect->query("SELECT * FROM items WHERE id='$item_id' ORDER BY type");
					if(!$ask)
					{
						throw new Exception($db_connect->error);
					}
					else
					{
						$line = $ask->fetch_assoc();

						$type = $line['type'];
						$name = $line['name'];
						$priceGold = $line['price_gold'];

						$bonus1 = $line['bonus1'];
						$bonus2 = $line['bonus2'];
						$bonus3 = $line['bonus3'];
						$bonus4 = $line['bonus4'];

						$text = '<div onmouseover="check(this)" class="item '.$item_id.'" data-type="'.$type.'" data-id="'.$item_id.'"  data-active="'.$active.'"><img src="images/items/'.$item_id.'.png" ><div class="tooltiptext"><span style="color: gold; font-weight: bold">'.$name.'</span></br> <div class = "variety">bonus1: </br>bonus2: </br>bonus3: </br>bonus4: </br></br>Wartość: '.$priceGold.' </div><div class="values">'.$bonus1.'</br>'.$bonus2.'</br>'.$bonus3.'</br>'.$bonus4.'</br></div><div style="clear:both"></div></div></div>';

						switch ($type) 
						{
							case 'graphics':
								if($previousType != $type) echo '</div><div id="graphics_eq">';
								echo $text;
								$previousType = $type;
								for ($i=0; $i < 5 ; $i++) { 
									if($names[$i]=='graphics') $names[$i] = 0;
								}
								break;

							case 'hard_drive':
								if($previousType != $type) echo '</div><div id="hard_drive_eq">';
								echo $text;
								$previousType = $type;
								for ($i=0; $i < 5 ; $i++) { 
									if($names[$i]=='hard_drive') $names[$i] = 0;
								}
								break;

							case 'motherboard':
								if($previousType != $type) echo '</div><div id="motherboard_eq">';
								echo $text;
								$previousType = $type;
								for ($i=0; $i < 6 ; $i++) { 
									if($names[$i]=='motherboard') $names[$i] = 0;
								}
								break;

							case 'phone':
							if($_SESSION['level'] >= 4)
							{
								if($previousType != $type) echo '</div><div id="phone_eq">';
								echo $text;
								$previousType = $type;
								for ($i=0; $i < 6 ; $i++) { 
									if($names[$i]=='phone') $names[$i] = 0;
								}
							}
								break;

							case 'power_supply':
								if($previousType != $type) echo '</div><div id="power_supply_eq">';
								echo $text;
								$previousType = $type;
								for ($i=0; $i < 6 ; $i++) { 
									if($names[$i]=='power_supply') $names[$i] = 0;
								}
								break;

							case 'processor':
								if($previousType != $type) echo '</div><div id="processor_eq">';
								echo $text;
								$previousType = $type;
								for ($i=0; $i < 6 ; $i++) { 
									if($names[$i]=='processor') $names[$i] = 0;
								}
								break;

							case 'ram':
								if($previousType != $type) echo '</div><div id="ram_eq">';
								echo $text;
								$previousType = $type;
								for ($i=0; $i < 6 ; $i++) { 
									if($names[$i]=='ram') $names[$i] = 0;
								}
								break;

							case 'watch':
							if($_SESSION['level'] >= 4)
							{
								if($previousType != $type) echo '</div><div id="watch_eq">';
								echo $text;
								$previousType = $type;
								for ($i=0; $i < 6 ; $i++) { 
									if($names[$i]=='watch') $names[$i] = 0;
								}
							}
						}								
					}
				}
			}

			echo '</div>';
			$divId = 'graphics_eq';
			for ($i=0; $i < 6; $i++) 
			{ 
				if($names[$i] != '0')
				{
					$divId = $names[$i].'_eq';
					echo '<div id="'.$divId.'"></div>';
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

?>
			</div>
		</div>
	</article>
</div>

<?php
	include("footer.php");
?>

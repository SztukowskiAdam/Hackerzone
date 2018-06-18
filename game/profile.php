<?php
	include("header.php");
	include("top.php");
	include("menu.php");
?>

<script type="text/javascript">

	function put_on_profile(that)
	{
		var type = that.data('type');
		var place = $("#"+type);

		// zakładam na puste miejsce
		that.appendTo(place);
		that.css({"width" : "100%", "height" : "100%", "border" : "none"});
	}

	function active_items_profile()
	{
		var item = $(".item");
		var size = $(".item").length;
		
		for( var i=0; i<size; i++)
		{
			put_on_profile(item.eq(i));
		}
	}
</script>



<div class="game">
	<article>

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
		$db_connect -> set_charset("utf8");
		$result = $db_connect -> query("SELECT id FROM uzytkownicy ORDER BY id DESC LIMIT 1");

		if(!$result)
		{
			throw new Exception($db_connect->error);
		}
		else
		{
			$line = $result -> fetch_assoc(); 
			$maxUserNum = $line['id'];

			if(!isset($_GET['uid']) or $_GET['uid'] > $maxUserNum or !is_numeric($_GET['uid']) or $_GET['uid'] <= 0 or intval($_GET['uid']) != $_GET['uid'] or is_float($_GET['uid']))
			{
				echo "NIE ZNALEZIONO GRACZA";
			}
			else
			{
				$id = $_GET['uid'];

				$result = $db_connect->query("SELECT user FROM uzytkownicy WHERE id = '$id'");
				if(!$result)
				{
					throw new Exception($db_connect->error);
				}
				else
				{
					$line = $result -> fetch_assoc();
					$userName = $line['user'];
				}


				$result = $db_connect->query("SELECT i.* FROM items AS i, player_items AS p WHERE i.id = p.item_id AND p.player_id = '$id' AND p.active = 1 ORDER BY i.type");
				if(!$result)
				{
					throw new Exception($db_connect->error);
				}
				else
				{
					while($line = $result -> fetch_assoc())
					{
						$type = $line['type'];
						$item_id = $line['id'];
						$name = $line['name'];
						$priceGold = $line['price_gold'];

						$bonus1 = $line['bonus1'];
						$bonus2 = $line['bonus2'];
						$bonus3 = $line['bonus3'];
						$bonus4 = $line['bonus4'];
echo <<< END
<div onmouseover="check(this)" class="item" data-type="$type"><img src="images/items/$item_id.png" ><div class="tooltiptext"><span style="color: gold; font-weight: bold">$name</span></br> <div class = "variety">bonus1: </br>bonus2: </br>bonus3: </br>bonus4: </br></br>Wartość: $priceGold </div><div class="values">$bonus1</br>$bonus2</br>$bonus3</br>$bonus4</br></div><div style="clear:both"></div></div></div>
END;
					}
				}


echo <<< END
		<div class="left_side">
			<div id="avatar"></div>

			<div id="phone"></div>
			<div id="watch"></div>
			<div style="clear: both"></div>
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
END;

				$result = $db_connect -> query("SELECT SUM(i.bonus1), SUM(i.bonus2), SUM(i.bonus3), SUM(i.bonus4) FROM items AS i, player_items AS p WHERE i.id = p.item_id AND p.active = 1 AND p.player_id = '$id'");
				if($db_connect->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());			
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

					echo '<div id="stats">';
					echo "Siła: ".$str.' ('.$row['str'].'+'.$line['SUM(i.bonus1)'].')</br>';
					echo "Zręczność: ".$hnd.' ('.$row['hnd'].'+'.$line['SUM(i.bonus2)'].')</br>';
					echo "Inteligencja: ".$inte.' ('.$row['inte'].'+'.$line['SUM(i.bonus3)'].')</br>';
					echo "Charyzma: ".$chr.' ('.$row['chr'].'+'.$line['SUM(i.bonus4)'].')</br>';
					echo '</div></br>';

					if($_SESSION['id'] != $id)
					{
						echo '<a href = "#">WYZWIJ GRACZA NA POJEDYNEK</a></br>';
						echo '<a href = "sendmessage.php?adress='.$userName.'">NAPISZ WIADOMOŚĆ</a>';		
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
?>
	
	</article>
</div>

<?php
	include("footer.php");
?>
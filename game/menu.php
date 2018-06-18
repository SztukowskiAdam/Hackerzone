<?php
	$name="/hackerzone/game/menu.php";
	if($_SERVER['PHP_SELF'] == $name)
	{
		header('Location:index.php');
		exit();
	}

	$adres = basename($_SERVER["SCRIPT_NAME"], ".php");

	$style = 'style="  -webkit-box-shadow: 0px 0px 15px -1px rgba(255,255,255,0.5);
						-moz-box-shadow: 0px 0px 15px -1px rgba(255,255,255,0.5);
						box-shadow: 0px 0px 15px -1px rgba(255,255,255,0.5);
						background-color: rgba(70,70,70,0.5);"';
?>

	<div class="sidemenu">
		<ol>
			<li
				<?php
					if($adres=="character")
						echo $style;
				?>>
				<a href="character.php">Postać</a>
			</li>

			<li
				<?php
					if($adres=="group")
						echo $style;
				?>>
				<a href="group.php">Grupa</a>
			</li>

			<li
				<?php
					if($adres=="rank")
						echo $style;
				?>>
				<a href="rank.php">Ranking</a>
			</li>

			<li
				<?php
					if($adres=="friends")
						echo $style;
				?>>
				<a href="friends.php">Zaproś znajomych</a>
			</li>

			<li
				<?php
					if($adres=="story")
						echo $style;
				?>>
				<a href="story.php">Kody Promocyjne</a>
			</li>

			<li
				<?php
					if($adres=="premium")
						echo $style;
				?>>
				<a href="premium.php">Premium</a>
			</li>
		</ol>

	<div class="bar">
	</div>

		<ol>
			<li
				<?php
					if($adres=="mission")
						echo $style;
				?>>
				<a href="mission.php">Zadania</a>
			</li>

			<li
				<?php
					if($adres=="job")
						echo $style;
				?>>
				<a href="job.php">Praca</a>
			</li>


			<li
				<?php
					if($adres=="training")
						echo $style;
				?>>
				<a href="training.php">Szkolenie</a>
			</li>

			<li
				<?php
					if($adres=="confrontation")
						echo $style;
				?>>
				<a href="confrontation.php">Konfrontacja</a>
			</li>

			<li
				<?php
					if($adres=="store")
						echo $style;
				?>>
				<a href="store.php">Sklep komputerowy</a>
			</li>

			<li
				<?php
					if($adres=="cellar")
						echo $style;
				?>>
				<a href="cellar.php">Piwnica</a>
			</li>
			
			<li
				<?php
					if($adres=="market")
						echo $style;
				?>>
				<a href="market.php">Czarny rynek</a>
			</li>

		</ol>
	</div>


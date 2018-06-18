<?php
	$name="/hackerzone/game/top.php";
	if($_SERVER['PHP_SELF'] == $name)
	{
		header('Location:index.php');
		exit();
	}
	$name2="/hackerzone/game/character.php";
	echo '<body onload = "';
	if($_SERVER['PHP_SELF'] == $name2)
	{
		echo 'active_items(), update_stats()';
	}
	$name3="/hackerzone/game/profile.php";
		if($_SERVER['PHP_SELF'] == $name3)
	{
		echo 'active_items_profile()';
	}
	echo '">';
?>



<div class="container">

	<div class="topmenu">
		<div class="socials">
		<a href ="user.php">user</a>
		<a href ="messages.php">mail</a>
		<a href ="news.php">news</a>
		</div>

		<ul>
			<li>
				<?php
					echo '<div id = "exp_values"> Exp: '.$_SESSION['exp']." / ".$_SESSION['needexp'];
				?>
			</li>
			<li>
				<?php
					echo '<div id = "level_values">Level: '.$_SESSION['level'].'</div>';
				?>
			</li>
			<li>
				<?php
					echo '<i class="icon-dollar"></i><div id = "money_values">'.$_SESSION['money'].'</div>';
				?>
			</li>
			<li>
				<?php
					echo '<i class="icon-bug"></i><div id = "vipmoney_values">'.$_SESSION['vipmoney'].'</div>';
				?>
			</li>
			<li>
				<?php
					echo '<a href=logout.php>'.'<i class="icon-off"></i>'.'<b>Wyloguj się!</b>'.'</a>';
				?>				
			</li>
		</ul>

	</div>
	<div style="clear: both;">
	</div>
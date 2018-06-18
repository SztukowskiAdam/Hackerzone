<?php
	include("header.php");
	include("top.php");
	include("menu.php");
?>
<div class="game">
	<article>
		<div class = "rank">


<?php
try
{
	$db_connect= new mysqli($host, $db_user, $db_password, $db_name);

	if($db_connect->connect_errno!=0)
	{
		throw new Exception(mysqli_connect_errno());				
	}
	else
	{	$db_connect->set_charset("utf8");
		$result = $db_connect -> query("SELECT id FROM uzytkownicy ORDER BY id DESC LIMIT 1");

		if(!$result)
		{
			throw new Exception($db_connect->error);
		}
		else
		{
			$userLimit = 100; // limit graczy w całym rankingu
			$line = $result -> fetch_assoc(); 
			if($line['id'] > $userLimit) $howManyUsers = $userLimit;
			else $howManyUsers = $line['id'];

			$usersOnPage = 5; // limit graczy na jednej stronie
			$allPages = ceil($howManyUsers/$usersOnPage);
			$userLevel = $_SESSION['level'];
			$userExp = $_SESSION['exp'];

			// ustawiamy numer strony startowej
			if(!isset($_GET['page']) or $_GET['page'] > $allPages or !is_numeric($_GET['page']) or $_GET['page'] <= 0 or intval($_GET['page']) != $_GET['page'] or is_float($_GET['page']))
			{
			 	$result = $db_connect -> query("SELECT COUNT(id) AS num FROM uzytkownicy WHERE level >= $userLevel");
		        if(!$result)
				{
					throw new Exception($db_connect->error);
				}
				else
				{
					$line = $result -> fetch_assoc();

					if($line['num'] > $userLimit) $page = 1;
					else $page = ceil($line['num']/$usersOnPage);
				}
			}
	        else
	        { 
	        	$page = intval($_GET['page']);
	        	if($page > $allPages) $page = 1;
	        }

	        // wyciągamy dane graczy z rankingu
	        $limit = ($page - 1) * $usersOnPage;

	        if($limit + $usersOnPage > $userLimit) $usersOnPage = $userLimit - $limit;

	        $result = $db_connect -> query("SELECT id, user, level, exp FROM uzytkownicy ORDER BY level DESC, exp DESC LIMIT $limit, $usersOnPage  ");
	        if(!$result)
			{
				throw new Exception($db_connect->error);
			}
			else
			{
				// wyświetlamy tabele
				echo '<table>';
				echo '<tr><td><span style="color:gold">Miejsce</span></td><td><span style="color:gold">Nick</span></td><td><span style="color:gold">Poziom</span></td><td><span style="color:gold">Exp</span></td></tr>';
				$lp = $limit+1;
				while($row = $result -> fetch_assoc())
				{
	                echo '<tr>';
	                echo '<td>'.$lp.'.</td>';
	                $lp ++;

	                if($row['id'] == $_SESSION['id']) echo '<td><a href = "profile.php?uid='.$row['id'].'">'.'**'.$row['user'].'**</a></td>';
	                else echo '<td><a href = "profile.php?uid='.$row['id'].'">'.$row['user'].'</a></td>';

	                echo '<td>'.$row['level'].'</td>';
	                echo '<td>'.$row['exp'].'</td>';
	                echo '</tr>';
       			}

       			echo "</table>";

				$start = 1;

				$prev = $page - 1;
				$next = $page + 1;

		         $scriptName = $_SERVER['SCRIPT_NAME'];

		        echo '<div class = "ranknav">';
				echo "<ul>";

				if($page > 1) echo "<li><a href=\"".$scriptName."?page=1\">&laquo;</a></li>";
				if($page > 1) echo "<li><a href=\"".$scriptName."?page=".$prev."\">&lt;</a></li>";

				echo "<li>".$page."</li>";

				if($page < $allPages) echo "<li><a href=\"".$scriptName."?page=".$next."\">&gt;</a></li>";
				if($page < $allPages) echo "<li><a href=\"".$scriptName."?page=".$allPages."\">&raquo;</a></li>";

				echo "</ul>";
				echo '</div>';

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
	</article>
 </div>

<?php
	include("footer.php");
?>

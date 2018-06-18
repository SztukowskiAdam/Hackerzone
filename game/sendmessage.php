<?php
	include("header.php");
	include("top.php");
	include("menu.php");
?>

<?php

// ---------------------------
// WALIDACJA WIADOMOŚCI
// ---------------------------

$allGood = true;


if(isset($_GET['adress']))
{
	$adressFromGet = $_GET['adress'];

	if(ctype_alnum($adressFromGet) == false)
	{
		$adressFromGet = '';
		$allGood = false;
		$_SESSION['m_error']="Nie ma takiego użytkownika!";
	}
	else
	{
		$adressFromGet = htmlentities($adressFromGet, ENT_QUOTES, "UTF-8");
	}
}

if(isset($_POST['adressed']))
{
	//pobieranie danych

	$adressed = $_POST['adressed'];
	$title = $_POST['title'];
	$text = $_POST['msgContent'];
	$author = $_SESSION['user'];

	// sprawdzanie czy nie piszemy do siebie
	if($author == $adressed) 
	{
		$allGood = false;
		$_SESSION['m_error'] = "Nie możesz pisać wiadomości do siebie!";
	}

	//sprawdzanie długości adresata
	if( (strlen($adressed) < 3) || (strlen($adressed) > 16))
	{
		$allGood=false;
		$_SESSION['m_error']="Nie ma takiego użytkownika!";
	}

	// sprawdzanie poprawności adresata
	if(ctype_alnum($adressed) == false)
	{
		$allGood = false;
		$_SESSION['m_error']="Nie ma takiego użytkownika!";
	}
	else
	{
		$adressed = htmlentities($adressed, ENT_QUOTES, "UTF-8");
	}

	// sprawdzanie tematu

	$title = htmlentities($title, ENT_QUOTES, "UTF-8");
	if(strlen($title) > 30)
	{
		$allGood = false;
		$_SESSION['m_error']="Błędny temat!";
	}
	

	//sprawdzanie treści
	if(trim($text) == '')
	{
		$allGood = false;
		$_SESSION['m_error']="Nie możesz wysłać pustej wiadomości!";

	}

	$text = nl2br(htmlentities($text, ENT_QUOTES, "UTF-8"));
	$author = htmlentities($author, ENT_QUOTES, "UTF-8");

	//zapamiętywanie danych
	$_SESSION['remember_adressed'] = $adressed;
	$_SESSION['remember_title'] = $title;
	$_SESSION['remember_text'] = $text;

	if($allGood)
	{
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
				$adressed = $db_connect -> real_escape_string($adressed);
				$title = $db_connect -> real_escape_string($title);
				$text = $db_connect -> real_escape_string($text);

				$result = $db_connect->query("SELECT id FROM uzytkownicy WHERE user='$adressed'");

				if(!$result)
				{
					throw new Exception($db_connect->error);
				}
				else
				{
					$howManyUsers = $result -> num_rows;

					if($howManyUsers > 0)
					{
						$result = $db_connect -> query("INSERT INTO messages VALUES(NULL,'$author', '$adressed', 0,0,0,'$text', now(),'$title')");
						if(!$result)
						{
							throw new Exception($db_connect->error);
						}
						else
						{
							unset($_SESSION['remember_adressed']);
							unset($_SESSION['remember_text']);
							unset($_SESSION['remember_title']);

							$_SESSION['m_success'] = "Wiadomość wysłana!";
						}
					}
					else
					{
						$_SESSION['m_error'] = "Nie ma takiego użytkownika!";
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
	}
}
?>

<script type="text/javascript">

	$(document).ready(function() {			
		$("#text").keyup(function(){
			var count = $("#text").val().length;
			var text = "Wiadomość ("+count+"/200 znaków): ";
			$(".msglength").text(text);
		});			
	});

</script>

<div class="game">

	<article>
		<div class = "message_form">
			<div class = "msgtitle">root@game: ~</div>
			<div class = "messages_write_btn"><a href = "messages.php">Powrót</a></div>
			<div style = "clear: both"></div>
			<div id="target">		
				<?php
					if(isset($_SESSION['m_error']))
					{
						echo '<span style = "color: red">';
						echo $_SESSION['m_error'];
						unset($_SESSION['m_error']);
					} 
					else if(isset($_SESSION['m_success']))
					{
						echo '<span style = "color: green">';
						echo $_SESSION['m_success'];
						unset($_SESSION['m_success']);
					}
					echo '</span>';
				?>
			</div>
			
			<form method = "post" id = "sendMessage" action = "sendmessage.php">
				<span style="color: green"> root@Nadawca:~# </span><?php echo $_SESSION['user'];?> </br>
				<span style="color: green"> root@Gracz:~#  </span><input type = "text" name = "adressed" 
				<?php
					if(isset($_GET['adress']))
					{
						if(!isset($_SESSION['m_error']))
						{
							echo 'value = "'.$adressFromGet.'"';
							unset($adressFromGet);
						}
						else
						{
							echo $_SESSION['m_error'];
							unset($_SESSION['m_error']);
						}
					}
					else if(isset($_SESSION['remember_adressed']))
					{
						echo 'value = "'.$_SESSION['remember_adressed'].'"';
						unset($_SESSION['remember_adressed']);
					}
				?>
				> </br>
				<span style="color: green"> root@Temat:~# </span> <input type = "text" id ="title" placeholder = "(Nie wymagane)" name = "title"
				<?php
					if(isset($_SESSION['remember_title']))
					{
						echo 'value = "'.$_SESSION['remember_title'].'"';
						unset($_SESSION['remember_title']);
					}
				?>
				> </br>
				<div class = "msglength">Wiadomość (0/200 znaków): </div>
				<textarea id = "text" name = "msgContent" form = "sendMessage" rows="14" cols="50" maxlength="200"></textarea
				<?php
					if(isset($_SESSION['remember_text']))
					{
						echo 'value = "'.$_SESSION['remember_text'].'"';
						unset($_SESSION['remember_text']);
					}
				?>
				> </br>
				<input type = "submit" value = "Wyślij wiadomość">
			</form>
		</div>
	</article>
 </div>

<?php
	include("footer.php");
?>

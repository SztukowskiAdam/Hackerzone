
<?php 
	session_start();

	//sprawdzamy czy nie jesteśmy już zalogowani
	if((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
	{
		header('Location: game/index.php');
		exit();
	}
	// ----------------
	//   rejestracja --
	// ----------------
	if(isset($_POST['email']))
	{
		// pobieranie danych
		$nick=$_POST['nick'];
		$password1=$_POST['password1'];
		$password2=$_POST['password2'];
		$email=$_POST['email'];
		$allgood=true;

		// sprawdzenie długości nicka
		if( (strlen($nick)<3) || (strlen($nick)>16))
		{
			$allgood=false;
			$_SESSION['e_nick']="Nick musi mieć od 3 do 16 znaków!";

		// sprawdzanie poprawności nicka
		}
		if(ctype_alnum($nick)==false)
		{
			$allgood=false;
			$_SESSION['e_nick']="Nick może składać się tylko z liter oraz cyfr (bez polskich znaków)!";
		}

		// sprawdzanie poprawności e-maila
		$email2=filter_var($email, FILTER_SANITIZE_EMAIL);

		if((filter_var($email2, FILTER_VALIDATE_EMAIL)==false) || ($email2!=$email))
		{
			$allgood=false;
			$_SESSION['e_email']="Sprawdź poprawność e-maila!";
		}

		// sprawdzanie poprawności haseł
		if((strlen($password1)<8) || strlen($password1)>20)
		{
			$allgood=false;
			$_SESSION['e_password']="Hasło powinno mieć od 8 do 20 znaków!";
		}
		if($password1!=$password2)
		{
			$allgood=false;
			$_SESSION['e_password']="Podane hasła nie są identyczne!";
		}

		$password_hash=password_hash($password1, PASSWORD_DEFAULT);

		// sprawdzanie zaznaczenia checkboxa
		if(!isset($_POST['regulamin']))
		{
			$allgood=false;
			$_SESSION['e_regulamin']="Powinieneś potwierdzić regulamin ;)";
		}

		// walidacja captchy

		$secret="6Len4ycUAAAAAENx_vABYsUI-RnYDOIt4i0V6E9j";
		$check=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);

		$answer=json_decode($check);

		if($answer->success==false)
		{
			$allgood=false;
			$_SESSION['e_recaptcha']="Powinieneś potwierdzić reCapche";;

		}

		// zapamiętywanie wpisanych danych
		$_SESSION['remember_nick']=$nick;
		$_SESSION['remember_email']=$email;
		$_SESSION['remember_password1']=$password1;
		$_SESSION['remember_password2']=$password2;
		if(isset($_POST['regulamin'])) $_SESSION['remember_regulamin']=true;

		// weryfikacja czy istnieją już wpisane takie dane

		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);

		try
		{
			$db_connect= new mysqli($host, $db_user, $db_password, $db_name);

			if($db_connect->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
				
			}
			else
			{

				// sprawdzanie czy e-mail istnieje
				$result=$db_connect->query("SELECT id FROM uzytkownicy WHERE email='$email'");

				if(!$result) throw new Exception($db_connect->error);


				$how_many_mails= $result->num_rows;
				if($how_many_mails>0)
				{
					$allgood=false;
					$_SESSION['e_email']="Podany e-mail już istnieje!";

				}

					// sprawdzanie czy login istnieje
				$result=$db_connect->query("SELECT id FROM uzytkownicy WHERE user='$nick'");

				if(!$result) throw new Exception($db_connect->error);


				$how_many_users= $result->num_rows;
				if($how_many_users>0)
				{
					$allgood=false;
					$_SESSION['e_nick']="Podany Nick już istnieje!";

				}

				// Udało się zwalidować gracza
				if($allgood==true)
				{	
					if(!$db_connect->query("INSERT INTO uzytkownicy VALUES(NULL,'$nick','$password_hash','$email',1,100,0,now() + INTERVAL 14 DAY, 0, 5, 5, 5, 5)"))
					{
						throw new Exception($db_connect->error);
					}
					else
					{
						if(isset($_SESSION['remember_nick'])) unset($_SESSION['remember_nick']);
						if(isset($_SESSION['remember_password1'])) unset($_SESSION['remember_password1']);
						if(isset($_SESSION['remember_password2'])) unset($_SESSION['remember_password2']);
						if(isset($_SESSION['remember_regulamin'])) unset($_SESSION['remember_regulamin']);
						if(isset($_SESSION['remember_email'])) unset($_SESSION['remember_email']);

						$_SESSION['good_register']='<br /><span style="color: green"><b>Brawo! Teraz możesz się zalogować!</b></span>';
					}
				}


				$db_connect-> close();
			}

		}
		catch(Exception $e)
		{
			$_SESSION['Exception']='<span style="color: red">Błąd serwera! Spróbuj później.</span>';
			//echo '<span style="color: red">Błąd serwera! Przepraszamy za niedogodności.</span>';
			//echo '<br /> Info: '.$e;
		}

	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<title>Hack- podbij cyber świat!</title>
	
	<meta name="description" content="Opis w Google" />
	<meta name="keywords" content="słowa, kluczowe, wypisane, po, porzecinku" />

	<link rel="stylesheet" href="style.css" type="text/css" />

	<link href="https://fonts.googleapis.com/css?family=Lobster&amp;subset=latin-ext" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Fira+Mono" rel="stylesheet">

	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script type="text/javascript" src="tab.js"></script>    


	<!--[if lt IE 9]>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
</head>
<body>

<div id="container">
	<header>

			<div class="logo"></div>			
		
	</header>

		<div class="register">
			<form method="post">

				<b>Nickname</b> <br /> <input type="text" name="nick" 
				value="<?php 
				if(isset($_SESSION['remember_nick']))
				{
				 echo $_SESSION['remember_nick'];
				 unset($_SESSION['remember_nick']);
				}
				?>"
				/> <br />

				<?php
					if(isset($_SESSION['e_nick']))
					{
						echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
						unset($_SESSION['e_nick']);
					}
				?>

				<b>Hasło</b> <br /> <input type="password" name="password1"
				value="<?php 
				if(isset($_SESSION['remember_password1']))
				{
				 echo $_SESSION['remember_password1'];
				 unset($_SESSION['remember_password1']);
				}
				?>"
				 /> <br />
				<?php
					if(isset($_SESSION['e_password']))
					{
						echo '<div class="error">'.$_SESSION['e_password'].'</div>';
						unset($_SESSION['e_password']);
					}
				?>

				<b>Powtórz hasło</b> <br /> <input type="password" name="password2"
				value="<?php 
				if(isset($_SESSION['remember_password2']))
				{
				 echo $_SESSION['remember_password2'];
				 unset($_SESSION['remember_password2']);
				}
				?>"
				 /> <br />

				<b>E-mail</b> <br /> <input type="text" name="email"
				value="<?php 
				if(isset($_SESSION['remember_email']))
				{
				 echo $_SESSION['remember_email'];
				 unset($_SESSION['remember_email']);
				}
				?>"
				 /> <br />
				<?php
					if(isset($_SESSION['e_email']))
					{
						echo '<div class="error">'.$_SESSION['e_email'].'</div>';
						unset($_SESSION['e_email']);
					}
				?>
				<br />

				<label>
				<input type="checkbox" name="regulamin"

				<?php 
				if(isset($_SESSION['remember_regulamin']))
				{
				 echo "checked";
				 unset($_SESSION['remember_regulamin']);
				}
				?>
				/> <b>Akceptuję regulamin</b> <br />
				</label>
				<?php
					if(isset($_SESSION['e_regulamin']))
					{
						echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
						unset($_SESSION['e_regulamin']);
					}
				?>
				<br/>

				<div class="g-recaptcha" data-sitekey="6Len4ycUAAAAAG5MAGY1-ZZhfDFXIuNJNYjr18x_"></div>
				<br />
				<?php
					if(isset($_SESSION['e_recaptcha']))
					{
						echo '<div class="error">'.$_SESSION['e_recaptcha'].'</div>';
						unset($_SESSION['e_recaptcha']);
					}
				?>

				<input type="submit" value="Zarejestruj się!" />
				<?php
					if(isset($_SESSION['good_register']))
					{
						echo $_SESSION['good_register'];
						unset($_SESSION['good_register']);
					}
				?>

			</form>
		</div>

		<div class="description">
			<div class="top-bar">root@game: ~</div>
			<div class="nav">
				<ul>
					   	<li><a href="#" onclick="wymienTresc('1', 'main');">Start</a></li>
					    <li><a href="#" onclick="wymienTresc('2', 'main');">GameOver</a></li>
					    <li><a href="#" onclick="wymienTresc('3', 'main');">Media</a></li>
					    <li><a href="#" onclick="wymienTresc('4', 'main');">Forum</a></li>
				</ul>
			</div>

			<main>
			<div id="main">
				./passwdscript.sh </br></br>
				ERROR: Something went wrong! Stopping the program </br></br>
				<span style="color: red"> root@game:~# </span> I'm watching you... </br>
				<span style="color: red"> root@game:~# </span> Mam Ci coś do przekazania. Pamiętaj, że to poufna informacja. Obserwowałem Cię od pewnego czasu i myślę, że się nadasz. </br>
				<span style="color: red"> root@game:~# </span> Nie zawiedź mnie<span class="blinking-cursor">|</span>
			</div>
				
			</main>
		</div>

		<div class="login">

					<div class="zaloguj">
						<form method="post" action="zaloguj.php">
						<b>Login</b> </br>
						<input type="text" name="login">
						</br>
						<b>Hasło</b> </br>
						<input type="password" name="pass">
						</br>
						<input type="submit" value="Play">
						</form>
					</div>

					<div class="err">
					<?php
						if(isset($_SESSION['error']))
						{
							echo $_SESSION['error'];
							unset($_SESSION['error']);
						}
						else if(isset($_SESSION['Exception']))
						{
							echo $_SESSION['Exception'];
							unset($_SESSION['Exception']);
						}
					?>
					</div>


				</div>
	<footer>

	</footer>

</div>	

</body>
</html>
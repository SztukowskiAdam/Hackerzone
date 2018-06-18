<?php
session_start();

	if(!isset($_SESSION['logged']))
	{
		header('Location:../index.php');
		exit();
	}
	$name="/hackerzone/game/header.php";
	if($_SERVER['PHP_SELF'] == $name)
	{
		header('Location:index.php');
		exit();
	}

	require_once("../connect.php");
	mysqli_report(MYSQLI_REPORT_STRICT);
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
	
	<title>Hackersss- podbij cyber świat!</title>
	
	<meta name="description" content="Opis w Google" />
	<meta name="keywords" content="słowa, kluczowe, wypisane, po, porzecinku" />

	<link rel="stylesheet" href="gamestyle.css" type="text/css" />
	<link rel="stylesheet" href="css/fontello.css" type="text/css" />

	<link href="https://fonts.googleapis.com/css?family=Fira+Mono" rel="stylesheet">

	<script type="text/javascript" src="scripts.js"></script> 

	<script 
	src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous">	
	</script>
	
</head>
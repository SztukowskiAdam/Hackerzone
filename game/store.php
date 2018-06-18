<?php
	include("header.php");
	include("top.php");
	include("menu.php");
?>
<script type="text/javascript">
	
	var name = "magia";
	var type = "";
	$(document).keypress(function(event){

		var keycode = (event.keyCode ? event.keyCode : event.which);
		var x = String.fromCharCode(keycode);

		type+=x;
		if(name == type) window.location.replace("https://www.google.pl/search?biw=1536&bih=759&tbm=isch&sa=1&q=krawczyk&oq=krawczyk&gs_l=psy-ab.3..0l10.938.2274.0.2699.8.8.0.0.0.0.133.772.1j6.7.0....0...1.1.64.psy-ab..1.7.768....0.mNhE1x2UNyc#imgrc=4Un9CmrnOUpO7M:");
	});
</script>

<div class="game">
	<header>
	Sklep komputerowy
	</header>

	<article>
		Sklep komputerowy
	</article>
 </div>

<?php
	include("footer.php");
?>

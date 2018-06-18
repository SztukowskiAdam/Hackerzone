<?php
	include("header.php");
	include("top.php");
	include("menu.php");
?>


<div class="game">
	<article>
		<div id="blad"></div>
		<div class = "messages_received_btn" onclick = "showAllMessages('received')">Otrzymane</div>
		<div class = "messages_sent_btn" onclick = "showAllMessages('sent')">Wysłane</div>
		<div class = "messages_write_btn"><a href = "sendmessage.php">Napisz wiadomość</a></div>
		<div style = "clear:both"></div>

		<div class = "messages_received">

<?php
	try
	{
		$db_connect= new mysqli($host, $db_user, $db_password, $db_name);

		if($db_connect->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
			echo '</div>';				
		}

		else
		{	
			$db_connect->set_charset("utf8");
			$userName = $_SESSION['user'];

			$result = $db_connect -> query("SELECT id, message, sender, topic, sent, adressed_readed FROM messages WHERE adressed = '$userName' AND adressed_delete = 0");
			if(!$result)
			{
				throw new Exception($db_connect->error);
			}
			else
			{
				$howManyMessages = $result -> num_rows;

				if($howManyMessages == 0)
				{
					echo "NIE DOSTAŁEŚ WIADOMOŚCI</div>";
				}
				else
				{
					
					while($line = $result -> fetch_assoc())
					{
						echo '<div class="message">';
						echo '<div class = "message_info" onclick = "showMessage(this)" data-mid ="'.$line['id'].'" data-readed = "'.$line['adressed_readed'].'"';
						if($line['adressed_readed']== 0) echo ' style = "font-weight: bold">';
							else echo ">";
						echo 'Od: '.$line['sender'].' Temat: '.$line['topic'].' Dostarczono: '.$line['sent'];
						echo '</div>';

						echo '<div class = "message_content">';
						echo $line['message'];
						echo '</br>'.'<a onclick = "deleteMessage(this, '.$line['id'].')">Skasuj</a>';
						echo '</div></div>';
					}
					echo '</div>';
				}
				
				echo '<div class = "messages_sent">';

				$result = $db_connect -> query("SELECT id, adressed, message, adressed, topic, sent  FROM messages WHERE sender = '$userName' AND sender_delete = 0");
				if(!$result)
				{
					throw new Exception($db_connect->error);
				}
				else
				{
					$howManyMessages = $result -> num_rows;

					if($howManyMessages == 0)
					{
						echo "NIE WYSŁAŁEŚ WIADOMOŚCI</div>";
					}
					else
					{
						
						while($line = $result -> fetch_assoc())
						{
							echo '<div class="message">';
							echo '<div class = "message_info" onclick = "showMessage(this)" data-mid ="'.$line['id'].'">';
							echo 'Do: '.$line['adressed'].' Temat: '.$line['topic'].' Dostarczono: '.$line['sent'];
							echo '</div>';

							echo '<div class = "message_content">';
							echo $line['message'];
							echo '</br>'.'<a onclick = "deleteMessage(this, '.$line['id'].')">Skasuj</a>';
							echo '</div></div>';
						}
						echo '</div>';
					}
				}
			}
		}

		$db_connect -> close();
	}
	catch(Exception $e)
	{
		echo $e;
	}
?>

	</article>
 </div>

<?php
	include("footer.php");
?>

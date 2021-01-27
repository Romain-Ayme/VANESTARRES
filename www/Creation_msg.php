<?php
echo '
	<form action="insertion_db.php" method="post" enctype="multipart/form-data">', PHP_EOL,
	'</t><label for="message">Message:</label><br>', PHP_EOL,
	'</t><input type="text" name="msg" placeholder="Ecrit ton message ici...">', PHP_EOL,
	'</t><input type="file" name="img" accept=".png, .jpg, .jpeg, .gif"><br/>', PHP_EOL,
	'</t><input type="submit" value="Envoyer">', PHP_EOL,
	'</form>', PHP_EOL;
?>
	

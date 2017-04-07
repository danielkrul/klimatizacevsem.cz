<?php
if(!isset($_SESSION)){ 
	session_start(); 
} 

$db_file = 'data_klaga.db';
$db = new SQLite3($db_file);
$results = $db->query('SELECT * FROM users');

if(isset($_SESSION['name']) && isset($_SESSION['password'])){
	$ifPassOkSession = false;

	while ($row = $results->fetchArray()) {
		if($row['name'] == $_SESSION['name'] && $row['password'] == $_SESSION['password']){
			$ifPassOkSession = true;
		}
	}

	if(!$ifPassOkSession){
		echo 'Špatná session, přihlašte se znovu';
		die();
	}
} else {
	echo 'Neexistující session';
	die();
}
?>

<div id="visibilityContainer">
	<div class="center">
		<h2>Změnit heslo <a href="login_complete.php"><img class="close" src="./design/icons/close.png" width="25" /></a></h2>
		<form method="POST">
		<table>
			<tr>
				<td>Původní heslo: </td>
				<td><input type="password" name="last_password" autofocus /></td>
			</tr>
			<tr>
				<td>Nové heslo: </td>
				<td><input type="password" name="new_password" /></td>
			</tr>

			<tr>
			<td>Nové heslo pro kontrolu: </td>
				<td><input type="password" name="new_password_2" /></td>
			</tr>
			<tr>
				<td>
					<button class="add_button" name="change_password">Změnit heslo</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>
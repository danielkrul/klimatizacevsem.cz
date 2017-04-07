<?php
session_start();

if(isset($_POST['submit'])){
	$db_file = 'data_klaga.db';
	$db = new SQLite3($db_file);
	$results = $db->query('SELECT * FROM users');

	$ifPassOk = false;

	while ($row = $results->fetchArray()) {
		if($row['name'] == $_POST['name'] && $row['password'] == hash('sha256', $_POST['password'])){
			$ifPassOk = true;
		}
	}

	if($ifPassOk){
		$_SESSION['name'] = $_POST['name'];
		$_SESSION['password'] = hash('sha256', $_POST['password']);
		$_SESSION['admin'] = true;

		header('Location: login_complete.php');
	} else {
		echo '<script>alert("Špatné jméno nebo heslo!");</script>';
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="author" content="Daniel Krul">
	<link rel="shortcut icon" href="favicon.png" type="image/x-icon">
	<link rel="icon" href="favicon.png" type="image/x-icon">
	<meta name="keywords" content="e-shop, klimatizace, topení, ostrava">

	<title>Login</title>

	<link rel="stylesheet" type="text/css" href="./css/login.css">
	<script type="text/javascript" src="./js/jquery.js"></script>
	<script type="text/javascript" src="./js/main.js"></script>
</head>
<body>
	<div id="loginPanel">
		<form method="POST">
			<table>
				<tr>
					<td>Jméno: </td> 
					<td><input type="text" name="name" placeholder="Login" autofocus /></td>
				</tr>
				<tr>
					<td>Heslo: </td> 
					<td><input type="password" name="password" /></td>
				</tr>
				<tr>
					<td><button name="submit" type="submit">Přihlásit</button></td>
				</tr>
			</table>
		</div>
	</form>
</body>
</html>
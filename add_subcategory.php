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
		<h2>Přidat podkategorii <a href="login_complete.php"><img class="close" src="./design/icons/close.png" width="25" /></a></h2>
		<form method="POST">
		<table>
			<tr>
				<td>Název podkategorie: </td>
				<td><input type="text" name="subcategory" autofocus /> <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>" /></td>
			</tr>
			<tr>
				<td>
					<button class="add_button" name="add_subcategory">Přidat podkategorii</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>
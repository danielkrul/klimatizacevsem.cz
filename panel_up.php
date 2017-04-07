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

<script src="./js/tinymce/tinymce.min.js"></script>

<script>
  tinymce.init({
    selector: '.content_textarea',
    plugins : 'advlist autolink link image lists charmap print preview',
    toolbar: 'bold, italic, underline, strikethrough, alignleft, aligncenter, alignright, alignjustify, styleselect, fontsizeselect, bullist, numlist, outdent, indent',
  	fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
    language: 'cs'
  });
  </script>

<div id="visibilityContainer">
	<div class="center">
		<h2>Přidat panel <a href="login_complete.php"><img class="close" src="./design/icons/close.png" width="25" /></a></h2>
		<form method="POST">
		<table>
			<tr>
				<td>Název panelu: </td>
				<td><input maxlength="24" type="text" name="panel_name" autofocus placeholder="Název panelu" /></td>
			</tr>
			<tr>
				<td>
					Obsah: 
				</td>
				<td>
					<textarea name="input" class="content_textarea" ></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<button class="add_button" name="post_up">Přidat</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>
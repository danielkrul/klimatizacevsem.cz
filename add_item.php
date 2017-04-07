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
    selector: '.description',
    plugins : 'advlist autolink link image lists charmap print preview table textcolor colorpicker',
    toolbar: 'bold, italic, underline, strikethrough, alignleft, aligncenter, alignright, alignjustify, styleselect, fontsizeselect, bullist, numlist, outdent, indent, forecolor',
  	fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
    language: 'cs'
  });
</script>

<div id="visibilityContainer">
	<div class="center">
		<h2>Přidat produkt <a href="login_complete.php"><img class="close" src="./design/icons/close.png" width="25" /></a></h2>
		<form method="POST" enctype="multipart/form-data">
		<table>
			<tr>
				<td>Název produktu: </td>
				<td>
					<input type="text" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" autofocus />
					<input type="hidden" name="category" value="<?php echo $_GET['add_item']; ?>" />
					<input type="hidden" name="subcategory" value="<?php echo $_GET['subcategory']; ?>" />
				</td>
			</tr>

			<tr>
				<td>Popisek produktu: </td>
				<td>
					<textarea name="caption"><?php echo isset($_POST['caption']) ? $_POST['caption'] : ''; ?></textarea>
				</td>
			</tr>

			<tr>
				<td>Cena produktu: </td>
				<td>
					<input type="number" name="price" value="<?php echo isset($_POST['price']) ? $_POST['price'] : '0'; ?>" />
				</td>
			</tr>

			<tr>
				<td>Počet kusů na skladě: </td>
				<td>
					<input type="number" name="stock"  value="<?php echo isset($_POST['stock']) ? $_POST['stock'] : '1'; ?>" />
				</td>
			</tr>

			<tr>
				<td>Úvodní obrázek: </td>
				<td>
					<input type="file" name="image" />
				</td>
			</tr>

			<tr>
				<td>Měna: </td>
				<td>
					<input type="text" name="currency" value="<?php echo isset($_POST['currency']) ? $_POST['currency'] : 'Kč'; ?>" />
				</td>
			</tr>

			<tr>
				<td>Detailní popis: </td>
				<td>
					<textarea name="description" class="description"><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
				</td>
			</tr>

			<tr>
				<td>Parametry produktu: </td>
				<td>
					<textarea name="params" class="description"><?php echo isset($_POST['params']) ? $_POST['params'] : ''; ?></textarea>
				</td>
			</tr>

			<tr>
				<td>Produkt je doporučený: </td>
				<td>
					<input type="hidden" name="recommended" value="0" />
					<input type="checkbox" name="recommended" value="1">
				</td>
			</tr>

			<tr>
				<td>YouTube video URL: </td>
				<td>
					<input type="text" name="video" value="<?php echo isset($_POST['video']) ? $_POST['video'] : ''; ?>" />
				</td>
			</tr>

			<tr>
				<td>
					<button class="add_button" name="add_item">Přidat produkt</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>
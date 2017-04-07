<?php
$item_result = $db->query('SELECT * FROM items WHERE id="'. $_GET['view_item'] .'" ');
$item_info = $item_result->fetchArray();
$price_changed = number_format($item_info['price'], 2, ',', ' ');
$price_without_dph = $item_info['price'] - ($item_info['price'] * 0.15);
$price_without_dph = number_format($price_without_dph, 2, ',', ' ');

if (isset($_POST['buy_item'])) {
	$buy_item = $db->exec('INSERT INTO cart (name, count, price, item_id, ip) VALUES ("'. $item_info['name'] .'", "'. $_POST['number'] .'", "'. $item_info['price'] * $_POST['number'] .'", "'. $item_info['id'] .'", "'. $_SERVER['REMOTE_ADDR'] .'" )');

	if($buy_item){
		echo '<script> alert("Produkt vložen do košíku."); </script>';
		echo '<script> window.location.href = "?view_item='. $_GET['view_item'] .'"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
	}
}

?>

<div class="itemInfo">
	<h1><?php echo $item_info['name']; ?> <a href="index.php"><img class="rightHome" src="./design/icons/home.png" title="Hlavní strana" width="30" /></a></h1>

	<div class="category_tree">
		<menu>
		<?php
		echo '<a href="?category='. $item_info['category'] .'">'. $item_info['category'] .' <img src="./design/icons/arrow_2.png" width="8" /></a>';
		echo '<a href="?from_category='. $item_info['category'] .'&to='. $item_info['sub_category'] .'">'. $item_info['sub_category'] .'</a>';

		?>
		</menu>
	</div>

	<div class="left">
		<img src="<?php echo $item_info['image']; ?>" />
		<div class="video">
			<?php echo html_entity_decode($item_info['video']); ?>
		</div>
	</div>

	<div class="right">
		<p>
		<?php echo $item_info['caption']; 
		?>
		</p>
		<hr />

		<table>
			<tr>
				<td>Cena s DPH: </td>
				<td class="price">
				<?php 
				echo $price_changed . ' ' . $item_info['currency']; 
				?>
				</td>
			</tr>

			<tr>
				<td>Cena bez DPH: </td>
				<td class="dph">
				<?php 
				echo $price_without_dph . ' ' . $item_info['currency']; 
				?>
				</td>
			</tr>
		</table>

		<div class="all">
			<table>
			<tr>
				<td>Skladem: </td>
				<td class="stock">
				<?php 
					if($item_info['stock'] > 0){
						echo '<p style="color: green;"><strong>Produkt je skladem ('. $item_info['stock'] .') </strong></p>';
					} else {
						echo '<p style="color: red;"><strong>Produkt není skladem.</strong></p>';
					}
				?>
				</td>
			</tr>

			<tr>
				<td>
					Počet kusů: 
				</td>
				<td class="number">
				<form method="POST">
				<?php
					if($item_info['stock'] > 0){
						echo '<input type="number" name="number" value="1" min="1" max="15" />';
					} else {
						echo '<input type="number" name="number" value="1" min="1" max="15" disabled />';
					}
				?>
					
				</td>
			</tr>

			<tr>
				<td>
					 
				</td>
				<td class="number">
				<?php
					if($item_info['stock'] > 0){
						echo '<button type="submit" name="buy_item">Vložit do košíku</button>';
					} else {
						//echo '<button disabled>Zakoupit</button>';
					}
				?>
				</form>
				</td>
			</tr>
		</table>
		</div>
	</div>

	<div style="clear: both;"></div>

	<div class="bigPanel">
		<menu class="panels">
			<a href="?view_item=<?php echo $item_info['id']; ?>#content">Detailní popis</a>
			<a href="?view_item=<?php echo $item_info['id']; ?>&params#content">Parametry</a>
			<a href="?view_item=<?php echo $item_info['id']; ?>&files#content">Přílohy</a>
		</menu>
		<hr />

		<div id="content">
			<?php
			if(isset($_GET['view_item']) && isset($_GET['files'])){
				$count_files = 0;

				$results_files = $db->query('SELECT * FROM files WHERE item_id="'. $_GET['view_item'] .'" ');

				while ($row_files = $results_files->fetchArray()) {
					echo '<a href="'. $row_files['url'] .'"> '. $row_files['file_name'] .'</a><br />';
					$count_files++;
				}

				if($count_files == 0){
					echo '<div class="error">Nepřiložen žádný soubor</div>';
				}
			}

			if(isset($_GET['view_item']) && isset($_GET['params'])){
				$item_info['params'] = html_entity_decode($item_info['params']);
				echo $item_info['params'];
			}

			if(isset($_GET['view_item']) && !isset($_GET['files']) && !isset($_GET['params'])){
				$item_info['description'] = html_entity_decode($item_info['description']);
				echo $item_info['description'];
			}

			?>
		</div>
	</div>

</div>
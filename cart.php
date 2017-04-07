<div class="article">
	<h1 class="head">Košík<a href="index.php"><img class="right" src="./design/icons/home.png" title="Hlavní strana" width="30" /></a></h1>

	<div class="cart_table">
		<table>
			<thead>
				<tr>
					<th>Název produktu: </th>
					<th>Cena: </th>
					<th>Počet kusů: </th>
					<th>Akce: </th>
				</tr>
			</thead>

			<tbody>
			<?php
				$results_cart = $db->query('SELECT * FROM cart WHERE ip="'. $_SERVER['REMOTE_ADDR'] .'" ORDER BY id');
				$count_cart = 0;
				$price_all = 0;

				while ($row_cart = $results_cart->fetchArray()) {
					echo '<tr>';
					echo '<td> <a style="color: black; text-decoration: none;" target="_blank" href=?view_item='. $row_cart['item_id'] .'>'. $row_cart['name'] .'</a></td>';
					echo '<td>'. number_format($row_cart['price'], 2, ',', ' ') . ' Kč' .'</td>';
					echo '<td style="text-align: center;">'. $row_cart['count'] .'</td>';
					echo '<td><a href="?delete_cart='. base64_encode(base64_encode(base64_encode($row_cart['id']))) .'" title="Smazat položku"><img src="./design/icons/del.png" width="16" /></a> </td>';
					echo '</tr>';

					$count_cart += $row_cart['count'];
					$price_all += $row_cart['price'];
				}

			?>					
			</tbody>
		</table>

		<?php
			if($count_cart == 0){
				echo '<div class="error">Košík je prázdný!</div>';
			}
		?>

		<table style="text-align: center; width: 60%; margin: 0 auto; margin-top: 3em">
			<thead>
				<tr>
					<th>Počet kusů celkem: </th>
					<th>Celková cena: </th>
					<th>Akce: </th>
				</tr>
			</thead>

			<tbody>
				<tr style="font-weight: bold;">
					<td>
						<?php echo $count_cart ?>
					</td>

					<td>
						<?php echo number_format($price_all, 2, ',', ' ') ?> Kč
					</td>

					<td>
						<a href="?delete_all_cart=<?php echo base64_encode($_SERVER['REMOTE_ADDR']) ?>"><img src="./design/icons/trash.png" width="30" title="Vysypat košík" /></a>

						<a href="<?php echo $count_cart == 0 ? '' : '?complete_cart='. base64_encode($_SERVER['REMOTE_ADDR']) .''; ?>"><img src="./design/icons/cart_complete.png" width="30" title="Dokončit nákup" /></a>
					</td>
				</tr>
			</tbody>
		</table>

	</div>
</div>
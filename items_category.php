<div class="sort">
	<form method="POST">
		Seřadit podle: 

		<select class="sort_select" name="sort_select">
			<option value="recommended">Doporučeno</option>
			<option value="low_price"
			<?php
			if($sort_selected){
				if($_POST['sort_select'] == 'low_price'){
					echo 'selected';
				}
			}
			?>
			>Nejnižší ceny</option>
			<option value="high_price"
			<?php
			if($sort_selected){
				if($_POST['sort_select'] == 'high_price'){
					echo 'selected';
				}
			}
			?>
			>Nejvyšší ceny</option>						
		</select>
		<button type="submit" name="sort">Seřaď!</button>
	</form>
</div>

<div class="itemsSorted">

	<?php

	$count_items = 0;

	for ($i = 0; $i < count($items); $i++) { 
		if($items[$i]['category'] == $_GET['from_category'] && $items[$i]['sub_category'] == $_GET['to']){
			echo '<div>';
			echo '<a href="?view_item='. $items[$i]['id'] .'" style="text-decoration: none; color: black;" ><p class="name">' . $items[$i]['name'] . '</p></a>';

			if($items[$i]['recommended']){
				echo '<div class="image"><img src="'. $items[$i]['image'] .'" width="200" height="170" /><div class="star"><img src="./design/icons/star.png" width="25" height="25" title="Doporučujeme!" /></div></div>'; 
			} else {
				echo '<div class="image"><img src="'. $items[$i]['image'] .'" width="200" height="170" /></div>'; 
			}

			echo '<div class="caption">';
			for ($c = 0; $c < 9; $c++) { 
				if($c == 9 - 1){
					echo $items[$i]['caption'][$c] . '...';
				} else {
					echo $items[$i]['caption'][$c] . ' ';
				}

			}
			echo '</div>';

			echo '<div class="button"><span class="price">'. number_format($items[$i]['price'], 2, ',', ' ') . ' '. $items[$i]['currency'] . '</span> <a href="">Koupit</a></div>';

			echo '</div>';

			$count_items++;
		}
		
	}

	if($count_items == 0){
		echo '<div class="error">Nebyla nalezena žádná položka</div>';
	}

	?>
	
</div>
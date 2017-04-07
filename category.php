<div class="itemInfo">
	<h1>Kategorie: 
	
	<?php
		echo $_GET['category'];
	?>

	<a href="index.php"><img class="rightHome" src="./design/icons/home.png" title="Hlavní strana" width="30" /></a></h1> 

	<div class="innerBig">
	<?php
	$count_subcategories_detail = 0;

	$results_subcategories_detail = $db->query('SELECT * FROM sub_categories WHERE from_="'. $_GET['category'] .'"');

		while ($row_subcategories_detail = $results_subcategories_detail->fetchArray()) {
			echo '<div class="innerItem">';
			echo '<a href="?from_category='. $row_subcategories_detail['from_'] .'&to='. $row_subcategories_detail['category'] .'"><img class="up" src="./design/icons/subcategory.png" />';
			echo '<h2>' . $row_subcategories_detail['category'] . '</h2>';
			echo '</a>';
			echo '</div>';

			$count_subcategories_detail++;
		}
	
	if($count_subcategories_detail == 0){
		echo '<div class="error">Nebyla nalezena žádná podkategorie</div>';
	}

	?>
		
	</div>
</div>
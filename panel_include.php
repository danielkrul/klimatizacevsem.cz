<?php
	$content_panel = $db->query('SELECT * FROM panel_up WHERE id="'. $_GET['panel_id'] .'" ');
	$row_content_panel = $content_panel->fetchArray();

	$row_content_panel['content'] = html_entity_decode($row_content_panel['content']);

	echo '<div class="article">';
	echo '<h1 class="head">'. $row_content_panel['name'] .'<a href="index.php"><img class="right" src="./design/icons/home.png" title="Hlavní strana" width="30" /></a></h1>';
	echo '<p class="content">'. $row_content_panel['content'] .'</p>';
	echo '</div>';
?>
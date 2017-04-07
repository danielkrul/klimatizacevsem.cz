<?php
if(!isset($_SESSION)){ 
	session_start(); 
} 

$db_file = 'data_klaga.db';
$db = new SQLite3($db_file);
$results = $db->query('SELECT * FROM main_settings');

$settings = array();

while ($row = $results->fetchArray()) {
	array_push($settings, array(
        'site_name' => $row['site_name'], 
        'site_currency' => $row['site_currency'], 
        'location' => $row['location'],
        'email' => $row['email'],
        'phone' => $row['phone'],
        'ico' => $row['ico'],
        'youtube' => $row['youtube'],
        'name' => $row['name'],
        'dic' => $row['dic']
    ));

}

$cart_message = $db->querySingle('SELECT COUNT(*) as count FROM cart WHERE ip="'. $_SERVER['REMOTE_ADDR'] .'" ');

$results_menu = $db->query('SELECT * FROM categories');
$categories = array();

while ($row_category = $results_menu->fetchArray()) {
	array_push($categories, $row_category['category']);
}

$sort = 'recommended';
$desc = 'DESC';
$sort_selected = false;

if(isset($_POST['sort'])){
	$stop = false;
	$sort_selected = true;

	if($_POST['sort_select'] == 'low_price'){
		$sort = 'price';
		$desc = '';

		$stop = true;
	}

	if($_POST['sort_select'] == 'high_price'){
		$sort = 'price';
		$desc = 'DESC';

		$stop = true;
	}

	if(!$stop){
		$sort = $_POST['sort_select'];
	}
	
}

$items = array();
$results_items = $db->query('SELECT * FROM items ORDER BY '. $sort .' '. $desc .'');

while ($row_items = $results_items->fetchArray()) {

	if($row_items['stock'] == 1){
		$stock = 'Je skladem';
	} else {
		$stock = 'Není skladem';
	}

	if($row_items['recommended'] == 1){
		$recommended = true;
	} else {
		$recommended = false;
	}

	$caption = explode(' ', $row_items['caption']);

	array_push($items, array(
		'id' => $row_items['id'],
        'name' => $row_items['name'], 
        'price' => $row_items['price'], 
        'stock' => $stock,
        'image' => $row_items['image'],
        'recommended' => $recommended,
        'currency' => $row_items['currency'],
        'caption' => $caption,
        'popularity' => $row_items['popularity'],
        'category' => $row_items['category'],
        'sub_category' => $row_items['sub_category'],
        'description' => $row_items['description'],
        'params' => $row_items['params']
    ));
}

$admin = false;

if(isset($_SESSION['admin'])){
	$admin = true;
}

$panel_up = array();
$results_panel = $db->query('SELECT * FROM panel_up');

while ($row_panel_up = $results_panel->fetchArray()) {
	array_push($panel_up, array(
        'name' => $row_panel_up['name'], 
        'id' => $row_panel_up['id']
    ));
}

$panel_content = false;

if(isset($_GET['panel_id'])){
	$panel_content = true;
}

$view_item = false;

if(isset($_GET['view_item'])){
	$view_item = true;
}

$category = false;

if(isset($_GET['category'])){
	$category = true;
}

$sub_category_item = false;

if(isset($_GET['from_category']) && isset($_GET['to'])){
	$sub_category_item = true;
}

$cart = false;

if(isset($_GET['cart'])){
	$cart = true;
}

if(isset($_GET['delete_cart'])){
	$delete_cart = $db->exec('DELETE FROM cart WHERE id = "'. base64_decode(base64_decode(base64_decode($_GET['delete_cart']))) .'"');

	if($delete_cart){
		echo '<script>alert("Položka smazána!"); </script>';
		echo '<script> window.location.href = "?cart"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
		echo '<script> window.location.href = "?cart"; </script>';
	}

}

if(isset($_GET['delete_all_cart'])){
	$delete_all_cart = $db->exec('DELETE FROM cart WHERE ip = "'. base64_decode($_GET['delete_all_cart']) .'"');

	if($delete_all_cart){
		echo '<script>alert("Košík vysypán!"); </script>';
		echo '<script> window.location.href = "?cart"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
		echo '<script> window.location.href = "?cart"; </script>';
	}

}

$complete_cart = false;

if(isset($_GET['complete_cart'])){
	$complete_cart = true;
}

if(isset($_POST['buy_item_complete'])){
	$hlavicka = 'From: objednavka@klimatizacevsem.cz';
	$hlavicka .= "\nMIME-Version: 1.0\n";
	$hlavicka .= "Content-Type: text/html; charset=\"utf-8\"\n";
	$adress = 'dankrul.krul@gmail.com';
	$predmet = 'Nová objednávka od: ' . $_POST['name'];

	$message = '<h2>Nová objednávka z e-shopu </h2>';

	if($_POST['gender'] == 'male'){
		$_POST['gender'] = 'Pan ';
	} else {
		$_POST['gender'] = 'Paní ';
	}

	$message .= '<p style="font-weight: bold;">Objednavatel(ka): '. $_POST['gender'] . $_POST['title'] . $_POST['name'] .'</p>';
	$message .= '<p style="font-weight: bold;">Společnost (volitelné): '. $_POST['company'] . '</p>';
	$message .= '<p style="font-weight: bold;">Ulice a číslo domu: '. $_POST['street'] . '</p>';

	$message .= '<p style="font-weight: bold;">PSČ a město: '. $_POST['psc'] . '      '.  $_POST['city'] . '</p>';

	$message .= '<p style="font-weight: bold;">Telefon: '. $_POST['phone'] . '</p>';
	$message .= '<p style="font-weight: bold;">E-mail: '. $_POST['email'] . '</p>';

	$message .= '<p style="font-weight: bold;">Objednané položky: </p>';

	$results_cart_complete = $db->query('SELECT * FROM cart WHERE ip="'. $_SERVER['REMOTE_ADDR'] .'" ORDER BY id');

	$price_all_complete = 0;

	while ($row_cart_complete = $results_cart_complete->fetchArray()) {
		$message .= '<p><a href="http://klimatizacevsem.cz/index.php?view_item='. $row_cart_complete['item_id'] .'">'. $row_cart_complete['name'] .'</a> ('. $row_cart_complete['count'] .' kusů)</p>';
		$price_all_complete += $row_cart_complete['price'];

		$db->exec("UPDATE items SET stock=stock - '". $row_cart_complete['count'] ."' WHERE id='". $row_cart_complete['item_id'] ."' ");
	}

	$message .= '<p style="font-weight: bold;">Celková cena: '. number_format($price_all_complete, 2, ',', ' ') .' Kč</p>';

	$sended = mb_send_mail($adress, $predmet, $message, $hlavicka);

	if($sended){
		echo '<script>alert("Položky zakoupeny"); </script>';

		$delete_all_cart_complete = $db->exec('DELETE FROM cart WHERE ip = "'. $_SERVER['REMOTE_ADDR'] .'"');



		echo '<script> window.location.href = "index.php"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
		echo '<script> window.location.href = "?cart"; </script>';
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

	<title><?php echo $settings[0]['site_name']; ?></title>

	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<link rel="stylesheet" type="text/css" href="./css/flexslider.css">
	<script type="text/javascript" src="./js/jquery.js"></script>
	<script type="text/javascript" src="./js/main.js"></script>
	<script type="text/javascript" src="./js/flexslider.js"></script>
</head>
<body>

	<div id="panel_up">
		<div class="left">
		<?php
			for ($i = 0; $i < count($panel_up); $i++) { 
				echo '<a href="index.php?panel_id='. $panel_up[$i]['id'] .'">' . $panel_up[$i]['name'] . '</a>';
			}
		?>
		</div>

		<div class="right">
		<?php
			if($admin){
				echo '<a class="login" href="login_complete.php" target="_blank">Administrace</a>';
			} else {
				echo '<a class="login" href="login.php" target="_blank">Přihlásit</a>';
			}
		?>
			
		</div>
	</div>
	<header id="header">
		<div id="logo">
			<a href="/"><img alt="" src="./design/images/logo.jpg" /></a>
		</div>

		<div id="ego">
			<div class="flexslider">
				<ul class="slides">
					<li>
						<img alt="" src="./design/images/sinclair.png" width="500" />
					</li>

				</ul>
			</div>
		</div>

		<div id="shopping_cart">
			
			<div class="img">
				<a href="?cart">
					<img alt="" src="./design/icons/cart.png" />
				</a>
				</div>
			<p>Počet položek: <?php echo $cart_message; ?></p>

		</div>

		<div style="clear: both;"></div>
	</header>

	<div id="main">
		<menu class="menu">
			<ul>
			<?php
			for ($i = 0; $i < count($categories); $i++) { 
				echo '<li>';
				echo '<div class="inner">';

				$results_subcategories = $db->query('SELECT * FROM sub_categories WHERE from_="'. $categories[$i] .'"');

				while ($row_subcategories = $results_subcategories->fetchArray()) {
					echo '<span><a style="background-image: none;" href="index.php?from_category='. $row_subcategories['from_'] .'&to='. $row_subcategories['category'] .'">'. $row_subcategories['category'] .'</a></span>';
				}

				echo '</div>';
				echo '<a href="index.php?category='. $categories[$i] .'">' . $categories[$i];
				echo '</a>';
				echo '</li>';
			}
			?>
			</ul>

			<div style="clear: both;"></div>

			<h2>Rychlý kontakt</h2>

			<div class="info">
				<span class="name"><img alt="" src="./design/icons/who.png" width="25" /> 
				<strong>
				<?php
					echo $settings[0]['name'];
				?>
				</strong>
				</span>

				<span class="place"><img alt="" src="./design/icons/place.png" width="25" /> 
				<?php
					echo $settings[0]['location'];
				?>
				</span>

				<span class="email"><img alt="" src="./design/icons/email.png" width="25" /> 
				<?php
					echo $settings[0]['email'];
				?>
				</span>

				<span class="phone"><img alt="" src="./design/icons/phone.png" width="25" />
				<?php
					echo number_format($settings[0]['phone'], 0, ',', ' ');
				?>
				</span>

				<span class="youtube">
				<?php
					echo '<a target="_blank" href="'. $settings[0]['youtube'] .'"><img alt="" src="./design/icons/youtube.png" width="120" /></a>';
				?>
				</span>

			</div>
		</menu>

		<div class="items">
			<?php
				if($panel_content){
					include('panel_include.php');
				} elseif($view_item){
					include('view_item.php');
				} elseif ($category) {
					include('category.php');
				} elseif($sub_category_item){
					include('items_category.php');
				} elseif ($cart) {
					include('cart.php');
				} else {
					include('items.php');
				}
			?>
		</div>
		<div style="clear: both;"></div>
	</div>

	<?php

	include('footer.php');

	if($complete_cart){
		include('cart_complete.php');
	}

	?>
</body>
</html>
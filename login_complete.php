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
		echo 'Špatná session, přihlašte se znovu <br />';
		echo '<a href="index.php">Zpět</a>';
		die();
	}
} else {
	echo 'Neexistující session <br />';
	echo '<a href="index.php">Zpět</a>';
	die();
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
		'dic' => $row['dic'],
		'youtube' => $row['youtube']
		));

}

if(!isset($_SESSION['number_items'])){
	$cart_message = 0;
	$cart_all = 0;
}

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
		'sub_category' => $row_items['sub_category']
		));
}

$add_panel = false;
if(isset($_GET['add_panel'])){
	$add_panel = true;
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

if (isset($_POST['post_up'])) {
	$_POST['panel_name'] = htmlspecialchars(trim($_POST['panel_name']));
	$_POST["input"] = htmlentities($_POST["input"]);

	$insert_panel = $db->exec("INSERT INTO panel_up (name, content) VALUES ('". $_POST["panel_name"] ."', '". $_POST["input"] ."')");

	if($insert_panel){
		echo '<script> alert("Panel přidán"); </script>';
		echo '<script> window.location.href = "login_complete.php"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
	}

}

if(isset($_GET['logout'])){
	unset($_SESSION['name']);
	unset($_SESSION['password']);
	unset($_SESSION['admin']);

	session_destroy();

	header('Location: index.php');
}

if(isset($_POST['save_panel'])){
	$_POST['name'] = htmlspecialchars(trim($_POST['name']));
	$_POST["content"] = htmlentities($_POST["content"]);

	$set_new_panel = $db->exec("UPDATE panel_up SET name='". $_POST["name"] ."', content='". $_POST["content"] ."' WHERE id='". $_POST["id"] ."' ");

	if($set_new_panel){
		echo '<script>alert("Panel uložen"); </script>';
		echo '<script> window.location.href = "login_complete.php"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
	}
}

if(isset($_POST['delete_panel'])){
	$delete_panel = $db->exec('DELETE FROM panel_up WHERE id = "'. $_POST['id'] .'"');

	if($delete_panel){
		echo '<script>alert("Panel smazán"); </script>';
		echo '<script> window.location.href = "login_complete.php"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
	}
}

if(isset($_GET['delete_category'])){
	$delete_category = $db->exec('DELETE FROM categories WHERE category = "'. $_GET['delete_category'] .'"');

	if($delete_category){
		echo '<script>alert("Kategorie smazána!"); </script>';
		echo '<script> window.location.href = "login_complete.php"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
	}
}

$password = false;
if(isset($_GET['password'])){
	$password = true;
}

if(isset($_POST['change_password'])){
	if(hash('sha256', $_POST['last_password']) != $_SESSION['password']){
		echo '<script>alert("Původní heslo nesedí..."); </script>';
	} elseif ($_POST['new_password'] != $_POST['new_password_2']) {
		echo '<script>alert("Nová hesla nejsou stejná!"); </script>';
	} else {
		$set_new_password = $db->exec('UPDATE users SET password="'. hash('sha256', $_POST['new_password']) .'" WHERE password="'. $_SESSION['password'] .'" ');

		if($set_new_password){
			echo '<script>alert("Nové heslo nastaveno!"); </script>';

			unset($_SESSION['name']);
			unset($_SESSION['password']);
			unset($_SESSION['admin']);

			session_destroy();

			echo '<script> window.location.href = "login.php"; </script>';
		} else {
			echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
		}
	}
}

$contact = false;
if(isset($_GET['contact'])){
	$contact = true;
}

$add_category = false;
if(isset($_GET['add_category'])){
	$add_category = true;
}

if(isset($_POST['post_contact'])){
	$set_new_contact = $db->exec('UPDATE main_settings SET location="'. $_POST['location'] .'", email="'. $_POST['email'] .'", phone="'. $_POST['phone'] .'" ');

	if($set_new_contact){
		echo '<script>alert("Rychlý kontakt upraven"); </script>';
		echo '<script> window.location.href = "login_complete.php"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
	}
}

if (isset($_POST['add_category'])) {
	$_POST['category'] = htmlspecialchars(trim($_POST['category']));

	$insert_category = $db->exec('INSERT INTO categories (category) VALUES ("'. $_POST['category'] .'")');

	if($insert_category){
		echo '<script> alert("Kategorie přidána"); </script>';
		echo '<script> window.location.href = "login_complete.php"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
	}

}

$category = false;

if(isset($_GET['category'])){
	$category = true;
}

if(isset($_GET['delete_subcategory']) && isset($_GET['from'])){
	$delete_subcategory = $db->exec('DELETE FROM sub_categories WHERE category = "'. $_GET['delete_subcategory'] .'" AND from_ = "'. $_GET['from'] .'" ');

	if($delete_subcategory){
		echo '<script>alert("Podkategorie smazána!"); </script>';
		echo '<script> window.location.href = "login_complete.php"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
	}
}

$add_subcategory = false;

if(isset($_GET['add_subcategory']) && isset($_GET['category'])){
	$add_subcategory = true;
}

if(isset($_POST['add_subcategory'])){
	$_POST['subcategory'] = htmlspecialchars(trim($_POST['subcategory']));

	$insert_subcategory = $db->exec('INSERT INTO sub_categories (from_,category) VALUES ("'. $_POST['category'] .'", "'. $_POST['subcategory'] .'")');

	if($insert_subcategory){
		echo '<script> alert("Podkategorie přidána"); </script>';
		echo '<script> window.location.href = "login_complete.php"; </script>';
	} else {
		echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
	}
}

$sub_category_item = false;

if(isset($_GET['from_category']) && isset($_GET['to'])){
	$sub_category_item = true;
}

$add_item = false;

if(isset($_GET['add_item'])){
	$add_item = true;
}

if(isset($_POST['add_item'])){

	$target_dir = 'upload/';
	$target_file = $target_dir . basename($_FILES['image']['name']);
	$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

	$ok = 1;

	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
		$ok = 2;
	}

	if ($_FILES['image']['size'] > 500000) {
		$ok = 3;
	}

	switch ($ok) {
		case 2:
		echo '<script>alert("Neplatný formát obrázku!");</script>';
		break;

		case 3:
		echo '<script>alert("Obrázek je příliš velký!");</script>';
		break;

		default:
		$temp = explode(".", $_FILES['image']['name']);
		$file_name = round(microtime(true)) . '.' . end($temp);

		if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $file_name)) {
			$image_url = 'http://' . $_SERVER['SERVER_NAME'] . '/upload/' . $file_name;
        		// Note: The part of this string, "/Klaga", must be deleted in the future.
			$_POST['description'] = htmlentities($_POST['description']);
			$_POST['params'] = htmlentities($_POST['params']);

			preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $_POST['video'], $matches);
    		$id = $matches[1];
    		$width = '800px';
    		$height = '450px';
			
			$video_url = '<iframe type="text/html" width="'. $width .'" height="'. $height .'"src="https://www.youtube.com/embed/'. $id .'?rel=0&showinfo=0&color=white&iv_load_policy=3" frameborder="0" allowfullscreen></iframe>';
    		$video_url = htmlentities($video_url);

			$insert_item = $db->exec('INSERT INTO items (name, price, stock, currency, category, sub_category, image, recommended, caption, video, description, params, last_discount) VALUES ("'. $_POST['name'] .'", "'. $_POST['price'] .'", "'. $_POST['stock'] .'", "'. $_POST['currency'] .'", "'. $_POST['category'] .'", "'. $_POST['subcategory'] .'", "'. $image_url .'", "'. $_POST['recommended'] .'", "'. $_POST['caption'] .'", "'. $video_url .'", "'. $_POST['description'] .'", "'. $_POST['params'] .'", 0) ');

			if($insert_item){
				echo '<script> alert("Produkt přidán"); </script>';
				echo '<script> window.reload(); </script>';
			} else {
				echo '<script> alert("Upsíček, něco se pokazilo..."); </script>';
			}
		} else {
			echo '<script>Upsíček, něco se pokazilo při nahrávání obrázku.</script>';
		}
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

	<title>Administrace</title>

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
				echo '<a href="?panel_id='. $panel_up[$i]['id'] .'">' . $panel_up[$i]['name'] . '</a>';
			}
		?>
			<a href="?add_panel" class="add"><img src="./design/icons/add.png" width="20" title="Přidat panel" /> </a>
		</div>

		<div class="right">
			<a href="?password" class="password">Změnit heslo</a>
			<a href="?logout" class="logout">Odhlásit</a>
		</div>
	</div>
	<header id="header">
		<div id="logo">
			<a href="/"><img src="./design/images/logo.jpg" /></a>
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
					echo '<span><a style="background-image: none;" href="?from_category='. $row_subcategories['from_'] .'&to='. $row_subcategories['category'] .'">'. $row_subcategories['category'] .'</a></span>';
				}

				echo '</div>';

				echo '<a href="?category='. $categories[$i] .'">' . $categories[$i];
				echo '</a>';

				echo '<a class="func" href="?delete_category='. $categories[$i] .'"><img src="./design/icons/del.png" width="15" title="Smazat kategorii" />';
				echo '</a>';
				echo '</li>';
			}

			echo '<li style="text-align: center;"><a href="?add_category" style="background: white;"><img src="./design/icons/add.png" width="30" /></a></li>'
			?>
			</ul>

			<div style="clear: both;"></div>

			<h2>Rychlý kontakt <a href="?contact" class="edit"><img  src="./design/icons/edit.png" width="25" /></a></h2>

			<div class="info">
				<span class="place"><img src="./design/icons/place.png" width="25" /> 
				<?php
					echo $settings[0]['location'];
				?>
				</span>
				<span class="email"><img src="./design/icons/email.png" width="25" /> 
				<?php
					echo $settings[0]['email'];
				?>
				</span>
				<span class="phone"><img src="./design/icons/phone.png" width="25" />
				<?php
					echo number_format($settings[0]['phone'], 0, ',', ' ');
				?>
				</span>

				<span class="youtube">
				<?php
					echo '<a target="_blank" href="'. $settings[0]['youtube'] .'"><img src="./design/icons/youtube.png" width="120" /></a>';
				?>
				</span>
			</div>
		</menu>

		<div class="items">

			<?php
				if($panel_content){
					include('panel_admin_include.php');
				} elseif($category){
					include('category_admin.php');
				} elseif($sub_category_item){
					include('items_category_admin.php');
				} else {
					include('items_admin.php');
				}
			?>

		</div>
		<div style="clear: both;"></div>
	</div>

	<div id="ego">
		<div class="flexslider">
			<ul class="slides">
				<li>
				<img src="./design/images/sinclair.bmp" />
				<p class="flex-caption">Petr Klaga</p>
				</li>

			</ul>
		</div>
	</div>


	<?php

	include('footer.php');

	?>

	<?php
		if($add_panel){
			include('panel_up.php');
		}

		elseif ($password) {
			include('change_password.php');
		}

		elseif($add_category){
			include('add_category.php');
		}

		elseif($contact){
			include('change_contact.php');
		}

		elseif($add_subcategory){
			include('add_subcategory.php');
		}

		elseif($add_item){
			include('add_item.php');
		}
	?>
</body>
</html>
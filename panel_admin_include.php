<script src="./js/tinymce/tinymce.min.js"></script>

<script>
  tinymce.init({
    selector: '.edit_content',
    plugins : 'advlist autolink link image lists charmap print preview',
    toolbar: 'bold, italic, underline, strikethrough, alignleft, aligncenter, alignright, alignjustify, styleselect, fontsizeselect, bullist, numlist, outdent, indent',
  	fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
    language: 'cs'
  });
</script>

<?php
	$content_panel = $db->query('SELECT * FROM panel_up WHERE id="'. $_GET['panel_id'] .'" ');
	$row_content_panel = $content_panel->fetchArray();

	echo '<div class="article">';
	echo '<form method="POST" style="display: inline;">';
	echo '<input type="hidden" name="id" value="'. $row_content_panel['id'] .'" />';
	echo '<textarea name="name" class="edit_title" maxlength="24">'. $row_content_panel['name'] .'</textarea>';
	echo '<textarea name="content" class="edit_content">'. $row_content_panel['content'] .'</textarea>';
	echo '<button type="submit" class="butt" style="display: inline;" name="save_panel"><img src="./design/icons/save.png" width="20" /> Uložit</button>';
	echo '</form>';
	echo '<form method="POST" style="display: inline;">';
	echo '<input type="hidden" name="id" value="'. $row_content_panel['id'] .'" />';
	echo '<button type="submit" class="butt" name="delete_panel"><img src="./design/icons/delete.png" width="20" /> Smazat položku</button>';
	echo '</form>';
	echo '</div>';
?>
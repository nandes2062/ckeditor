<?php
$moduleInput = rex_get_file_contents($REX["INCLUDE_PATH"] . "/addons/ckeditor/module/standard/input.php");
$moduleOutput = rex_get_file_contents($REX["INCLUDE_PATH"] . "/addons/ckeditor/module/standard/output.php");

// Ist Modul schon vorhanden ?
$searchtext = 'module: ckeditor_standard_in';

$gm = rex_sql::factory();
$gm->setQuery('select * from ' . $REX['TABLE_PREFIX'] . 'module where eingabe LIKE "%' . $searchtext . '%"');

$module_id = 0;
$module_name = "";

foreach($gm->getArray() as $module) {
  $module_id = $module["id"];
  $module_name = $module["name"];
}

if (isset($_REQUEST["install"]) && $_REQUEST["install"] == 1) {
  $module_name = $I18N->msg('ckeditor_module_name_standard');

  $mi = rex_sql::factory();
  // $mi->debugsql = 1;
  $mi->setTable("rex_module");
  $mi->setValue("eingabe", addslashes($moduleInput));
  $mi->setValue("ausgabe", addslashes($moduleOutput));

  if (isset($_REQUEST["module_id"]) && $module_id == $_REQUEST["module_id"]) {
	// altes Module aktualisieren
    $mi->setWhere('id="' . $module_id . '"');
    $mi->update();

 	// article updaten
	rex_generateAll();
	
    echo rex_info($I18N->msg('module_updated').' | '.$I18N->msg('delete_cache_message'));
  }else {
	// neues Modul einf&uuml;gen
    $mi->setValue("name", $module_name);
    $mi->insert();
    $module_id = (int) $mi->getLastId();
	
    echo rex_info($I18N->msg('ckeditor_module_added', $module_name));
  }
}
?>

<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('ckeditor_standard_module'); ?></h2>
	<div class="rex-area-content module">
		<ul>
		<?php
			if ($module_id > 0) {
				if (!isset($_REQUEST["install"])) {
					echo '<li><a href="index.php?page=ckeditor&amp;subpage=standard_module&amp;install=1&amp;module_id=' . $module_id . '">' . $I18N->msg('ckeditor_module_update') . '</a></li>';
				}
    		} else {
				if (!isset($_REQUEST["install"])) {
					echo '<li><a href="index.php?page=ckeditor&amp;subpage=standard_module&amp;install=1">' . $I18N->msg('ckeditor_module_install') . '</a></li>';
				}
			}
		?>		
		</ul>
		<p class="headline"><?php echo $I18N->msg('ckeditor_module_input'); ?></p><?php rex_highlight_string($moduleInput); ?>
		<p>&nbsp;</p>
		<p class="headline"><?php echo $I18N->msg('ckeditor_module_output'); ?></p><?php rex_highlight_string($moduleOutput); ?>
	</div>
</div>


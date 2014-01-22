<?php
class rex_ckeditor_utils {
	public static function addToOutputFilter($params) {
		return str_replace('</body>', self::getHtml() . '</body>', $params['subject']);
	}

	public static function getHtml() {
		$html = PHP_EOL;

		$html .= '<!-- BEGIN ckeditor -->' . PHP_EOL;
		$html .= '<link rel="stylesheet" type="text/css" href="../' . self::getMediaAddonDir() . '/ckeditor/redaxo.css" />' . PHP_EOL;
		//$insert .= '<script type="text/javascript">var CKEDITOR_BASEPATH = "../' . self::getMediaAddonDir() . '/ckeditor/vendor/";</script>' . PHP_EOL;
		$html .= '<script type="text/javascript" src="../' . self::getMediaAddonDir() . '/ckeditor/vendor/ckeditor.js"></script>' . PHP_EOL;
		$html .= '<!-- END ckeditor -->';

		return $html;
	}

	protected static function getMediaAddonDir() {
		global $REX;

		// check for media addon dir var introduced in REX 4.5
		if (isset($REX['MEDIA_ADDON_DIR'])) {
			return $REX['MEDIA_ADDON_DIR'];
		} else {
			return 'files/addons';
		}
	}

	public static function getHtmlFromMDFile($mdFile, $search = array(), $replace = array()) {
		global $REX;

		$curLocale = strtolower($REX['LANG']);

		if ($curLocale == 'de_de') {
			$file = $REX['INCLUDE_PATH'] . '/addons/ckeditor/' . $mdFile;
		} else {
			$file = $REX['INCLUDE_PATH'] . '/addons/ckeditor/lang/' . $curLocale . '/' . $mdFile;
		}

		if (file_exists($file)) {
			$md = file_get_contents($file);
			$md = str_replace($search, $replace, $md);
			$md = self::makeHeadlinePretty($md);

			return Parsedown::instance()->parse($md);
		} else {
			return '[translate:' . $file . ']';
		}
	}

	public static function makeHeadlinePretty($md) {
		return str_replace('CKEditor - ', '', $md);
	}
}


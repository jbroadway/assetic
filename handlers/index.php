<?php

$cl = new SplClassLoader ('Assetic', 'apps/assetic/lib');
$cl->register ();

if (! defined ('ASSETIC_VER')) {
	define ('ASSETIC_VER', mt_rand (1, 100));
}

if (! @is_dir ('cache/assetic')) {
	mkdir ('cache/assetic');
	chmod ('cache/assetic', 0777);
}

if (! isset ($data['css']) && ! isset ($data['js']) && ! isset ($_GET['css']) && ! isset ($_GET['js']) && count ($this->params) > 0) {
	// Handle a single file

	$file = join ('/', $this->params);
	$save_as = str_replace ('/', '_', $file);

	$assets = new Assetic\Asset\AssetCollection;

	if (preg_match ('/\.less$/i', $file)) {
		$save_as = preg_replace ('/\.less$/i', '.css', $save_as);
		$assets->add (new Assetic\Asset\FileAsset ($file, array (
			new Assetic\Filter\LessphpFilter (),
			new Assetic\Filter\Yui\CssCompressorFilter ($appconf['Assetic']['yui_compressor'])
		)));
	} elseif (preg_match ('/\.sass$/i', $file)) {
		$save_as = preg_replace ('/\.sass$/i', '.css', $save_as);
		$assets->add (new Assetic\Asset\FileAsset ($file, array (
			new Assetic\Filter\Sass\SassFilter ($appconf['Assetic']['sass_filter']),
			new Assetic\Filter\Yui\CssCompressorFilter ($appconf['Assetic']['yui_compressor'])
		)));
	} elseif (preg_match ('/\.css$/i', $file)) {
		$assets->add (new Assetic\Asset\FileAsset ($file, array (
			new Assetic\Filter\Yui\CssCompressorFilter ($appconf['Assetic']['yui_compressor'])
		)));
	} elseif (preg_match ('/\.(coffee|cs)$/i', $file)) {
		$save_as = preg_replace ('/\.(coffee|cs)$/i', '.js', $save_as);
		$assets->add (new Assetic\Asset\FileAsset ($file, array (
			new Assetic\Filter\CoffeeScriptFilter ($appconf['Assetic']['coffeescript']),
			new Assetic\Filter\Yui\JsCompressorFilter ($appconf['Assetic']['yui_compressor'])
		)));
	} else {
		$assets->add (new Assetic\Asset\FileAsset ($file, array (
			new Assetic\Filter\Yui\JsCompressorFilter ($appconf['Assetic']['yui_compressor'])
		)));
	}

	file_put_contents ('cache/assetic/' . $save_as, $assets->dump ());
	echo '/cache/assetic/' . $save_as . '?v=' . ASSETIC_VER;
} else {
	// Handle a list of files

	$name = count ($this->params) > 0 ? $this->params[0] : 'all';

	$css = isset ($data['css']) ? $data['css'] : (isset ($_GET['css']) ? $_GET['css'] : array ());
	if (! is_array ($css)) {
		$css = array ($css);
	}

	$fm = new Assetic\FilterManager ();
	$fm->set ('sass', new Assetic\Filter\Sass\SassFilter ($appconf['Assetic']['sass_filter']));
	$fm->set ('less', new Assetic\Filter\LessphpFilter ());
	$fm->set ('coffee', new Assetic\Filter\CoffeeScriptFilter ($appconf['Assetic']['coffeescript']));
	$fm->set ('yui_css', new Assetic\Filter\Yui\CssCompressorFilter ($appconf['Assetic']['yui_compressor']));
	$fm->set ('yui_js', new Assetic\Filter\Yui\JsCompressorFilter ($appconf['Assetic']['yui_compressor']));

	if (! empty ($css)) {
		$assets = new Assetic\Asset\AssetCollection;

		foreach ($css as $file) {
			if (strpos ($file, '*')) {
				$assets->add (new Assetic\Asset\GlobAsset ($file, array ($fm->get ('yui_css'))));
			} elseif (preg_match ('/\.less$/i', $file)) {
				$assets->add (new Assetic\Asset\FileAsset ($file, array (
					$fm->get ('less'),
					$fm->get ('yui_css')
				)));
			} elseif (preg_match ('/\.sass$/i', $file)) {
				$assets->add (new Assetic\Asset\FileAsset ($file, array (
					$fm->get ('sass'),
					$fm->get ('yui_css')
				)));
			} else {
				$assets->add (new Assetic\Asset\FileAsset ($file, array ($fm->get ('yui_css'))));
			}
		}

		file_put_contents ('cache/assetic/' . $name . '.css', $assets->dump ());
		echo '<script src="/cache/assetic/' . $name . '.css?v=' . ASSETIC_VER . '"></script>';
	}

	$js = isset ($data['js']) ? $data['js'] : (isset ($_GET['js']) ? $_GET['js'] : array ());
	if (! is_array ($js)) {
		$js = array ($js);
	}

	if (! empty ($js)) {
		$assets = new Assetic\Asset\AssetCollection;

		foreach ($js as $file) {
			if (strpos ($file, '*')) {
				$assets->add (new Assetic\Asset\GlobAsset ($file, array ($fm->get ('yui_js'))));
			} elseif (preg_match ('/\.(coffee|cs)$/i', $file)) {
				$assets->add (new Assetic\Asset\FileAsset ($file, array (
					$fm->get ('coffee'),
					$fm->get ('yui_jss')
				)));
			} else {
				$assets->add (new Assetic\Asset\FileAsset ($file, array ($fm->get ('yui_js'))));
			}
		}

		file_put_contents ('cache/assetic/' . $name . '.js', $assets->dump ());
		echo '<script src="/cache/assetic/' . $name . '.js?v=' . ASSETIC_VER . '"></script>';
	}
}

?>
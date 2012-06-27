<?php

/**
 * Precompiles handlebars templates for client-side rendering. Avoids
 * unnecessary client-side template compilation, and includes the much
 * smaller Handlebars runtime without the compiler, so it's much faster.
 *
 * Usage:
 *
 * 1. Save your handlebars templates with a .handlebars file extension
 * in your app's views folder.
 *
 * 2. Include the following in your layout template to precompile them:
 *
 *     {! assetic/handlebars !}
 *
 * 3. After the above include has compiled the templates, you can include
 * them via:
 *
 *     <script src="/cache/assetic/handlebars.compiled.js"></script>
 *
 * > Note: This also includes the slimmer Handlebars runtime for you.
 *
 * 4. To call a template, simply refer to it like this:
 *
 *     <script>
 *     $('#my-div').html (Handlebars.templates.my_template (data));
 *     </script>
 */

// path to the handlebars compiler
$handlebars = $appconf['Assetic']['handlebars'];

// path to save cache
$cache_dir = $appconf['Assetic']['save_path'] . '/handlebars';
if (! file_exists ($cache_dir)) {
	mkdir ($cache_dir);
	chmod ($cache_dir, 0777);
}

$files = glob ('apps/*/views/*.handlebars');

$all = $appconf['Assetic']['save_path'] . '/handlebars.compiled.js';
if (! file_exists ($all)) {
	$recompile = true;
} else {
	$recompile = false;
}

foreach ($files as $file) {
	$cache_file = $cache_dir . '/' . basename ($file, '.handlebars') . '.js';
	if (! file_exists ($cache_file) || filemtime ($cache_file) < filemtime ($file)) {
		$recompile = true;
		$cmd = sprintf ('%s %s -m -f %s', $handlebars, $file, $cache_file);
		system ($cmd, $res);
	}
}

if ($recompile) {
	file_put_contents ($all, file_get_contents ('apps/assetic/js/handlebars.runtime-1.0.0.beta.6.js'));
	foreach ($files as $file) {
		$cache_file = $cache_dir . '/' . basename ($file, '.handlebars') . '.js';
		file_put_contents ($all, file_get_contents ($cache_file) . ";\n", FILE_APPEND);
	}
}

?>
<?php

/**
 * Shows a list of compiled assets.
 */

$this->require_admin ();

$page->title = 'Assetic';
$page->layout = 'admin';

$save_path = $appconf['Assetic']['save_path'];
$web_path = $appconf['Assetic']['web_path'];

if (! is_dir ($save_path)) {
	if ($save_path === 'cache/assetic') {
		mkdir ($save_path);
		chmod ($save_path, 0777);
	} else {
		printf ('<p>%s</p>', i18n_get ('Error: Save folder is missing, please create it.'));
		return;
	}
}

$cache = array ();
if (is_dir ($save_path)) {
	$d = dir ($save_path);
	while (false != ($entry = $d->read ())) {
		if (preg_match ('/^(.*)\.(css|js)$/', $entry, $regs)) {
			$cache[] = ltrim ($web_path, '/') . '/' . $regs[1] . '.' . $regs[2];
		}
	}
	$d->close ();
	sort ($cache);
}

echo $tpl->render ('assetic/admin', array ('cache' => $cache));

?>
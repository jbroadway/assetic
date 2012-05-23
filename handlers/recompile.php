<?php

/**
 * Recompiles the assets.
 */

$this->require_admin ();

$page->title = 'Assetic';
$page->layout = 'admin';

// touching the layout files will trigger
// a recompile on the next page view.
function touch_layouts ($files) {
	foreach ($files as $file) {
		if (preg_match ('/\.html$/', $file)) {
			touch ('layouts/' . $file);
		}
	}
}
touch_layouts (glob ('layouts/*.html', GLOB_NOSORT));
touch_layouts (glob ('layouts/*/*.html', GLOB_NOSORT));

$this->add_notification (i18n_get ('Assets will recompile on the next page load.'));
$this->redirect ('/assetic/admin');

?>
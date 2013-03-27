<?php

/**
 * Recompiles the assets.
 */

if ($this->cli) {
	$page->layout = false;
} else {
	$this->require_admin ();

	$page->title = 'Assetic';
	$page->layout = 'admin';
}

// touching the layout files will trigger
// a recompile on the next page view.
function touch_layouts ($files) {
	foreach ($files as $file) {
		if (preg_match ('/\.html$/', $file)) {
			touch ($file);
		}
	}
}
touch_layouts (glob ('layouts/*.html', GLOB_NOSORT));
touch_layouts (glob ('layouts/*/*.html', GLOB_NOSORT));

// touching the handlebars templates will
// trigger a recompile on the next page view.
function touch_handlebars ($files) {
	foreach ($files as $file) {
		if (preg_match ('/\.handlebars$/', $file)) {
			touch ($file);
		}
	}
}
touch_handlebars (glob ('apps/*/views/*.handlebars', GLOB_NOSORT));

if (! $this->cli) {
	$this->add_notification (i18n_get ('Assets will recompile on the next page load.'));
	$this->redirect ('/assetic/admin');
}

?>
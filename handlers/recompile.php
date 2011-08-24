<?php

if (! User::require_admin ()) {
	$this->redirect ('/admin');
}

$page->title = 'Assetic';
$page->layout = 'admin';

$d = dir (getcwd () . '/layouts');
while (false != ($entry = $d->read ())) {
	if (preg_match ('/\.html$/', $entry)) {
		touch ('layouts/' . $entry);
	}
}
$d->close ();

$this->add_notification (i18n_get ('Assets will recompile on the next page load.'));
$this->redirect ('/assetic/admin');

?>
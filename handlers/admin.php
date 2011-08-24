<?php

if (! User::require_admin ()) {
	$this->redirect ('/admin');
}

$page->title = 'Assetic';
$page->layout = 'admin';

$cache = array ();
$d = dir (getcwd () . '/cache/assetic');
while (false != ($entry = $d->read ())) {
	if (preg_match ('/^(.*)\.(css|js)$/', $entry, $regs)) {
		$cache[] = 'cache/assetic/' . $regs[1] . '.' . $regs[2];
	}
}
$d->close ();
sort ($cache);

echo $tpl->render ('assetic/admin', array ('cache' => $cache));

?>
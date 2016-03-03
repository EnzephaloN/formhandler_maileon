<?php
########################################################################
# Extension Manager/Repository config file for ext: "formhandler_maileon"
#
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Formhandler Maileon Subscription',
	'description' => 'Liefert den REST-CURL-Finisher für Formhandler',
	'category' => 'fe',
	'author' => 'Johannes C. Schulz',
	'author_email' => 'info@enzephalon.de',
	'author_company' => 'EnzephaloN IT-Solutions',
	'shy' => '1',
	'dependencies' => 'extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.1.0',
	'constraints' => array (
		'depends' => array (
			'formhandler' => '1.0.0-2.2.99',
			'typo3' => '6.2.0-7.6.99',
		),
		'conflicts' => array (
		),
		'suggests' => array (
		),
	),
);

?>

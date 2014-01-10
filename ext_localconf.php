<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Alexweb.' . $_EXTKEY,
	'Feawresize',
	array(
		'Resizer' => 'list',

	),
	// non-cacheable actions
	array(
		'Resizer' => 'list',

	)
);

?>
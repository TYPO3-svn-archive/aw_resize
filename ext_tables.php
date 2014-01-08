<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Feawresize',
	'fe_aw_resize'
);

if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Alexweb.' . $_EXTKEY,
		'web',	 // Make module a submodule of 'web'
		'awresize',	// Submodule key
		'',						// Position
		array(
			'Resizer' => 'list',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.png',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_awresize.xlf',
		)
	);

}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'aw_resize');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_awresize_domain_model_resizer', 'EXT:aw_resize/Resources/Private/Language/locallang_csh_tx_awresize_domain_model_resizer.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_awresize_domain_model_resizer');
$TCA['tx_awresize_domain_model_resizer'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:aw_resize/Resources/Private/Language/locallang_db.xlf:tx_awresize_domain_model_resizer',
		'label' => 'uid',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => '',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Resizer.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_awresize_domain_model_resizer.gif'
	),
);

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(Array('LLL:EXT:/locallang_db.php:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY,"pi1/static/","A Sample Flexform Plugin");
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/flexform_ds_pi1.xml');

?>

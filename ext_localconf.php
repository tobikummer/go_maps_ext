<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Clickstorm.' . $_EXTKEY,
	'Show',
	array(
		'Map' => 'show',

	),
	// non-cacheable actions
	array()
);

$TYPO3_CONF_VARS['EXTCONF']['cms']['db_layout']['addTables']['tx_gomapsext_domain_model_map'][0] = array(
	'fList' => 'title,default_type',
	'icon' => true
);

$TYPO3_CONF_VARS['EXTCONF']['cms']['db_layout']['addTables']['tx_gomapsext_domain_model_category'][0] = array(
	'fList' => 'name',
	'icon' => true
);

$TYPO3_CONF_VARS['EXTCONF']['cms']['db_layout']['addTables']['tx_gomapsext_domain_model_address'][0] = array(
	'fList' => 'title, info_window_content',
	'icon' => true
);

// here we register "tx_gomapsext_double6"
$TYPO3_CONF_VARS['SC_OPTIONS']['tce']['formevals']['tx_gomapsext_double6'] = 'EXT:cs_templates/class.tx_gomapsext_double6.php';

?>
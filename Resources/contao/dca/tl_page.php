<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @filesource  tl_page.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:22
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/**
 * Table tl_page
 */
$strPalettes = '{easy_downloadmail},downloadtime,redirecttime,mailfrom,mailsubject,mailbcc,mailtext,jumptodownload; ';
$strReplace = '{publish_legend}';
$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = \str_replace($strReplace, $strPalettes . $strReplace, $GLOBALS['TL_DCA']['tl_page']['palettes']['root']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['rootfallback'] = \str_replace($strReplace, $strPalettes . $strReplace, $GLOBALS['TL_DCA']['tl_page']['palettes']['rootfallback']); // @fixed: #18

$GLOBALS['TL_DCA']['tl_page']['fields']['downloadtime'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_page']['downloadtime'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "int(10) unsigned NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['mailfrom'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_page']['mailfrom'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['maxlength' => 255, 'rgxp' => 'email', 'decodeEntities' => true, 'tl_class' => 'long clr'],
    'sql' => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['mailbcc'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_page']['mailbcc'],
    'exclude' => true,
    'inputType' => 'listWizard',
    'eval' => ['rgxp' => 'email', 'decodeEntities' => true, 'tl_class' => 'clr'],
    'sql' => "text NULL"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['mailsubject'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_page']['mailsubject'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['maxlength' => 255, 'tl_class' => 'long clr'],
    'sql' => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['mailtext'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_page']['mailtext'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['tl_class' => 'long clr'],
    'sql' => "text NULL"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['jumptodownload'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_page']['jumptodownload'],
    'exclude' => true,
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => ['fieldType' => 'radio', 'tl_class' => 'w50'],
    'sql' => "int(10) unsigned NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['redirecttime'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_page']['redirecttime'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "int(10) unsigned NOT NULL default '0'"

];

/*
 * TinyMCE setting
 */
if (\Contao\Config::get('usetiny')) {
    $GLOBALS['TL_DCA']['tl_page']['fields']['mailtext']['eval']['rte'] = 'tinyMCE';
}

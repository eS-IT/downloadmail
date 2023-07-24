<?php

/**
 * @package     downloadmail
 * @since       18.10.2018 - 10:23
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

declare(strict_types=1);

/*
 * Table tl_settings
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'setdownladsuffix';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default']       .= ';{easy_downloadmail}, downloadtime, redirecttime, mailfrom, mailsubject, mailbcc, mailtext, jumptodownload, usetiny,setdownladsuffix;';

/* Subpalettes */
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['setdownladsuffix'] = 'downloadsuffix';


$GLOBALS['TL_DCA']['tl_settings']['fields']['downloadtime'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['downloadtime'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'w50']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['mailfrom'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['mailfrom'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 255, 'rgxp' => 'email', 'decodeEntities' => true, 'tl_class' => 'long clr']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['mailbcc'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['mailbcc'],
    'exclude'   => true,
    'inputType' => 'listWizard',
    'eval'      => ['rgxp' => 'email', 'decodeEntities' => true, 'tl_class' => 'clr']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['mailsubject'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['mailsubject'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'long clr']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['mailtext'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['mailtext'],
    'exclude'   => true,
    'inputType' => 'textarea',
    'eval'      => ['tl_class' => 'long clr'],
    'sql'       => "text NOT NULL"
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['jumptodownload'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_settings']['jumptodownload'],
    'exclude'       => true,
    'inputType'     => 'pageTree',
    'foreignKey'    => 'tl_page.title',
    'eval'          => ['fieldType' => 'radio', 'tl_class' => 'w50']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['redirecttime'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['redirecttime'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'w50']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['usetiny'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['usetiny'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => '']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['setdownladsuffix'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['setdownladsuffix'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => 'w50 m12 clr', 'submitOnChange' => true]
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['downloadsuffix'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['downloadsuffix'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'w50']
];


/*
 * TinyMCE setting
 */
if (\Contao\Config::get('usetiny')) {
    $GLOBALS['TL_DCA']['tl_settings']['fields']['mailtext']['eval']['rte'] = 'tinyMCE';
}

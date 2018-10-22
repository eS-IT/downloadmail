<?php
/**
 * @package     downloadmail
 * @filesource  tl_form_field.php
 * @version     2.0.0
 * @since       18.10.2018 - 09:21
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/**
 * Set Tablename: tl_form_field
 */
$strName = 'tl_form_field';

/* Palettes */
$GLOBALS['TL_DCA'][$strName]['palettes']['text'] .= ';{downloadmail_legend},downloadmailaddress;';

/* Fields */
$GLOBALS['TL_DCA'][$strName]['fields']['downloadmailaddress'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['downloadmailaddress'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA'][$strName]['fields']['rgxp']['options'][]  = 'mailblacklist';

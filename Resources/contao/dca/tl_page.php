<?php
/**
 * @package     downloadmail
 * @filesource  tl_page.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:22
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/**
 * Table tl_page
 */

$strPalettes    = '{easy_downloadmail}, downloadtime, redirecttime, mailfrom, mailsubject, mailbcc, mailtext, jumptodownload; ';
$strReplace     = '{publish_legend}';
$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = str_replace($strReplace, $strPalettes . $strReplace, $GLOBALS['TL_DCA']['tl_page']['palettes']['root']);


$GLOBALS['TL_DCA']['tl_page']['fields']['downloadtime'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['downloadtime'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('tl_class' => 'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['mailfrom'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['mailfrom'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('maxlength'=>255, 'rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'long'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['mailbcc'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['mailbcc'],
    'exclude'                 => true,
    'inputType'               => 'listWizard',
    'eval'                    => array('rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'clr'),
    'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['mailsubject'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['mailsubject'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('maxlength'=>255, 'tl_class' => 'long clr'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['mailtext'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['mailtext'],
    'exclude'                 => true,
    'inputType'               => 'textarea',
    'eval'                    => array('tl_class'=>'long clr'),
    'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['jumptodownload'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['jumptodownload'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'foreignKey'              => 'tl_page.title',
    'eval'                    => array('fieldType'=>'radio', 'tl_class' => 'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['redirecttime'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['redirecttime'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('tl_class' => 'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"

);

/**
 * TinyMCE setting
 */
if (\Contao\Config::get('usetiny')) {
    $GLOBALS['TL_DCA']['tl_page']['fields']['mailtext']['eval']['rte'] = 'tinyMCE';
}

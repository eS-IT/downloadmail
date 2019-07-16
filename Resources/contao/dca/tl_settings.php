<?php
/**
 * @package     downloadmail
 * @filesource  tl_settings.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:23
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/**
 * Table tl_settings
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{easy_downloadmail}, downloadtime, redirecttime, mailfrom, mailsubject, mailbcc, mailtext, jumptodownload, usetiny;';


$GLOBALS['TL_DCA']['tl_settings']['fields']['downloadtime'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['downloadtime'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['mailfrom'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['mailfrom'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('maxlength'=>255, 'rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'long clr')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['mailbcc'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['mailbcc'],
    'exclude'                 => true,
    'inputType'               => 'listWizard',
    'eval'                    => array('rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'clr')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['mailsubject'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['mailsubject'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('tl_class' => 'long clr')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['mailtext'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['mailtext'],
    'exclude'                 => true,
    'inputType'               => 'textarea',
    'eval'                    => array('tl_class'=>'long clr'),
    'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['jumptodownload'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['jumptodownload'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'foreignKey'              => 'tl_page.title',
    'eval'                    => array('fieldType'=>'radio', 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['redirecttime'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['redirecttime'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['usetiny'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['usetiny'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class' => '')
);

/**
 * TinyMCE setting
 */
if (\Contao\Config::get('usetiny')) {
    $GLOBALS['TL_DCA']['tl_settings']['fields']['mailtext']['eval']['rte'] = 'tinyMCE';
}
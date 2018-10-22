<?php
/**
 * @package     downloadmail
 * @filesource  tl_form.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:20
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/**
 * Set Tablename: tl_form
 */
$strName = 'tl_form';

/* Palettes */
$GLOBALS['TL_DCA'][$strName]['palettes']['__selector__'][] = 'downloadmailform';
$GLOBALS['TL_DCA'][$strName]['palettes']['default'] .= ';{downloadmail_legend},downloadmailform;';

/* Subpalettes */
$GLOBALS['TL_DCA'][$strName]['subpalettes']['downloadmailform'] = 'downloadtime,redirecttime,mailfrom,mailbcc,mailsubject,mailtext,jumptodownload,mySingleSRC';

/* Fields */
$GLOBALS['TL_DCA'][$strName]['fields']['downloadmailform'] = [
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['downloadmailform'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => ['submmitOnChange'=>true],
    'sql'                     => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA'][$strName]['fields']['downloadtime'] = array(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['downloadtime'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('tl_class' => 'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA'][$strName]['fields']['redirecttime'] = array(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['redirecttime'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('tl_class' => 'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"

);

$GLOBALS['TL_DCA'][$strName]['fields']['mailfrom'] = array(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['mailfrom'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('maxlength'=>255, 'rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strName]['fields']['mailbcc'] = array(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['mailbcc'],
    'exclude'                 => true,
    'inputType'               => 'listWizard',
    'eval'                    => array('rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'clr'),
    'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA'][$strName]['fields']['mailsubject'] = array(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['mailsubject'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('maxlength'=>255, 'tl_class' => 'long clr'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strName]['fields']['mailtext'] = array(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['mailtext'],
    'exclude'                 => true,
    'inputType'               => 'textarea',
    'eval'                    => array('tl_class'=>'long clr'),
    'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA'][$strName]['fields']['jumptodownload'] = array(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['jumptodownload'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'foreignKey'              => 'tl_page.title',
    'eval'                    => array('fieldType'=>'radio', 'tl_class' => 'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA'][$strName]['fields']['mySingleSRC'] = array(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['mySingleSRC'],
    'exclude'                 => true,
    'inputType'               => 'fileTree',
    'eval'                    => array('filesOnly'=>true, 'fieldType'=>'radio', 'mandatory'=>true, 'tl_class'=>'clr'),
    'sql'                     => "binary(16) NULL"
);


/**
 * TinyMCE setting
 */
if(\Contao\Config::get('usetiny')){
    $GLOBALS['TL_DCA'][$strName]['fields']['mailtext']['eval']['rte'] = 'tinyMCE';
}
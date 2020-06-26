<?php

/**
 * @package     htdocs
 * @filesource  tl_content.php
 * @since       25.06.2020 - 17:50
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */

/**
 * Set Tablename: tl_content
 */
$table = 'tl_content';

/* global_operations
$GLOBALS['TL_DCA'][$table]['list']['global_operations'] = [
   'all' => [
       'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
       'href'                => 'act=select',
       'class'               => 'header_edit_all',
       'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
   ]
];

/* operations
$GLOBALS['TL_DCA'][$table]['list']['operations']['delete'] = [
    'label'             => &$GLOBALS['TL_LANG'][$table]['delete'],
    'href'              => 'act=delete',
    'icon'              => 'delete.svg',
    'attributes'        => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
];

/* slectors
$GLOBALS['TL_DCA'][$table]['palettes']['__selector__'][] = 'SUBFIELD';

/* Palettes */
$GLOBALS['TL_DCA'][$table]['palettes']['smartgallerymenu']  = '{type_legend},type,headline;{settings_legend},startfolder;{jumpto_legend:hide},jumpto;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop;';
$GLOBALS['TL_DCA'][$table]['palettes']['smartgallery']      = '{type_legend},type,headline;{image_legend},size,imagemargin,perRow,fullsize,perPage;{template_legend:hide},galleryTpl,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop;';

/* subpalettes
$GLOBALS['TL_DCA'][$table]['subpalettes']['SUBFIELD'] = 'FIELDS';

/* Fields */
$GLOBALS['TL_DCA'][$table]['fields']['jumpto'] = [
    'label'                 => &$GLOBALS['TL_LANG'][$table]['jumpto'],
    'exclude'               => true,
    'inputType'             => 'pageTree',
    'foreignKey'            => 'tl_page.title',
    'eval'                  => array('fieldType'=>'radio', 'tl_class'=>'clr'),
    'sql'                   => "int(10) unsigned NOT NULL default 0"
];

$GLOBALS['TL_DCA'][$table]['fields']['startfolder'] = [
    'label'                 => &$GLOBALS['TL_LANG'][$table]['startfolder'],
    'exclude'               => true,
    'inputType'             => 'fileTree',
    'eval'                  => ['fieldType'=>'radio', 'tl_class'=>'clr', 'multiple'=>false, 'files'=>false],
    'sql'                   => 'blob NULL'
];
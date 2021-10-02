<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @filesource  tl_module.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:22
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/*
 * Table tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['easy_Downloadmail'] = '{type_legend},name,type;{easy_downloadmail},redirecttime;{protected_legend:hide},protected;{expert_legend:hide},guests';


$GLOBALS['TL_DCA']['tl_module']['fields']['name']['eval']['tl_class'] = 'w50';
$GLOBALS['TL_DCA']['tl_module']['fields']['type']['eval']['tl_class'] = 'w50';


$GLOBALS['TL_DCA']['tl_module']['fields']['redirecttime'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['redirecttime'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "int(10) unsigned NOT NULL default '0'"

];

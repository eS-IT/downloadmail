<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @filesource  tl_dm_blacklist.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:21
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/*
 * Table tl_dm_blacklist
 */
$GLOBALS['TL_DCA']['tl_dm_blacklist'] = [

    // Config
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary'
            ]
        ]
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['title'],
            'panelLayout' => 'filter;sort,search,limit',
            'flag' => 1
        ],
        'label' => [
            'fields' => ['title'],
            'format' => '%s'
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            ]
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_dm_blacklist']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif'
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_dm_blacklist']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif'
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_dm_blacklist']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_dm_blacklist']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif'
            ]
        ]
    ],

    // Edit
    'edit' => [
        'buttons_callback' => []
    ],

    // Palettes
    'palettes' => [
        '__selector__' => [''],
        'default' => '{title_legend}, title, pattern, regex;'
    ],

    // Subpalettes
    'subpalettes' => [
        '' => ''
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_blacklist']['title'],
            'exclude' => true,
            'inputType' => 'text',
            'filter' => true,
            'sort' => true,
            'search' => true,
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'pattern' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_blacklist']['pattern'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'regex' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_blacklist']['regex'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default ''"
        ]
    ]
];

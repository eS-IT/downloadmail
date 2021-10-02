<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @filesource  tl_dm_downloads.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:21
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/*
 * Table tl_dm_downloads
 */
$GLOBALS['TL_DCA']['tl_dm_downloads'] = [

    // Config
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'notCreatable' => true,
        'notCopyable' => true,
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
            'fields' => ['requesttime', 'email'],
            'flag' => 5,
            'panelLayout' => 'filter;sort,search,limit'
        ],
        'label' => [
            'fields' => ['requesttime', 'email', 'downloadcount'],
            'format' => '[%s] %s - %s Downloads'
        ],
        'global_operations' => [],
        'operations' => [
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_dm_downloads']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'view' => [
                'label' => &$GLOBALS['TL_LANG']['tl_dm_downloads']['view'][0],
                'href' => 'key=view',
                'icon' => 'bundles/esitdownloadmail/img/magnifier--arrow.png',
                'button_callback' => [\Esit\Downloadmail\Classes\Contao\Dca\TlDmDownloads::class, 'generateIcon']
            ]
        ]
    ],

    // Palettes
    'palettes' => [
        'default' => '{info_legend}, requesttime, code, email, downloadcount, singleSRC, jumpto;'
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'requesttime' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_downloads']['requesttime'],
            'exclude' => true,
            'sorting' => true,
            'flag' => 5,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''"
        ],
        'code' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_downloads']['code'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 64, 'tl_class' => 'w50', 'rgxp' => 'alnum', 'nospace' => true, 'unique' => true],
            'sql' => "varchar(64) NOT NULL default ''"
        ],
        'email' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_downloads']['email'],
            'exclude' => true,
            'sorting' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'rgxp' => 'email', 'decodeEntities' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'downloadcount' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_downloads']['downloadcount'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 10, 'tl_class' => 'w50', 'rexp' => 'digit'],
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'singleSRC' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_downloads']['singleSRC'],
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => ['filesOnly' => true, 'fieldType' => 'radio', 'mandatory' => true, 'tl_class' => 'clr'],
            'sql' => "binary(16) NULL"
        ],
        'jumpto' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_downloads']['jumpto'],
            'exclude' => true,
            'inputType' => 'pageTree',
            'foreignKey' => 'tl_page.title',
            'eval' => ['fieldType' => 'radio', 'tl_class' => 'w50'],
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'formid' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_downloads']['formid'],
            'exclude' => true,
            'inputType' => 'text',
            'foreignKey' => 'tl_form.title',
            'eval' => ['mandatory' => true, 'maxlength' => 10, 'tl_class' => 'w50', 'rexp' => 'digit'],
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'downloaddata' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_downloads']['downloaddata'],
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => ['doNotShow' => true],
            'sql' => "text NOTNULL"
        ],
        'requestpage' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dm_downloads']['requestpage'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['doNotShow' => true],
            'sql' => "varchar(255) NOT NULL default ''"
        ]
    ]
];

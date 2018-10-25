<?php
/**
 * @package     downloadmail
 * @filesource  tl_dm_downloads.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:21
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/**
 * Table tl_dm_downloads
 */
$GLOBALS['TL_DCA']['tl_dm_downloads'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'notCreatable'                => true,
		'notCopyable'                 => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('requesttime', 'email'),
			'flag'                    => 5,
            'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('requesttime', 'email', 'downloadcount'),
			'format'                  => '[%s] %s - %s Downloads'
		),
		'global_operations' => array
		(/*
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)*/
		),
		'operations' => array
		(
            /*'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_dm_downloads']['edit'],
                'href'                => 'act=edit',
              'icon'                => 'edit.gif'
            ),/*
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_dm_downloads']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_dm_downloads']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),*/
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_dm_downloads']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'view' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_dm_downloads']['view'],
                'href'                => 'key=view',
                'icon'                => 'bundles/esitdownloadmail/img/magnifier--arrow.png'
            )
		)
	),

	// Edit
	'edit' => array
	(
		'buttons_callback' => array()
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(''),
		'default'                     => '{info_legend}, requesttime, code, email, downloadcount, singleSRC, jumpto;'
	),

	// Subpalettes
	'subpalettes' => array
	(
		''                            => ''
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
        'requesttime' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_dm_downloads']['requesttime'],
            'exclude'                 => true,
            'sorting'                 => true,
            'flag'                    => 5,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        'code' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_dm_downloads']['code'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class' => 'w50', 'rgxp' => 'alnum', 'nospace' => true, 'unique' => true),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'email' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_dm_downloads']['email'],
            'exclude'                 => true,
            'sorting'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'downloadcount' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_dm_downloads']['downloadcount'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>10, 'tl_class' => 'w50', 'rexp' => 'digit'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'singleSRC' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_dm_downloads']['singleSRC'],
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('filesOnly'=>true, 'fieldType'=>'radio', 'mandatory'=>true, 'tl_class'=>'clr'),
            'sql'                     => "binary(16) NULL"
        ),
        'jumpto' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_dm_downloads']['jumpto'],
            'exclude'                 => true,
            'inputType'               => 'pageTree',
            'foreignKey'              => 'tl_page.title',
            'eval'                    => array('fieldType'=>'radio', 'tl_class' => 'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'formid' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_dm_downloads']['formid'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'foreignKey'              => 'tl_form.title',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>10, 'tl_class' => 'w50', 'rexp' => 'digit'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'downloaddata' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_dm_downloads']['downloaddata'],
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('doNotShow'=>true),
            'sql'                     => "text NOTNULL"
        ),
        'requestpage' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_dm_downloads']['requestpage'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('doNotShow'=>true),
            'sql'                     => "varchar(255) NOT NULL default ''"
        )
	)
);

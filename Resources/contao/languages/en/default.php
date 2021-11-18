<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @filesource  default.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:23
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/*
 * Content elements
 */
$GLOBALS['TL_LANG']['CTE']['easy_downloadmail'] = ['easy_Downloadmail', 'easy_Downloadmail'];
$GLOBALS['TL_LANG']['CTE']['downloadmail'] = ['Download request', 'Download request'];


/*
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['mailplaceholder'] = 'Please enter your e-mail address.';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['submit'] = 'Request';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['mandatory'] = 'Required field';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['emaillabel'] = 'E-mail address';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['blacklisterror'] = 'Unfortunately, no download can be requested with this mail address!';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['downloaderror'] = 'Unfortunately, no download could be found with this download code!';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['keyerror'] = 'No download code could be found!';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['fileerror'] = 'File not found!';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['downloadstart'] = 'Start download';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['downloadmessage'] = 'If the download does not start in %s seconds, please use the following link:';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['downloadtolate'] = 'Unfortunately, this download code has expired. You can request the download again here.';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['custrgxperr'] = 'The e-mail address in the %s file cannot be used!';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['notimeerr'] = 'No request time found!';

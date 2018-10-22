<?php
/**
 * @package     downloadmail
 * @filesource  default.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:23
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/**
 * Content elements
 */
$GLOBALS['TL_LANG']['CTE']['easy_downloadmail'] = array('easy_Downloadmail', 'Mit diesem Element kann ein Download angefordert werden.');
$GLOBALS['TL_LANG']['CTE']['downloadmail']      = array('Downloadanforderung', 'Anforderung eines Downloads');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['mailplaceholder']    = 'Bitte geben Sie Ihre Mailadresse ein.';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['submit']             = 'Anfordern';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['mandatory']          = 'Pflichtfeld';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['emaillabel']         = 'E-Mailadresse';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['blacklisterror']     = 'Leider kann mit dieser Mailadresse kein Download angefordert werden!';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['downloaderror']      = 'Leider konnte kein Download mit diesem Downloadkennzeichen gefunden werden!';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['keyerror']           = 'Es konnte kein Downloadkennzeichen gefunden werden!';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['fileerror']          = 'Datei nicht gefunden!';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['downloadstart']      = 'Download starten';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['downloadmessage']    = 'Wenn der Download nicht in %s Sekunden startet, benutzen Sie bitte den folgenden Link:';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['downloadtolate']     = 'Leider ist dieses Downloadkennzeichen abgelaufen. Sie können den Download <a href="%s">hier</a> erneut anfordern.';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['custrgxperr']        = 'Die Mailadresse im Feld %s kann nicht verwendet werden!';
$GLOBALS['TL_LANG']['MSC']['easy_downloadmail']['feform']['notimeerr']          = 'Keine Anfragezeit gefunden!';

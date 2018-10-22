<?php
/**
 * @package     downloadmail
 * @filesource  tl_form.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:33
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/**
 * Tablename: tl_form
 */
$strName = 'tl_form';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strName]['downloadmailform']       = array('Formular für DownloadMail', 'Bei diesem Formular handelt es sich um ein Formular für die DownloadMail-Erweiterung.');
$GLOBALS['TL_LANG'][$strName]['downloadtime']           = array('Gültigkeitsdauer', 'Bitte geben Sie die Gültigkeitsdauer (in Stunden) eines Downloads ein.');
$GLOBALS['TL_LANG'][$strName]['redirecttime']           = array('Zeit bis zum Start des Downloads', 'Bitte geben Sie die Wartezeit bis zum Start des Downloads in Sekunden ein.');
$GLOBALS['TL_LANG'][$strName]['mySingleSRC']            = array('Quelldatei', 'Bitte wählen Sie eine Datei aus der Dateiübersicht.');
$GLOBALS['TL_LANG'][$strName]['downloadtime']           = array('Gültigkeitsdauer', 'Bitte geben Sie die Gültigkeitsdauer (in Stunden) eines Downloads ein.');
$GLOBALS['TL_LANG'][$strName]['redirecttime']           = array('Zeit bis zum Start des Downloads', 'Bitte geben Sie die Wartezeit bis zum Start des Downloads in Sekunden ein.');
$GLOBALS['TL_LANG'][$strName]['jumptodownload']         = array('Downloadseite', 'Bitte wählen Sie die Downloadseite aus.');
$GLOBALS['TL_LANG'][$strName]['mailfrom']               = array('Absender', 'Bitte geben Sie die E-Mailadresse des Absenders ein.');
$GLOBALS['TL_LANG'][$strName]['mailsubject']            = array('Betreff', 'Bitte geben Sie den Betreff der E-Mail ein.');
$GLOBALS['TL_LANG'][$strName]['mailtext']               = array('Mailtext', 'Bitte geben Sie den Text der E-Mail ein. Um den Link einzufügen benutzen Sie bitte den Inserttag {{download::link}} und für die Formulardaten bitte {{download::NAMEdesFORMULARFELDS}}.');
$GLOBALS['TL_LANG'][$strName]['mailbcc']                = array('Empfänger der Bildkopie', 'Bitte geben Sie die E-Mailadresse des Empfänger der Bildkopie ein.');


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strName]['downloadmail_legend']    = 'DownloadMail';
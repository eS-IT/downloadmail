<?php
/**
 * @package     downloadmail
 * @since       18.10.2018 - 10:33
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

declare(strict_types=1);

/*
 * Fields
 */
$GLOBALS['TL_LANG']['tl_settings']['downloadtime']      = ['Gültigkeitsdauer', 'Bitte geben Sie die Gültigkeitsdauer (in Stunden) eines Downloads ein.'];
$GLOBALS['TL_LANG']['tl_settings']['redirecttime']      = ['Zeit bis zum Start des Downloads', 'Bitte geben Sie die Wartezeit bis zum Start des Downloads in Sekunden ein.'];
$GLOBALS['TL_LANG']['tl_settings']['jumptodownload']    = ['Downloadseite', 'Bitte wählen Sie die Downloadseite aus.'];
$GLOBALS['TL_LANG']['tl_settings']['mailfrom']          = ['Absender', 'Bitte geben Sie die E-Mailadresse des Absenders ein.'];
$GLOBALS['TL_LANG']['tl_settings']['mailsubject']       = ['Betreff', 'Bitte geben Sie den Betreff der E-Mail ein.'];
$GLOBALS['TL_LANG']['tl_settings']['mailtext']          = ['Mailtext', 'Bitte geben Sie den Text der E-Mail ein. Um den Link einzufügen benutzen Sie bitte den Inserttag {{download::link}} und für die Formulardaten bitte {{download::NAMEdesFORMULARFELDS}}.'];
$GLOBALS['TL_LANG']['tl_settings']['usetiny']           = ['TinyMCE verwenden', 'Wenn der Haken gesetzt ist, wird für den Mailtext der TinyMCE verwendet.'];
$GLOBALS['TL_LANG']['tl_settings']['mailbcc']           = ['Empfänger der Bildkopie', 'Bitte geben Sie die E-Mailadresse des Empfänger der Bildkopie ein.'];
$GLOBALS['TL_LANG']['tl_settings']['setdownladsuffix']  = ['Suffix für die Downloadseite setzen', 'Ist der Haken gesetzt, kann hier ein abweichender Suffix für die Seite mit dem Download gesetzt werden.'];
$GLOBALS['TL_LANG']['tl_settings']['downloadsuffix']    = ['Suffix für die Downloadseite', 'Bitte geben Sie den Suffix für die Seite mit dem Download ein. Dieser kann auch leer sein.'];


/*
 * Legends
 */
$GLOBALS['TL_LANG']['tl_settings']['easy_downloadmail'] = 'Downloadmail';

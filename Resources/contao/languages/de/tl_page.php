<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @filesource  tl_page.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:33
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/*
 * Fields
 */
$GLOBALS['TL_LANG']['tl_page']['downloadtime'] = ['Gültigkeitsdauer', 'Bitte geben Sie die Gültigkeitsdauer (in Stunden) eines Downloads ein.'];
$GLOBALS['TL_LANG']['tl_page']['redirecttime'] = ['Zeit bis zum Start des Downloads', 'Bitte geben Sie die Wartezeit bis zum Start des Downloads in Sekunden ein.'];
$GLOBALS['TL_LANG']['tl_page']['jumptodownload'] = ['Downloadseite', 'Bitte wählen Sie die Downloadseite aus.'];
$GLOBALS['TL_LANG']['tl_page']['mailfrom'] = ['Absender', 'Bitte geben Sie die E-Mailadresse des Absenders ein.'];
$GLOBALS['TL_LANG']['tl_page']['mailsubject'] = ['Betreff', 'Bitte geben Sie den Betreff der E-Mail ein.'];
$GLOBALS['TL_LANG']['tl_page']['mailtext'] = ['Mailtext', 'Bitte geben Sie den Text der E-Mail ein. Um den Link einzufügen benutzen Sie bitte den Inserttag {{download::link}} und für die Formulardaten bitte {{download::NAMEdesFORMULARFELDS}}.'];
$GLOBALS['TL_LANG']['tl_page']['mailbcc'] = ['Empfänger der Bildkopie', 'Bitte geben Sie die E-Mailadresse des Empfänger der Bildkopie ein.'];


/*
 * Legends
 */
$GLOBALS['TL_LANG']['tl_page']['easy_downloadmail'] = 'Downloadmail';

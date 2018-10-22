<?php
/**
 * @package     downloadmail
 * @filesource  tl_dm_blacklist.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:32
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_dm_blacklist']['title']         = array('Title', 'Bitte geben Sie den Title.');
$GLOBALS['TL_LANG']['tl_dm_blacklist']['pattern']       = array('Muster', 'Bitte geben Sie ein Muster ein. Dies kann ein Teil einer Adresse sein (z.B. info@ oder example.org) oder ein regulärer Ausdruck (hierbei wird Groß- und Kleinschreibung nicht berücksichtigt, Begrenzungszeichen dürfen nicht mit eingegeben weden).');
$GLOBALS['TL_LANG']['tl_dm_blacklist']['regex']         = array('Regulärer Ausdruck', 'Bitte setzen Sie den Haken, wenn es sich bei dem Muster um einen regulären Ausdruck handelt.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_dm_blacklist']['title_legend'] = 'Title';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_dm_blacklist']['new']    = array('Neuer Eintrag ', 'Neuen Eintrag erstellen');
$GLOBALS['TL_LANG']['tl_dm_blacklist']['show']   = array('Details anzeigen', 'Details des Eintrags mit der ID %s anzeigen.');
$GLOBALS['TL_LANG']['tl_dm_blacklist']['edit']   = array('Eintrag bearbeiten ', 'Eintrags mit der ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_dm_blacklist']['cut']    = array('Eintrag verschieben', 'Eintrags mit der ID %s verschieben');
$GLOBALS['TL_LANG']['tl_dm_blacklist']['copy']   = array('Eintrag kopieren', 'Eintrags mit der ID %s kopieren');
$GLOBALS['TL_LANG']['tl_dm_blacklist']['delete'] = array('Eintrag löschen', 'Eintrags mit der ID %s löschen');

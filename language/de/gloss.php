<?php
/**
* gloss.php
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2017 LMDI - Pierre Duhem
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* Deutsche übersetzung: Frank Ingermann
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'LGLOSSAIRE'	=> 'Glossar',
	'TGLOSSAIRE'	=> 'Glossar',

	'GLOSS_ELINK'				=> 'Externer Link: ',

// Installation
	'ROLE_GLOSS_ADMIN'	=> 'Glossar-Administratoren',
	'ROLE_GLOSS_EDITOR'	=> 'Glossar-Bearbeiter',
	'ROLE_DESCRIPTION_GLOSS_ADMIN' => 'Administrations-Rolle zur Verwaltung des Glossars und der Bearbeiter',
	'ROLE_DESCRIPTION_GLOSS_EDITOR' => 'Benutzer-Rolle um Benutzern die Bearbeitung des Glossars zu erlauben',
	'GROUP_GLOSS_ADMIN'				=> 'Glossar-Administratoren',
	'GROUP_DESCRIPTION_GLOSS_ADMIN'	=> 'Gruppe der Glossar-Administratoren',
	'GROUP_GLOSS_EDITOR'			=> 'Glossar-Bearbeiter',
	'GROUP_DESCRIPTION_GLOSS_EDITOR'	=> 'Gruppe der Glossar-Bearbeiter',

// ACP
	'ACP_GLOSS_TITLE'	=> 'Glossar',
	'ACP_GLOSS'		=> 'Einstellungen',
	'ALLOW_FEATURE'		=> 'Glossar aktivieren',
	'ALLOW_FEATURE_EXPLAIN'	=> 'Du kannst das Glossar für das gesamte Board aktivieren/deaktivieren.',
	'ALLOW_TITLE'		=> 'Tooltip aktivieren',
	'ALLOW_TITLE_EXPLAIN'	=> 'Du kannst die Anzeige des Popups mit der Begriffserklärung beim Darüberfahren mit der Maus aktivieren/deaktivieren.',
	'CREATE_UGROUP'		=> 'Benutzergruppe anlegen',
	'CREATE_UGROUP_EXPLAIN'	=> 'Du kannst eine Benutzergruppe anlegen und ihr die Rolle zur Glossar-Bearbeitung zuweisen. Danach kannst Du Bearbeiter zu dieser Gruppe hinzufügen.',
	'CREATE_AGROUP'		=> 'Administratoren-Gruppe anlegen',
	'CREATE_AGROUP_EXPLAIN'	=> 'Du kannst eine Gruppe für die Adminstration des Glossars anlegen. Danach kannst Du die Administatoren der Gruppe hinzufügen.',
	'LANGUAGE'		=> 'Standard-Sprache',
	'LANGUAGE_EXPLAIN'	=> 'Sprache (Vorgabe: Hauptsprache des Boards) in der neue Einträge angelegt werden, wenn bei der Anlage keine andere Sprache angegeben wird.',
	'GLOSS_PIXELS'			=> 'Größe von Bildern in Pixeln',
	'GLOSS_PIXELS_EXPLAIN'	=> 'Stelle hier die Größe von erklärenden Bildern ein (lange Seite).',
	'GLOSS_WEIGHT'			=> 'Größe in kB',
	'GLOSS_WEIGHT_EXPLAIN'	=> 'Stell hier die max. Dateigröße der Bilder in kB ein.',
	'TITLE_LENGTH'		=> 'Länge des Textes im Tootip',
	'TITLE_LENGTH_EXPLAIN'	=>'Stell hier die max. Länge des Textes im Tooltip. Wenn der Text der Begriffserklärung länger ist, wird er abgeschnitten.',
	'ACP_GLOSS_FORUMS'	=> 'Forenauswahl',
	'ACP_GLOSS_ENABLED'	=> 'Glossar aktivieren',


));

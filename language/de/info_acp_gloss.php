<?php
/**
* info_acp_gloss.php
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2021 LMDI - Pierre Duhem
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


// Installation
	'ROLE_GLOSS_ADMIN'	=> 'Glossar-Administratoren',
	'ROLE_GLOSS_EDITOR'	=> 'Glossar-Bearbeiter',
	'ROLE_DESCRIPTION_GLOSS_ADMIN' => 'Administrations-Rolle zur Verwaltung des Glossars und der Bearbeiter',
	'ROLE_DESCRIPTION_GLOSS_EDITOR' => 'Benutzer-Rolle um Benutzern die Bearbeitung des Glossars zu erlauben',
	'GROUP_GLOSS_ADMIN'				=> 'Glossar-Administratoren',
	'GROUP_DESCRIPTION_GLOSS_ADMIN'	=> 'Gruppe der Glossar-Administratoren',
	'GROUP_GLOSS_EDITOR'			=> 'Glossar-Bearbeiter',
	'GROUP_DESCRIPTION_GLOSS_EDITOR'	=> 'Gruppe der Glossar-Bearbeiter',

));

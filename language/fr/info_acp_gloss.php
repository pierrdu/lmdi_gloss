<?php
/**
* info_acp_gloss.php
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2020 LMDI - Pierre Duhem
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
	'ROLE_GLOSS_ADMIN'				=> 'Administrateurs du glossaire',
	'ROLE_DESCRIPTION_GLOSS_ADMIN'	=> 'Modèle des administrateurs chargés de gérer le glossaire et ses éditeurs',
	'GROUP_GLOSS_ADMIN'				=> 'Administrateurs du glossaire',
	'GROUP_DESCRIPTION_GLOSS_ADMIN'	=> 'Groupe des administrateurs du glossaire',
	'ROLE_GLOSS_EDITOR'				=> 'Éditeurs du glossaire',
	'ROLE_DESCRIPTION_GLOSS_EDITOR'	=> 'Modèle des utilisateurs chargés de l’édition du glossaire',
	'GROUP_GLOSS_EDITOR'			=> 'Éditeurs du glossaire',
	'GROUP_DESCRIPTION_GLOSS_EDITOR'	=> 'Groupe des éditeurs du glossaire',
	'GLOSS_ELINK'					=> 'Lien externe&nbsp;: ',

));

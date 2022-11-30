<?php
/**
* info_acp_gloss.php
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2021 LMDI - Pierre Duhem
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
	'ROLE_GLOSS_ADMIN'	=> 'Glossary Administrators',
	'ROLE_GLOSS_EDITOR'	=> 'Glossary Editors',
	'ROLE_DESCRIPTION_GLOSS_ADMIN' => 'Administration role to manage the glossary and its editors',
	'ROLE_DESCRIPTION_GLOSS_EDITOR' => 'User role to be assigned for editing the glossary',
	'GROUP_GLOSS_ADMIN'				=> 'Glossary Administrators',
	'GROUP_DESCRIPTION_GLOSS_ADMIN'	=> 'Group of the glossary administrators',
	'GROUP_GLOSS_EDITOR'			=> 'Glossary Editors',
	'GROUP_DESCRIPTION_GLOSS_EDITOR'	=> 'Group of the glossary editors',
	'GLOSS_ELINK'				=> 'External link: ',

));

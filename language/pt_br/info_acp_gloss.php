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
	'ROLE_GLOSS_ADMIN'			=> 'Administradores do Glossário',
	'ROLE_GLOSS_EDITOR'			=> 'Editores do Glossário',
	'ROLE_DESCRIPTION_GLOSS_ADMIN' 		=> 'Perfil de Administração para gerenciar o glossário e os editores',
	'ROLE_DESCRIPTION_GLOSS_EDITOR' 	=> 'Perfil de usuário definido para edição do glossário',
	'GROUP_GLOSS_ADMIN'			=> 'Administradores do Glossário',
	'GROUP_DESCRIPTION_GLOSS_ADMIN'		=> 'Grupo dos administradores do glossário',
	'GROUP_GLOSS_EDITOR'			=> 'Editores do Glossário',
	'GROUP_DESCRIPTION_GLOSS_EDITOR'	=> 'Grupo dos editores do glossário',
	'GLOSS_ELINK'				=> 'Link externo: ',

));

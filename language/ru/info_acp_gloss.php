<?php
/**
* info_acp_gloss.php
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2021 LMDI - Pierre Duhem
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* Russian translation by rua and MaxTr
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
	'ROLE_GLOSS_ADMIN'	=> 'Администраторы Глоссария',
	'ROLE_GLOSS_EDITOR'  => 'Редакторы Глоссария',
	'ROLE_DESCRIPTION_GLOSS_ADMIN'	=> 'Управление Глоссарием и его редакторами',
	'ROLE_DESCRIPTION_GLOSS_EDITOR'  => 'Пользователь должен быть назначен для редактирования глоссария',
	'GROUP_GLOSS_ADMIN'				  => 'Администратор глоссария',
	'GROUP_DESCRIPTION_GLOSS_ADMIN'  => 'Группа администраторов Глоссария',
	'GROUP_GLOSS_EDITOR'				 => 'Редакторы глоссария',
	'GROUP_DESCRIPTION_GLOSS_EDITOR' => 'Группа редакторов Глоссария',

));

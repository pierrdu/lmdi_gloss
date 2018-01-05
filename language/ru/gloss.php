<?php
/**
* gloss.php
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2018 LMDI - Pierre Duhem
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
	'LGLOSSAIRE'	=> 'Глоссарий',
	'TGLOSSAIRE'	=> 'Глоссарий',

	'GLOSS_ELINK'	=> 'Внешняя ссылка: ',

// Installation
	'ROLE_GLOSS_ADMIN'	=> 'Администраторы Глоссария',
	'ROLE_GLOSS_EDITOR'  => 'Редакторы Глоссария',
	'ROLE_DESCRIPTION_GLOSS_ADMIN'	=> 'Управление Глоссарием и его редакторами',
	'ROLE_DESCRIPTION_GLOSS_EDITOR'  => 'Пользователь должен быть назначен для редактирования глоссария',
	'GROUP_GLOSS_ADMIN'				  => 'Администратор глоссария',
	'GROUP_DESCRIPTION_GLOSS_ADMIN'  => 'Группа администраторов Глоссария',
	'GROUP_GLOSS_EDITOR'				 => 'Редакторы глоссария',
	'GROUP_DESCRIPTION_GLOSS_EDITOR' => 'Группа редакторов Глоссария',
// ACP
	'ACP_GLOSS_TITLE'		 => 'Глоссарий',
	'ACP_GLOSS'				 => 'Настройки',
	'ALLOW_FEATURE'			=> 'Включить Словарь терминов',
	'ALLOW_FEATURE_EXPLAIN' => 'Вы можете включить/отключить функцию словаря на конференции.',
	'ALLOW_TITLE'			  => 'Включить всплывающие подсказки',
	'ALLOW_TITLE_EXPLAIN'	=> 'Вы можете включить/отключить отображение описания термина в подсказке при наведении курсора на слово в тексте.',
	'CREATE_UGROUP'			=> 'Создание группы пользователей',
	'CREATE_UGROUP_EXPLAIN' => 'Вы можете создать группу Редакторов Глоссария и установить ей права доступа. После этого вы можете добавить в эту группу пользователей.',
	'CREATE_AGROUP'			=> 'Создание группы администраторов',
	'CREATE_AGROUP_EXPLAIN' => 'Вы можете создать группу для управления Глоссарием. После этого вы можете добавить администраторов к этой группе.',
	'LANGUAGE'				  => 'Язык по умолчанию',
	'LANGUAGE_EXPLAIN'		=> 'Код языка (язык конференции по умолчанию), который будет использоваться для словаря термина, если вы не укажите другой язык при редакции термина.',
	'GLOSS_PIXELS'			 => 'Размер загружаемых фотографий в пикселях',
	'GLOSS_PIXELS_EXPLAIN'  => 'Укажите размер (в пикселях) по длинной стороне загружаемых изображений.',
	'GLOSS_WEIGHT'					  => 'Размер в kB',
	'GLOSS_WEIGHT_EXPLAIN'			=> 'Укажите максимальный размер (в Кб) для загружаемых изображений.',
	'TITLE_LENGTH'			 => 'Длина текста подсказки',
	'TITLE_LENGTH_EXPLAIN'  => 'Укажите максимальную длину текста (количество знаков), отображаемого в всплывающей подсказке. Значение термина будет обрезано, если привысит кол-во знаков.',
	'ACP_GLOSSARY_FORUMS'	=> 'Forum selection',
	'ACP_GLOSSARY_ENABLED'	=> 'Enable glossary',

));

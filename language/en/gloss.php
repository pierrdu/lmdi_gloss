<?php
/**
* gloss.php
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
	'LGLOSSAIRE'	=> 'Glossary',
	'TGLOSSAIRE'	=> 'Extension setting',


// ACP
	'ACP_GLOSS_TITLE'	=> 'Glossary',
	'ACP_GLOSS'		=> 'Settings',
	'ALLOW_FEATURE'		=> 'Enable Glossary Feature',
	'ALLOW_FEATURE_EXPLAIN'	=> 'You may enable/disable the glossary tagging feature for the whole board.',
	'ALLOW_TITLE'		=> 'Enable Tooltip',
	'ALLOW_TITLE_EXPLAIN'	=> 'You may enable/disable the display of the term description in a tooltip when hovering over the term.',
	'CREATE_UGROUP'		=> 'Creation of an usergroup',
	'CREATE_UGROUP_EXPLAIN'	=> 'You may create an usergroup and assign to it the glossary editor role created when installing the extension. You may then add users to this group.',
	'CREATE_AGROUP'		=> 'Creation of an administrator group',
	'CREATE_AGROUP_EXPLAIN'	=> 'You may create a group to manage the glossary administrators. You may then add administrators to this group.',
	'LANGUAGE'		=> 'Default language',
	'LANGUAGE_EXPLAIN'	=> 'Language code (board language by default) which will be registered in the base for the glossary term if you don\'t specify another language in the edition form.',
	'GLOSS_PIXELS'			=> 'Size of uploaded pictures in pixels',
	'GLOSS_PIXELS_EXPLAIN'	=> 'Set here the size (in pixels) of the long side of the uploaded pictures.',
	'GLOSS_WEIGHT'			=> 'Size in kB',
	'GLOSS_WEIGHT_EXPLAIN'	=> 'Set here the maximum size (in kB) of the uploaded pictures.',
	'TITLE_LENGTH'		=> 'Length of the tooltip text',
	'TITLE_LENGTH_EXPLAIN'	=>'Set here the maximum length of the text displayed by the tooltip. The description text will be truncated if longer.',
	'ACP_GLOSSARY_FORUMS'	=> 'Forum selection',
	'ACP_GLOSSARY_ENABLED'	=> 'Enable glossary',

));

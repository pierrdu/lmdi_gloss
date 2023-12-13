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
	'LGLOSSAIRE'	=> 'Glossário',
	'TGLOSSAIRE'	=> 'Configurações da extensão',


// ACP
	'ACP_GLOSS_TITLE'	=> 'Glossário',
	'ACP_GLOSS'		=> 'Configurações',
	'ALLOW_FEATURE'		=> 'Ativar recurso de glossário',
	'ALLOW_FEATURE_EXPLAIN'	=> 'Você pode ativar/desativar a marcação de termos do glossário para todo o forum.',
	'ALLOW_TITLE'		=> 'Ativar resumo',
	'ALLOW_TITLE_EXPLAIN'	=> 'Você pode ativar/desativar a apresentação do resumo quando passando sobre o termo.',
	'CREATE_UGROUP'		=> 'Criação de grupo de usuários',
	'CREATE_UGROUP_EXPLAIN'	=> 'Você pode criar um grupo de usuários e definir ao perfil de editor do glossário. Você pode então adicionar os editores a esse grupo.',
	'CREATE_AGROUP'		=> 'Criação de grupo de admistradores',
	'CREATE_AGROUP_EXPLAIN'	=> 'Você pode criar um grupo para gerenciar administradores de glossário. Você pode então adicionar administradores a esse grupo.',
	'LANGUAGE'		=> 'Linguagem padrão',
	'LANGUAGE_EXPLAIN'	=> 'Código de linguagem padrão (por padrão a do fórum) que será definido no termo de glossário se não for especificada outra linguaguem no formulario de edição.',
	'GLOSS_PIXELS'		=> 'Tamanho das imagens em pixels',
	'GLOSS_PIXELS_EXPLAIN'	=> 'Defina o tamanho (em pixels) do lado maior das imagens enviadas.',
	'GLOSS_WEIGHT'		=> 'Tamanho em kB',
	'GLOSS_WEIGHT_EXPLAIN'	=> 'Defina aqui o tamanho máximo (em kB) das imagens enviadas.',
	'TITLE_LENGTH'		=> 'Tamanho do texto de resumo',
	'TITLE_LENGTH_EXPLAIN'	=> 'Defina aqui o tamanho máximo do texto apresentado como resumo. O texto será truncado se for maior.',
	'ACP_GLOSSARY_FORUMS'	=> 'Seleção de fóruns',
	'ACP_GLOSSARY_ENABLED'	=> 'Ativar glossário',

));

<?php
/**
* edit_gloss.php
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
// Static Glossary page
	'ILLUSTRATION'		=>  "<p>Alguns termos tem ilustração explicativa.<br />Nesse caso, existe um link no final da linha.<br />Clique nele para ver a imagem.<br />Clique novamente na imagem para retornar.</p>",
	'GLOSS_DISPLAY'		=> 'Ver',
	'GLOSS_CLICK'		=> 'Clique na imagem para retornar a página anterior.',
	'GLOSS_VIEW'		=> 'Visualizar Glossário',
	'GLOSS_ILINK'		=> 'Veja também: ',
	'GLOSS_ELINK'		=> 'Link externo: ',
	'BACKTOP'		=> 'Topo',
// Glossary edition page
	'GLOSS_UNALLOWED'	=> 'Você não tem permissões para editar o glossário. Clique <a href="%s">aqui</a>.',
	'GLOSS_EDIT'		=>'Edição de item do Glossário',
	'GLOSS_CREAT'		=>'Criação de item do Glossário',
	'GLOSS_VARIANTS' 	=> 'Termos a pesquisar nas publicações',
	'GLOSS_VARIANTS_EX' 	=> 'Um ou mais termos, separados por virgula.',
	'GLOSS_TERM'		=> 'Termo apresentado',
	'GLOSS_TERM_EX' 	=> 'Termo a ser utilizado como titulo da janela.',
	'GLOSS_DESC'		=> 'Definição do termo',
	'GLOSS_PICT'		=> 'Imagem',
	'GLOSS_REGIS'		=> 'Salvar',
	'GLOSS_SUPPR'		=> 'Excluir',
	'GLOSS_ADMINISTRATION'	=> 'Página de Administração do Glossário',
	'GLOSS_EDITION'		=> 'Páginas de Edição do Glossário',
	'GLOSS_ED_TERM'		=> 'Termo',
	'GLOSS_ED_DEF'		=> 'Definição',
	'GLOSS_ED_CAT'		=> 'Categoria',
	'GLOSS_ED_CATEX'	=> 'For instance gender, etc.',
	'GLOSS_ED_ILINKS'	=> 'Links internos',
	'GLOSS_ED_ILEXP'	=> 'Itens de glossário, separador por virgula para referenciar.',
	'GLOSS_ED_ELINKS'	=> 'Link externo',
	'GLOSS_ED_ELEXP'	=> 'Link para um tópico, página etc. Formato: URL.',
	'GLOSS_ED_LABEL'	=> 'Título do link',
	'GLOSS_ED_LABEX'	=> 'Texto para identificar o link externo.',
	'GLOSS_ED_PICT'		=> 'Imagem',
	'GLOSS_ED_PIEXPL'	=> 'Nome de um arquivo de imagem (jpg, jpeg, gif or png). Disponivel na pasta images/lmdi/gloss.',
	'GLOSS_ED_UPLOAD'	=> 'Upload:',
	'GLOSS_ED_NOUP'		=> 'Sem arquivo para enviar',
	'GLOSS_ED_REUSE'	=> 'Arquivo a ser reutilizado',
	'GLOSS_ED_EXISTE'	=> 'Arquivo registrado',
	'GLOSS_ED_ACT'		=> 'Ação',
	'GLOSS_ED_EXPL'		=> '<p>Um link para edição existe na coluna de Ação para cada item.<br />%s<b>Clique aqui</b>%s para criar uma nova entrada.</p>',
	'GLOSS_ED_EDIT'		=> 'Editar',
	'GLOSS_LANG'		=> 'Linguagem',
	'GLOSS_ED_DOUBLON'	=> 'O termo %s já existe.<br />%s<b>Clique aqui</b>%s para retornar a página de edição.',
	'GLOSS_ED_SAVE'		=> 'O termo %s foi salvo.<br />%s<b>Clique aqui</b>%s para retornar a página de administração.',
	'GLOSS_ED_DELETE'	=> 'O termo %s foi removido.<br />%s<b>Clique aqui</b>%s para retornar a página de administração.',

	'LMDI_GLOSS_NOFILE'	=> 'Sem arquivo para enviar.',
	'LMDI_GLOSS_DISALLOWED_CONTENT'		=> 'O uload foi interrompido por o arquivo foi identificado como possivelmente perigoso.',
	'LMDI_GLOSS_DISALLOWED_EXTENSION'	=> 'A extensão de arquivo <strong>%s</strong> não é permitida.',
	'LMDI_GLOSS_EMPTY_FILEUPLOAD'		=> 'O arquivo está vazio.',
	'LMDI_GLOSS_EMPTY_REMOTE_DATA'		=> 'O arquivo parece incorreto ou corrompido.',
	'LMDI_GLOSS_IMAGE_FILETYPE_MISMATCH'	=> 'Tipo de arquivo inesperado: experado %1$s mas encontrado %2$s.',
	'LMDI_GLOSS_INVALID_FILENAME'		=> '%s é um nome de arquivo invalido.',
	'LMDI_GLOSS_NOT_UPLOADED'		=> 'Nenhum arquivo enviado',
	'LMDI_GLOSS_PARTIAL_UPLOAD'		=> 'O arquivo não foi transferido completamente.',
	'LMDI_GLOSS_PHP_SIZE_NA'		=> 'O arquivo é muito grande.<br />O tamanho máximo definido no php.ini não pode ser determinado".',
	'LMDI_GLOSS_PHP_SIZE_OVERRUN'		=> 'O arquivo é muito grande. O tamanho maximo permitido é %d Mo.<br />Esse valor foi definido em php.ini e não pode ser ignorado.',
	'LMDI_GLOSS_REMOTE_UPLOAD_TIMEOUT'	=> 'Não foi possivel enviar arquivo, tempo limite excedido.',
	'LMDI_GLOSS_UNABLE_GET_IMAGE_SIZE'	=> 'Não foi possivel determinar as dimensões do arquivo',
	'LMDI_GLOSS_WRONG_FILESIZE'		=> 'O arquivo deve ter menos de %1d kB.',
	'LMDI_GLOSS_WRONG_SIZE'			=> 'O arquivo tem %5$s de largura e %6$s de altura.<br>Imagens devem ter pelo menos %1$s de largura e %2$s de altura, mas menos de %3$s de largura e %4$s de altura.',
	'LMDI_CLICK_BACK'			=> '<a href="javascript:history.go(-1);"><b>Clique aqui</b></a> para retornar ao formulario de edição.',
));

<?php
/**
* gloss.php
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2016 LMDI - Pierre Duhem
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

$lang = array_merge ($lang, array(
// Static Glossary page
	'ILLUSTRATION'	=>  "<p>Some terms have an explicative illustration.<br />In such a case, there is a link at the end of the row.<br />Click on it to display the picture.<br />Click on the picture again to come back.</p>",
	'GLOSS_DISPLAY'	=> 'Display',
	'GLOSS_CLICK'		=> 'Click on the picture to come back to previous page.',
	'GLOSS_VIEW'		=> 'Glossary Viewer',
	'GLOSS_ILINKS'		=> 'See also: ',
	'GLOSS_ELINKS'		=> 'External link: ',
	'GLOSS_BIBLIO'		=> "<p>
		<span class=\"m\">
		<u>Bibliography</u><br /> 
		<br /><br /> 
		<u>Webography</u><br /><br />
		<br />
		<u>Illustrations</u><br /><br />
		<br /> 
		</span></p>",
// Glossary edition page
	'GLOSS_EDIT'	=>'Glossary Item Edition',
	'GLOSS_CREAT'	=>'Glossary Item Creation',
	'GLOSS_VARIANTS' => 'Terms to search in the posts',
	'GLOSS_VARIANTS_EX' => 'One or several terms, separated by a comma.',
	'GLOSS_TERM'	=> 'Term displayed',
	'GLOSS_TERM_EX' => 'Term to use as title in the popup window.',
	'GLOSS_DESC'	=> 'Definition of term',
	'GLOSS_PICT'	=> 'Picture',
	'GLOSS_REGIS'	=> 'Save',
	'GLOSS_SUPPR'	=> 'Delete',
	'GLOSS_EDITION'	=> 'Glossary Edition Page',
	'GLOSS_ED_TERM'	=> 'Term',
	'GLOSS_ED_DEF'		=> 'Definition',
	'GLOSS_ED_CAT'		=> 'Category',
	'GLOSS_ED_CATEX'	=> 'For instance gender, etc.',
	'GLOSS_ED_ILINKS'	=> 'Internal links',
	'GLOSS_ED_ILEXP'	=> 'Glossary items, comma separated, to use as anchor points.',
	'GLOSS_ED_ELINKS'	=> 'External link',
	'GLOSS_ED_ELEXP'	=> 'Link toward a topic, a page, etc. Syntax: plain URL.',
	'GLOSS_ED_LABEL'	=> 'Label of link',
	'GLOSS_ED_LABEX'	=> 'String to identify the external link.',
	'GLOSS_ED_PICT'	=> 'Picture',
	'GLOSS_ED_PIEXPL'	=> 'Name of the picture file (jpg, jpeg, gif or png). Uploaded in the folder ext/lmdi/gloss/glossaire.',
	'GLOSS_ED_UPLOAD'	=> 'Upload:',
	'GLOSS_ED_NOUP'	=> 'No file to upload',
	'GLOSS_ED_REUSE'	=> 'File to be reused',
	'GLOSS_ED_EXISTE'	=> 'Registered file',
	'GLOSS_ED_ACT'		=> 'Action',
	'GLOSS_ED_EXPL'	=> '<p>An edition link exists in the Action column for each entry.<br>To create a new entry, click %s<b>here</b>%s.</p>',
	'GLOSS_ED_EDIT'	=> 'Edit',
	'GLOSS_LANG'		=> 'Language',
	'LMDI_GLOSS_DISALLOWED_CONTENT'	=> 'Upload has been interrupted because the file had been identified as a potential threat.',
	'LMDI_GLOSS_DISALLOWED_EXTENSION'	=> 'The file extension <strong>%s</strong> is not allowed.',
	'LMDI_GLOSS_EMPTY_FILEUPLOAD'		=> 'The file is empty.',
	'LMDI_GLOSS_EMPTY_REMOTE_DATA'	=> 'The file data seem incorrect or corrupted.',
	'LMDI_GLOSS_IMAGE_FILETYPE_MISMATCH'	=> 'File type mismatch: expected extension %1$s but extension %2$s found.',
	'LMDI_GLOSS_INVALID_FILENAME'		=> '%s is an invalid file name.',
	'LMDI_GLOSS_NOT_UPLOADED'		=> 'No file was uploaded',
	'LMDI_GLOSS_PARTIAL_UPLOAD'		=> 'The file was not completly transfered.',
	'LMDI_GLOSS_PHP_SIZE_NA'			=> 'The file size is too high.<br />The maximum size set in php.ini could not be determined".',
	'LMDI_GLOSS_PHP_SIZE_OVERRUN'		=> 'The file size is too high. The maximum size allowed is %d Mo.<br />Please note that this value is set in php.ini and cannot be outreached.',
	'LMDI_GLOSS_REMOTE_UPLOAD_TIMEOUT'	=> 'The specified file could not be uploaded because the request timed out.',
	'LMDI_GLOSS_UNABLE_GET_IMAGE_SIZE'	=> 'It was not possible to determine the file dimensions',
	'LMDI_GLOSS_WRONG_FILESIZE'		=> 'The file size must be below %1d kB.',
	'LMDI_GLOSS_WRONG_SIZE'			=> 'The specified file is %5$s wide and %6$s high. Glossar pictures must be at least %1$s wide and %2$s high, but no larger than %3$s wide and %4$s high.',
  	'LMDI_CLICK_BACK'				=> 'Click <a href="javascript:history.go(-1);"><b>here</b></a> to come back to the edition form.',
// Glossary cleaning page
	'LMDI_GLOSS_CLEAN'				=> 'Cleaning successfully done.',
	'GLOSS_CLEAN'					=> 'Cleaning of data structures',
));

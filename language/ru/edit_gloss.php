<?php
/**
* edit_gloss.php
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
	'ILLUSTRATION'	=>  "Некоторые термины имеют иллюстрацию.<br />В таком случае линк в конце.<br />Нажмите для показа картинки.<br />Нажмите еще раз чтобы свернуть.",
	'GLOSS_DISPLAY'	=> 'Отобразить',
	'GLOSS_CLICK'		=> 'Нажмите на картинку чтобы вернуться назад.',
	'GLOSS_VIEW'		=> 'Просмотр глоссария',
	'GLOSS_ILINKS'		=> 'Смотри также: ',
	'GLOSS_ELINKS'		=> 'Внешние ссылки: ',
	'GLOSS_BIBLIO'		=> "
		<span class=\"m\">
		<u>Библиография</u><br /> 
		<br /><br /> 
		<u>Вебография</u><br /><br />
		<br />
		<u>Иллюстрации</u><br /><br />
		<br /> 
		</span>",
// Glossary edition page
	'GLOSS_EDIT'	=>'Редактировать термин глоссария',
	'GLOSS_CREAT'	=>'Создать термин глоссария',
	'GLOSS_VARIANTS' => 'Термин для подстановки',
	'GLOSS_VARIANTS_EX' => 'Один или несколько терминов, разделенных запятой.',
	'GLOSS_TERM'	=> 'Отображаемый термин',
	'GLOSS_TERM_EX' => 'Используемый термин в всплывающем окне.',
	'GLOSS_DESC'	=> 'Определение термина',
	'GLOSS_PICT'	=> 'Картинка',
	'GLOSS_REGIS'	=> 'Сохранить',
	'GLOSS_SUPPR'	=> 'Удалить',
	'GLOSS_EDITION'	=> 'Страница редактирования глоссария',
	'GLOSS_ED_TERM'	=> 'Термин',
	'GLOSS_ED_DEF'		=> 'Определение',
	'GLOSS_ED_CAT'		=> 'Категория',
	'GLOSS_ED_CATEX'	=> 'Обозначьте категорию термина.',
	'GLOSS_ED_ILINKS'	=> 'Внутренняя ссылка',
	'GLOSS_ED_ILEXP'	=> 'Термины глоссария, разделенные запятой, к которым прицепляется линк.',
	'GLOSS_ED_ELINKS'	=> 'Внешняя ссылка',
	'GLOSS_ED_ELEXP'	=> 'Ссылка, ведущая на страницу с обсуждением.',
	'GLOSS_ED_LABEL'	=> 'Метка ссылки',
	'GLOSS_ED_LABEX'	=> 'Строка, определяющая внешнюю ссылку.',
	'GLOSS_ED_PICT'	=> 'Картинка',
	'GLOSS_ED_PIEXPL'	=> 'Имя файла изображения (jpg, jpeg, gif или png). Загруженная в папку ext/lmdi/gloss/glossaire.',
	'GLOSS_ED_UPLOAD'	=> 'Загрузить:',
	'GLOSS_ED_NOUP'	=> 'Нет файла для загрузки.',
	'GLOSS_ED_REUSE'	=> 'Использовать уже имеющееся изображение',
	'GLOSS_ED_EXISTE'	=> 'Зарегестрированный файл',
	'GLOSS_ED_ACT'		=> 'Действие',
	'GLOSS_ED_EXPL'	=> 'Ссылка на редактирование для каждого действия.<br>Чтобы создать новую, кликните %s<b>тут</b>%s.</p> ',
	'GLOSS_ED_EDIT'	=> 'Редактировать',
	'GLOSS_LANG'		=> 'Язык',
	'LMDI_GLOSS_NOFILE'	=> 'Нет файла для загрузки.',
	'LMDI_GLOSS_DISALLOWED_CONTENT'	=> 'Загрузка была прервана, т.к. файл определился как потенциально опасный.',
	'LMDI_GLOSS_DISALLOWED_EXTENSION'	=> 'Расширение файла <strong>%s</strong> запрещено.',
	'LMDI_GLOSS_EMPTY_FILEUPLOAD'		=> 'Файл пустой.',
	'LMDI_GLOSS_EMPTY_REMOTE_DATA'	=> 'Файл битый или неправильного формата.',
	'LMDI_GLOSS_IMAGE_FILETYPE_MISMATCH'	=> 'Несовпадение типа файла: ожидаймый формат %1$s но обнаружен %2$s ',
	'LMDI_GLOSS_INVALID_FILENAME'		=> '%s неправильное имя файла.',
	'LMDI_GLOSS_NOT_UPLOADED'		=> 'Файл не был загружен.',
	'LMDI_GLOSS_PARTIAL_UPLOAD'		=> 'Файл не был полностью загружен.',
	'LMDI_GLOSS_PHP_SIZE_NA'			=> 'Размер файла слишком большой.".',
	'LMDI_GLOSS_PHP_SIZE_OVERRUN'		=> 'Размер файла слишком большой. Максимальный размер %d Mo.',
	'LMDI_GLOSS_REMOTE_UPLOAD_TIMEOUT'	=> 'Таймаут заргузки.',
	'LMDI_GLOSS_UNABLE_GET_IMAGE_SIZE'	=> 'Невозможно определить размер изображения',
	'LMDI_GLOSS_WRONG_FILESIZE'		=> 'Размер файла должен быть ниже %1d kB.',
	'LMDI_GLOSS_WRONG_SIZE'			=> 'Ширина файла %3$d пикселей и высота %4$d пикселей. Файл не может быть шире %1$d пикселей и %2$d в высоту.',
	'LMDI_CLICK_BACK'				=> 'Нажмите <a href="javascript:history.go(-1);"><b>тут</b></a> чтобы вернуться к редактированию.',
// Glossary cleaning page
	'LMDI_GLOSS_CLEAN'				=> 'Очистка успешно выполнена.',
	'GLOSS_CLEAN'					=> 'Очистка данных.',

));

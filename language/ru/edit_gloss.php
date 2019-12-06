<?php
/**
* edit_gloss.php
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2019 LMDI - Pierre Duhem
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

$lang = array_merge ($lang, array(
// Static Glossary page
	'ILLUSTRATION'	=>  "Некоторые термины имеют поясняющие иллюстрации.<br />В этом случае, в конце строки есть ссылка на изображение.<br />Нажмите на нее, чтобы открыть рисунок.<br />Нажмите на картинку еще раз, чтобы свернуть.",
	'GLOSS_DISPLAY'			  => 'Отобразить',
	'GLOSS_CLICK'		=> 'Нажмите на картинку чтобы вернуться назад.',
	'GLOSS_VIEW'		=> 'Просмотр глоссария',
	'GLOSS_ILINKS'		=> 'Смотрите также: ',
	'GLOSS_ELINKS'		=> 'Внешние ссылки: ',
	'BACKTOP'			=> 'Top',
// Glossary edition page
	'GLOSS_EDIT'	=>'Редактировать термин глоссария',
	'GLOSS_CREAT'	=>'Создать термин глоссария',
	'GLOSS_VARIANTS' => 'Варианты термина (учитывается при поиске)',
	'GLOSS_VARIANTS_EX' => 'Одно или несколько определений, разделенных запятой.',
	'GLOSS_TERM'	=> 'Термин (отображение в Глоссарии)',
	'GLOSS_TERM_EX' => 'Значение термина, как есть, для использования во всплывающем окне.',
	'GLOSS_DESC'	=> 'Определение термина',
	'GLOSS_PICT'	=> 'Рисунок',
	'GLOSS_REGIS'	=> 'Сохранить',
	'GLOSS_SUPPR'	=> 'Удалить',
	'GLOSS_ADMINISTRATION'	=> 'Страница редактирования глоссария',
	'GLOSS_EDITION'	=> 'Glossary Edition Page',
	'GLOSS_ED_TERM'	=> 'Термин',
	'GLOSS_ED_DEF'		=> 'Определение',
	'GLOSS_ED_CAT'		=> 'Категория',
	'GLOSS_ED_CATEX'	=> 'Обозначьте категорию термина.',
	'GLOSS_ED_ILINKS'	=> 'Внутренние ссылки',
	'GLOSS_ED_ILEXP'	=> 'Перечень определений, через запятую, чтобы использовать их в качестве анкоров.',
	'GLOSS_ED_ELINKS'	=> 'Внешняя ссылка',
	'GLOSS_ED_ELEXP'	=> 'Ссылка к теме, страницы и т.д. Синтаксис: обычный URL.',
	'GLOSS_ED_LABEL'	=> 'Название ссылки',
	'GLOSS_ED_LABEX'	=> 'Описание для внешней ссылки.',
	'GLOSS_ED_PICT'	=> 'Изображение',
	'GLOSS_ED_PIEXPL'	=> 'Имя файла изображения (jpg, jpeg, gif or png). Если файл находится в папке store/lmdi/gloss.',
	'GLOSS_ED_UPLOAD'	=> 'Загрузить:',
	'GLOSS_ED_NOUP'	=> 'Нет файла для загрузки.',
	'GLOSS_ED_REUSE'	=> 'Использовать уже имеющееся изображение',
	'GLOSS_ED_EXISTE'	=> 'Зарегистрированный файл',
	'GLOSS_ED_ACT'		=> 'Действие',
	'GLOSS_ED_EXPL'	=> 'Ссылка правки активна для каждого Термина.<br />Чтобы создать новую запись, нажмите %s<b>создать</b>%s ',
	'GLOSS_ED_EDIT'	=> 'Править',
	'GLOSS_LANG'		=> 'Язык',
	'GLOSS_ED_DOUBLON'	=> 'The glossary term %s exists already.<br />%s<b>Click here</b>%s to come back to the edition page.',
	'GLOSS_ED_SAVE'	=> 'The glossary term %s was successfully saved.<br />%s<b>Click here</b>%s to come back to the administration page.',
	'GLOSS_ED_DELETE'	=> 'The glossary term %s was successfully deleted.<br />%s<b>Click here</b>%s to come back to the administration page.',


	'LMDI_GLOSS_NOFILE'	=> 'Нет файла для загрузки.',
	'LMDI_GLOSS_DISALLOWED_CONTENT'	=> 'Загрузка была прервана, т.к. файл определился как потенциально опасный.',
	'LMDI_GLOSS_DISALLOWED_EXTENSION'	=> 'Расширение файла <strong>%s</strong> запрещено.',
	'LMDI_GLOSS_EMPTY_FILEUPLOAD'		=> 'Файл пустой.',
	'LMDI_GLOSS_EMPTY_REMOTE_DATA'		=> 'Файл битый или неправильного формата.',
	'LMDI_GLOSS_IMAGE_FILETYPE_MISMATCH'	=> 'Несовпадение типа файла: ожидаемый формат %1$s фактический  %2$s ',
	'LMDI_GLOSS_INVALID_FILENAME'		=> '%s неправильное название файла.',
	'LMDI_GLOSS_NOT_UPLOADED'		=> 'Файл не был загружен.',
	'LMDI_GLOSS_PARTIAL_UPLOAD'		=> 'Файл не был полностью загружен.',
	'LMDI_GLOSS_PHP_SIZE_NA'			=> 'Размер файла слишком большой.".',
	'LMDI_GLOSS_PHP_SIZE_OVERRUN'		=> 'Размер файла слишком большой. Максимальный размер %d Mb.',
	'LMDI_GLOSS_REMOTE_UPLOAD_TIMEOUT'	=> 'Таймаут загрузки.',
	'LMDI_GLOSS_UNABLE_GET_IMAGE_SIZE'	=> 'Невозможно определить размер изображения',
	'LMDI_GLOSS_WRONG_FILESIZE'		=> 'Размер файла должен быть менее %1d kB.',
	'LMDI_GLOSS_WRONG_SIZE'			=> 'Ширина файла %3$d пикселей и высота %4$d пикселей. Файл не может быть шире %1$d пикселей и %2$d в высоту.',
	'LMDI_CLICK_BACK'				=> '<a href="javascript:history.go(-1);"><b>Нажмите тут</b></a> чтобы вернуться к редактированию.',
));

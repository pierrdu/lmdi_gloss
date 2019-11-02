<?php
/**
* edit_gloss.php
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2019 LMDI - Pierre Duhem
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* Deutsche übersetzung: Frank Ingermann
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
	'ILLUSTRATION'	=>  "<p>Begriffe können erläuternde Bilder haben.<br />In diesem Fall gibt es einen Link am Ende der Zeile.<br />Klicke darauf um das Bild anzuzeigen.<br />Klicke erneut auf das Bild um zurückzukehren.</p>",
	'GLOSS_DISPLAY'	=> 'Anzeige',
	'GLOSS_CLICK'		=> 'Klicke auf das Bild um zur vorherigen Seite zurückzukehren.',
	'GLOSS_VIEW'		=> 'Glossar-Betrachter',
	'GLOSS_ILINKS'		=> 'Siehe auch: ',
	'GLOSS_ELINKS'		=> 'Externer Link: ',
// Glossary edition page
	'GLOSS_EDIT'	=>'Glossar-Eintrag bearbeiten',
	'GLOSS_CREAT'	=>'Glossar-Eintrag anlegen',
	'GLOSS_VARIANTS' => 'Begriffe/Abkürzungen, nach denen im Beitrag gesucht wird',
	'GLOSS_VARIANTS_EX' => 'Einer oder mehrere Begriffe (mit Komma getrennt).',
	'GLOSS_TERM'	=> 'Angezeigter Begriff',
	'GLOSS_TERM_EX' => 'Im Popup-Fenster anzuzeigender Begriff.',
	'GLOSS_DESC'	=> 'Definition des Begriffs',
	'GLOSS_PICT'	=> 'Bild',
	'GLOSS_REGIS'	=> 'Speichern',
	'GLOSS_SUPPR'	=> 'Löschen',
	'GLOSS_EDITION'	=> 'Glossar-Bearbeitungsseite',
	'GLOSS_ED_TERM'	=> 'Begriff',
	'GLOSS_ED_DEF'		=> 'Definition',
	'GLOSS_ED_CAT'		=> 'Kategorie',
	'GLOSS_ED_CATEX'	=> 'Z.B. Abkürzung, technischer Begriff...',
	'GLOSS_ED_ILINKS'	=> 'Interne Links',
	'GLOSS_ED_ILEXP'	=> 'Glossar-Einträge (Komma-getrennt) die als Ankerpunkte verwendet werden.',
	'GLOSS_ED_ELINKS'	=> 'Externer Link',
	'GLOSS_ED_ELEXP'	=> 'Link auf ein Thema, einen Beitrag, eine Seite etc. Syntax: einfach die URL.',
	'GLOSS_ED_LABEL'	=> 'Beschriftung des Links',
	'GLOSS_ED_LABEX'	=> 'Text der für den Link angezeigt wird.',
	'GLOSS_ED_PICT'	=> 'Bild',
	'GLOSS_ED_PIEXPL'	=> 'Dateiname der Bilddatei (jpg, jpeg, gif oder png). Upload in den Ordner store/lmdi/gloss.',
	'GLOSS_ED_UPLOAD'	=> 'Upload:',
	'GLOSS_ED_NOUP'	=> 'Kein Bild hochladen',
	'GLOSS_ED_REUSE'	=> 'Bild wiederverwenden',
	'GLOSS_ED_EXISTE'	=> 'Existierendes Bild',
	'GLOSS_ED_ACT'		=> 'Aktion',
	'GLOSS_ED_EXPL'	=> '<p>Es gibt einen Bearbeitungs-Link in der Spalte Aktion für jeden Eintrag.<br /> %s<b>Klicke hier</b>%s, um einen neuen Eintrag anzulegen.</p>',
	'GLOSS_ED_EDIT'	=> 'Bearbeiten',
	'GLOSS_LANG'		=> 'Sprache',
	'GLOSS_ED_SAVE'	=> 'Der Eintrag %s wurde gespeichert.<br />%s<b>Klicke hier</b>%s, um zur Glossar-Bearbeitungsseite zurückzukehren.',
	'GLOSS_ED_DELETE'	=> 'Der Eintrag %s wurde gelöscht.<br />%s<b>Klicke hier</b>%s, um zur Glossar-Bearbeitungsseite zurückzukehren.',

	'LMDI_GLOSS_NOFILE'	=> 'Keine Dateinamen zum Hochladen.',
	'LMDI_GLOSS_DISALLOWED_CONTENT'	=> 'Das Hochladen wurde unterbrochen weil die Datei als potentielle Bedrohung erkannt wurde.',
	'LMDI_GLOSS_DISALLOWED_EXTENSION'	=> 'Die Dateierweiterung <strong>%s</strong> ist nicht erlaubt.',
	'LMDI_GLOSS_EMPTY_FILEUPLOAD'		=> 'Die Datei ist leer.',
	'LMDI_GLOSS_EMPTY_REMOTE_DATA'	=> 'Der Dateiinhalt scheint beschädigt zu sein.',
	'LMDI_GLOSS_IMAGE_FILETYPE_MISMATCH'	=> 'Dateityp-Fehler: erwartete Dateiendung: %1$s gefundene Dateiendung: %2$s.',
	'LMDI_GLOSS_INVALID_FILENAME'		=> '%s ist ein ungültiger Dateiname.',
	'LMDI_GLOSS_NOT_UPLOADED'		=> 'Keine Datei hochgeladen',
	'LMDI_GLOSS_PARTIAL_UPLOAD'		=> 'Datei wurde nicht vollständig hochgeladen.',
	'LMDI_GLOSS_PHP_SIZE_NA'			=> 'Die Datei ist zu groß.<br />Die Maximalgröße in der php.ini konnte nicht bestimmt werden..',
	'LMDI_GLOSS_PHP_SIZE_OVERRUN'		=> 'Die Datei ist zu groß. Die maximale Größe ist %d Mb.<br />Bitte beachten: die Größe ist in der php.ini eingestellt und kann nicht überschritten werden.',
	'LMDI_GLOSS_REMOTE_UPLOAD_TIMEOUT'	=> 'Die angegebene Datei konnte wegen einer Zeitüberschreitung nicht hochgeladen werden.',
	'LMDI_GLOSS_UNABLE_GET_IMAGE_SIZE'	=> 'Es war nicht möglich die Dateigröße zu ermitteln.',
	'LMDI_GLOSS_WRONG_FILESIZE'		=> 'Die Datei muss kleiner als %1d kB sein.',
	'LMDI_GLOSS_WRONG_SIZE'			=> 'Das Bild ist %5$s breit und %6$s hoch.<br>Glossarbilder müssen mindestens %1$s breit und %2$s hoch, aber dürfen maximal %3$s breit und %4$s hoch sein.',
	'LMDI_CLICK_BACK'				=> 'Klicke <a href="javascript:history.go(-1);"><b>hier</b></a> um zur Bearbeitungsseite zurückzukehren.',
));

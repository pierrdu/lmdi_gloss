<?php
/**
* (c) LMDI Pierre Duhem 2015-2016
* Original author Renate Regitz http://www.kaninchenwissen.de/
* Rewritten by Pierre Duhem for the Glossary extension
* This code extracts from table glossary the contents of term id.
* The returned contents is displayed in the popup window.
* This code gets called from module jquery.lexicon.js.
**/

define('IN_PHPBB', true);

// Inclusion du fichier common.php (dans la racine)
// Inclusion of common.php (in forum root)
$phpbb_root_path = '../../../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path. 'common.' . $phpEx);

// Session management
$user->session_begin();
$user->setup();

if (!defined('GLOSSARY_TABLE'))
{
	global $table_prefix;
	define('GLOSSARY_TABLE', $table_prefix . 'glossary');
}

$id = $request->variable ('id', 0);
if ($id)
{
	// Extract glossary entry
	$sql = "SELECT * FROM " . GLOSSARY_TABLE .
		" WHERE term_id = '$id'";
	$result = $db->sql_query_limit($sql, 1);
	$row = $db->sql_fetchrow($result);
	$entry = '<h3><a title="'. $user->lang['CLOSE_WINDOW']. '" id="lexiconClose" href="#">x</a></h3>
		<h3>'.$row['term'].'</h3>'.'
		<p><b>'.$row['description'].'</b></p>
		<p><img class="popgloss" src="ext/lmdi/gloss/glossaire/'.$row['picture'].'"></p>';
	$db->sql_freeresult($result);
}

header('Content-type: text/html; charset=UTF-8');
echo $entry;

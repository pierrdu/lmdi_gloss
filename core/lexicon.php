<?php
/**
* (c) LMDI Pierre Duhem 2015-2020
* Original author Renate Regitz http://www.kaninchenwissen.de/
* Rewritten by Pierre Duhem for the Glossary extension
* This code extracts the contents of term id from glossary table.
* The returned contents is displayed in the popup window.
* This code is called from module jquery.lexicon.js.
**/

namespace lmdi\gloss\core;

class lexicon
{
	protected $user;
	protected $language;
	protected $db;
	protected $request;
	protected $phpbb_root_path;
	protected $glossary_table;

	public function __construct(
		\phpbb\user $user,
		\phpbb\language\language $language,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		$phpbb_root_path,
		$glossary_table)
	{
		$this->user			= $user;
		$this->language		= $language;
		$this->db				= $db;
		$this->request			= $request;
		$this->phpbb_root_path 	= $phpbb_root_path;
		$this->glossary_table	= $glossary_table;
	}

	public function main()
	{
		$this->language->add_lang('edit_gloss', 'lmdi/gloss');
		// id = keyword id
		$id = $this->request->variable('id', 0);
		if ($id)
		{
			$sql = "SELECT * FROM " . $this->glossary_table . " WHERE term_id = '$id'";
			$result = $this->db->sql_query_limit($sql, 1);
			$row = $this->db->sql_fetchrow($result);
			$entry = '<div id=\'glosspop\'><p id=\'lexClose\'><a title="'. $this->language->lang('CLOSE_WINDOW') . '" id="lexiconClose" href="#">x</a></p>';
			$entry .= '<h3>' . $row['term'] . '</h3>';
			if (strlen ($row['cat']))
			{
				$entry .= '<p><b>(' . $row['cat'] . ')<br>' . $row['description'] . '</b></p>';
			}
			else
			{
				$entry .= '<p><b>' . $row['description'] . '</b></p>';
			}
			$picture = $row['picture'];
			if ($picture != "nopict.jpg" && $picture != '')
			{
				$entry .= '<p><img class="popgloss" src="' . $this->phpbb_root_path . 'images/lmdi/gloss/' . $row['picture'] . '" alt="' . $row['term']. '" /></p>';
			}
			$elinks = $row['elinks'];
			$label = $row['label'];
			$str_elink = $this->language->lang('GLOSS_ELINK');
			if ($elinks != "")
			{
				if ($label == "")
				{
					$entry .= '<p id="elinks">' . $str_elink . '<a href="'.$elinks.'">'.$elinks.'</a></p>';
				}
				else
				{
					$entry .= '<p id="elinks">' . $str_elink . '<a href="'.$elinks.'">'.$label.'</a></p>';
				}
			}
			$entry .= "</div>";
			$this->db->sql_freeresult($result);
		}
		else
		{
			$entry = "Error";
		}
		$json_response = new \phpbb\json_response;
		$json_response->send($entry, true);
	}
}

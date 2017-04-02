<?php
/**
* (c) LMDI Pierre Duhem 2015-2017
* Original author Renate Regitz http://www.kaninchenwissen.de/
* Rewritten by Pierre Duhem for the Glossary extension
* This code extracts the contents of term id from glossary table.
* The returned contents is displayed in the popup window.
* This code is called from module jquery.lexicon.js.
**/

namespace lmdi\gloss\core;

class lexicon
{
	/** @var \phpbb\user */
	protected $user;
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	/** @var \phpbb\request\request */
	protected $request;
	protected $glossary_table;

	public function __construct(
		\phpbb\user $user,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		$glossary_table)
	{
		$this->user			= $user;
		$this->db				= $db;
		$this->request			= $request;
		$this->glossary_table	= $glossary_table;
	}

	public function main()
	{
		$this->user->add_lang_ext('lmdi/gloss', 'edit_gloss');
		// id = keyword id
		$id = $this->request->variable('id', 0);
		if ($id)
		{
			$sql = "SELECT * FROM " . $this->glossary_table . " WHERE term_id = '$id'";
			$result = $this->db->sql_query_limit($sql, 1);
			$row = $this->db->sql_fetchrow($result);
			$entry = '<h3><a title="'. $this->user->lang['CLOSE_WINDOW']. '" id="lexiconClose" href="#">x</a></h3><h3>'.$row['term'].'</h3>'.
				'<p><b>('. $row['cat']. ')<br>' . $row['description'].'</b></p>';
			$picture = $row['picture'];
			if ($picture != "nopict.jpg")
			{
				$entry .= '<p><img class="popgloss" src="ext/lmdi/gloss/glossaire/'.$row['picture'].'" alt="' . $row['term']. '" /></p>';
			}
			$elinks = $row['elinks'];
			$label = $row['label'];
			if ($elinks != "")
			{
				if ($label == "")
				{
					$entry .= '<p><a href="'.$elinks.'">'.$elinks.'</a></p>';
				}
				else
				{
					$entry .= '<p><a href="'.$elinks.'">'.$label.'</a></p>';
				}
			}
			$this->db->sql_freeresult($result);
		}
		$json_response = new \phpbb\json_response;
		$json_response->send($entry, true);
	}
}

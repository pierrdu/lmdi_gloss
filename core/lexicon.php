<?php
/**
* (c) LMDI Pierre Duhem 2015-2025
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
	protected $gloss_helper;

	public function __construct(
		\phpbb\user $user,
		\phpbb\language\language $language,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		$phpbb_root_path,
		$glossary_table)
	{
		global $phpbb_container;
		
		$this->user			= $user;
		$this->language		= $language;
		$this->db				= $db;
		$this->request			= $request;
		$this->phpbb_root_path 	= $phpbb_root_path;
		$this->glossary_table	= $glossary_table;
		$this->gloss_helper = $phpbb_container->get('lmdi.gloss.core.helper');

	}

	public function main()
	{
		
		$this->language->add_lang('edit_gloss', 'lmdi/gloss');
		$id = (int) $this->request->variable('id', 0);
		if ($id > 0)
		{
			$sql = "SELECT * FROM " . $this->glossary_table . " WHERE term_id = '$id'";
			$result = $this->db->sql_query_limit($sql, 1);
			$row = $this->db->sql_fetchrow($result);
			if ($row)
			{
				$term = htmlspecialchars($row['term'], ENT_QUOTES, 'UTF-8');
				$cat = trim($row['cat']);
				$desc = nl2br(htmlspecialchars(trim($row['description']), ENT_QUOTES, 'UTF-8'));

				$entry = '<div id="glosspop">';

				// Card header with title, optional category badge and close button
				$entry .= '<div class="lex-header">';
				$entry .= '<a id="lexiconClose" href="#" title="' . $this->language->lang('CLOSE_WINDOW') . '" aria-label="' . $this->language->lang('CLOSE_WINDOW') . '">';
				$entry .= '<i class="icon fa-times fa-fw" aria-hidden="true"></i>';
				$entry .= '</a>';
				$entry .= '<div class="lex-title-wrap">';
				$entry .= '<h3>' . $term . '</h3>';
				if ($cat !== '')
				{
					$entry .= '<p id=\'lextext\'>(' . htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') . ')</p>';
				}
				$entry .= '</div>';
				$entry .= '</div>';

				// Card body with description and optional picture
				$entry .= '<div class="lex-body">';
				$entry .= '<p id="lextext">' . $desc . '</p>';

				$picture = trim($row['picture']);
				if ($picture !== '' && $picture !== 'nopict.jpg')
				{
					$img_src = $this->phpbb_root_path . 'images/lmdi/gloss/' . htmlspecialchars($picture, ENT_QUOTES, 'UTF-8');
					$entry .= '<div class="lex-media"><img class="popgloss" src="' . $img_src . '" alt="' . $term . '" /></div>';
				}
				$entry .= '</div>';

				// Optional footer with external and internal links
				$elinks = trim($row['elinks']);
				$label = trim($row['label']);
				$ilinks = trim($row['ilinks']);

				if ($elinks !== '' || $ilinks !== '')
				{
					$entry .= '<div class="lex-footer">';
					if ($elinks !== '')
					{
						$safe_url = htmlspecialchars($elinks, ENT_QUOTES, 'UTF-8');
						$link_text = ($label !== '') ? htmlspecialchars($label, ENT_QUOTES, 'UTF-8') : $safe_url;
						$entry .= '<p class="lex-links"><i class="icon fa-external-link fa-fw" aria-hidden="true"></i> ' . $this->language->lang('GLOSS_ELINK') . ' <a href="' . $safe_url . '" target="_blank" rel="noopener">' . $link_text . '</a></p>';
					}
					if ($ilinks !== '')
					{
						$entry .= '<p class="lex-links"><i class="icon fa-book fa-fw" aria-hidden="true"></i> ' . $this->language->lang('GLOSS_ILINK') . ' ' . $this->gloss_helper->calcul_ilinks($this->phpbb_root_path . 'app.php/gloss', $ilinks) . '</p>';
					}
					$entry .= '</div>';
				}

				$entry .= '</div>';
			}
			else
			{
				$entry = '<div id="glosspop"><div class="lex-body"><p id="lextext">Term not found.</p></div></div>';
			}
		}
		else
		{
			$entry = '<div id="glosspop"><div class="lex-body"><p id="lextext">Error</p></div></div>';
		}
		$json_response = new \phpbb\json_response;
		$json_response->send($entry, true);
	}	// main
}

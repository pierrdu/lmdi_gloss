<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* @copyright (c) 2015-2019 LMDI - Pierre Duhem
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace lmdi\gloss\core;

class glossadmin
{
	protected $template;
	protected $db;
	protected $helper;
	protected $auth;
	protected $ext_manager;
	protected $path_helper;
	protected $gloss_helper;
	protected $phpEx;
	protected $phpbb_root_path;
	protected $glossary_table;
	protected $ext_path;
	protected $ext_path_web;

	/**
	* Constructor
	*
	*/
	public function __construct(
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\controller\helper $helper,
		\phpbb\auth\auth $auth,
		\phpbb\extension\manager $ext_manager,
		\phpbb\path_helper $path_helper,
		\lmdi\gloss\core\helper $gloss_helper,
		$phpEx,
		$phpbb_root_path,
		$glossary_table)
	{
		$this->template 		= $template;
		$this->language		= $language;
		$this->db 			= $db;
		$this->helper 			= $helper;
		$this->auth			= $auth;
		$this->ext_manager	 	= $ext_manager;
		$this->path_helper	 	= $path_helper;
		$this->gloss_helper		= $gloss_helper;
		$this->phpEx 			= $phpEx;
		$this->phpbb_root_path 	= $phpbb_root_path;
		$this->glossary_table 	= $glossary_table;

		$this->ext_path = $this->ext_manager->get_extension_path('lmdi/gloss', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public $u_action;

	public function main()
	{
		static $abc_table = null;
		static $gloss_table = null;

		$this->sanity_check();
		$this->language->add_lang ('edit_gloss', 'lmdi/gloss');

		if (!$abc_table)
		{
			$abc_table = $this->gloss_helper->compute_abc_table();
		}
		foreach ($abc_table as $l)
		{
			$this->template->assign_block_vars('gabc', array('ABC' => $l));
		}
		if (!$gloss_table)
		{
			$gloss_table = $this->gloss_helper->compute_gloss_table ($abc_table);
		}

		$str_action = $this->language->lang('GLOSS_DISPLAY');
		$str_edit2  = $this->language->lang('GLOSS_ED_EDIT');
		foreach ($abc_table as $l)
		{
			$block = $gloss_table[$l];
			$cpt = 0;
			foreach ($block as $row)
			{
				if (!$cpt)
				{
					$anchor = "<span id='$l'></span>";
				}
				else
				{
					$anchor = "";
				}
				$ilinks = $row['ilinks'];
				if (strlen ($ilinks))
				{
					$ilinks = $this->gloss_helper->calcul_ilinks ($ilinks);
					$s_ilinks = 1;
				}
				else
				{
					$s_ilinks = "";
				}
				$elinks = $row['elinks'];
				$label  = $row['label'];
				if (strlen ($elinks))
				{
					if (!strlen ($label))
					{
						$label = $elinks;
					}
					$s_elinks = 1;
				}
				else
				{
					$s_elinks = 0;
				}
				$pict = $row['picture'];
				$term = $row['term'];
				$code = $row['term_id'];
				if ($pict == "nopict.jpg" || $pict == '')
				{
					$url= "";
					$str_url = "";
					$s_url = 0;
				}
				else
				{
					$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glosspict', 'code' => $code, 'term' =>$term, 'pict' => $pict));
					$str_url = $pict;
					$s_url = 1;
				}
				$act = "<a href=\"";
				$act .= $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit', 'code' => $code, 'action' => 'edit'));
				$act .= "\">$str_edit2</a>";
				$this->template->assign_block_vars('ged', array(
					'TERM'	=> $term,
					'ID'		=> $code,
					'DEF'	=> $row['description'],
					'CAT'	=> $row['cat'],
					'ANCHOR'	=> $anchor,
					'S_ILINKS' => $s_ilinks,
					'ILINKS'	=> $ilinks,
					'S_ELINKS' => $s_elinks,
					'ELINKS'	=> $elinks,
					'LABEL'	=> $label,
					'S_URL'	=> $s_url,
					'URL'	=> $url,
					'STRURL'	=> $str_url,
					'ACTION'	=> $act,
					));
				$cpt++;
			}	// Inner foreach
		}	// Outer foreach

		// Breadcrumbs
		$params = "mode=glossadmin";
		$str_glossadmin = append_sid($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', $params);
		$this->template->assign_block_vars('navlinks', array(
			'U_VIEW_FORUM'	=> $str_glosspict,
			'FORUM_NAME'	=> $this->language->lang('GLOSS_ADMINISTRATION'),
		));
		$params = "mode=glossnew";
		$str_url = append_sid($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', $params);
		$str_url = '<a href="' . $str_url . '">';
		$str_url = sprintf ($this->language->lang('GLOSS_ED_EXPL'), $str_url, '</a>');
		$this->template->assign_vars(array(
			'EDIT_EXPLAIN'	=> $str_url,
		));

		$titre = $this->language->lang('GLOSS_ADMINISTRATION');
		page_header($titre);
		$this->template->set_filenames (array(
			'body' => 'glossadmin.html',
		));
		page_footer();
	}	// main


	private function sanity_check()
	{
		// Check of the existence of the folder store/lmdi/gloss
		$folder = $this->phpbb_root_path . 'store/lmdi';
		if (!is_dir ($folder))
		{
			mkdir ($folder, 0777);
		}
		else
		{
			chmod ($folder, 0777);
		}
		$folder .= '/gloss';
		if (!is_dir ($folder))
		{
			mkdir ($folder, 0777);
		}
		else
		{
			chmod ($folder, 0777);
		}
	}	// sanity_check

}

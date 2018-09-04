<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* Glossary viewer — Afficheur du glossaire
* @copyright (c) 2015-2018 LMDI - Pierre Duhem
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace lmdi\gloss\core;

class glossaire
{
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\user */
	protected $user;
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	/** @var \phpbb\controller\helper */
	protected $helper;
	/** @var \phpbb\auth\auth */
	protected $auth;
	/** @var \phpbb\extension\manager "Extension Manager" */
	protected $ext_manager;
	/** @var \phpbb\path_helper */
	protected $path_helper;
	/** @var \lmdi\gloss\core\helper */
	protected $gloss_helper;
	// Strings
	protected $glossary_table;
	protected $ext_path;
	protected $ext_path_web;

	/**
	* Constructor
	*
	*/
	public function __construct(
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\controller\helper $helper,
		\phpbb\auth\auth $auth,
		\phpbb\extension\manager $ext_manager,
		\phpbb\path_helper $path_helper,
		\lmdi\gloss\core\helper $gloss_helper,
		$glossary_table)
	{
		$this->template 		= $template;
		$this->user			= $user;
		$this->db				= $db;
		$this->helper			= $helper;
		$this->auth			= $auth;
		$this->ext_manager		= $ext_manager;
		$this->path_helper		= $path_helper;
		$this->gloss_helper		= $gloss_helper;
		$this->glossary_table	= $glossary_table;

		$this->ext_path = $this->ext_manager->get_extension_path('lmdi/gloss', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public $u_action;

	public function main()
	{
		static $abc_table = null;
		static $gloss_table = null;
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
		$str_action = $this->user->lang['GLOSS_DISPLAY'];
		$str_ilinks = $this->user->lang['GLOSS_ILINKS'];
		$str_elinks = $this->user->lang['GLOSS_ELINKS'];
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
					$brilinks = "<br>$str_ilinks";
				}
				else
				{
					$brilinks = "";
				}
				$elinks = $row['elinks'];
				$label  = $row['label'];
				if (strlen ($elinks))
				{
					if (!strlen ($label))
					{
						$label = $elinks;
					}
					$brelinks = "<br>$str_elinks";
				}
				else
				{
					$brelinks = "";
				}
				$pict = $row['picture'];
				$term = $row['term'];
				$code = $row['term_id'];
				if ($pict != "nopict.jpg")
				{
					$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glosspict', 'code' => $code, 'term' =>$term, 'pict' => $pict));
					$str_url = $str_action;
				}
				else
				{
					$url= "";
					$str_url = "";
				}
				$this->template->assign_block_vars('gaff', array(
					'TERM'	=> $term,
					'ID'		=> $code,
					'DEF'	=> $row['description'],
					'CAT'	=> $row['cat'],
					'URL'	=> $url,
					'STRURL'	=> $str_url,
					'ANCHOR'	=> $anchor,
					'ELINKS'	=> $elinks,
					'LABEL'	=> $label,
					'ILINKS'	=> $ilinks,
					'BRILINKS' => $brilinks,
					'BRELINKS' => $brelinks,
					));
				$cpt++;
			}	// Inner foreach
		}	// Outer foreach

		if ($this->auth->acl_get('u_lmdi_glossary') || $this->auth->acl_get('a_lmdi_glossary'))
		{
			$editor = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit'));
			$switch = 1;
		}
		else
		{
			$editor = "";
			$switch = 0;
		}

		// Bibliographie - Bibliographic part
		$biblio = $this->user->lang['GLOSS_BIBLIO'];
		if (strlen($biblio) < 50)
		{
			$biblio = "";
		}

		$this->template->assign_vars (array (
			'TITLE'		=> $this->user->lang['GLOSS_VIEW'],
			'S_EDIT'		=> $switch,
			'EDITOR'		=> $editor,
			'ACTION'		=> $this->user->lang['GLOSS_EDITION'],
			'ILLUST'		=> $this->user->lang['ILLUSTRATION'],
			'BIBLIO'		=> $biblio,
			'BACKTOP'		=> $this->user->lang['LMDI_BACK_TOP'],
			));
		return $this->helper->render('glossaire.html', $this->user->lang['TGLOSSAIRE']);
	}	// main

}

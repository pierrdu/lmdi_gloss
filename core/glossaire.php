<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
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
		$sql = 'SELECT DISTINCT UPPER(LEFT(TRIM(term),1)) AS a
				FROM ' . $this->glossary_table . '
				ORDER BY a';
		$result = $this->db->sql_query($sql);

		$abc_links = '<br /><p class="glossa">';

		$str_action = $this->user->lang['GLOSS_DISPLAY'];
		$str_ilinks = $this->user->lang['GLOSS_ILINKS'];
		$str_elinks = $this->user->lang['GLOSS_ELINKS'];

		while ($row = $this->db->sql_fetchrow($result))
		{
			$l = $row['a'];
			$abc_links .= "&nbsp;<a class=\"cap\" href =\"#$l\">$l</a>&nbsp;" ;
			$l = $this->db->sql_escape ($l);
			$sql = "SELECT * 
					FROM " . $this->glossary_table . "
					WHERE LEFT($this->glossary_table.term, 1) = '$l' 
					ORDER BY term";
			$result2 = $this->db->sql_query ($sql);
			$cpt = 0;
			while ($arow = $this->db->sql_fetchrow($result2))
			{
				$code   = $arow['term_id'];
				$term   = $arow['term'];
				$desc   = $arow['description'];
				$cat    = $arow['cat'];
				$ilinks = $arow['ilinks'];
				$elinks = $arow['elinks'];
				$label  = $arow['label'];
				$pict   = $arow['picture'];
				if (!$cpt)
				{
					$anchor = "<span id='$l'></span>";
				}
				else
				{
					$anchor = "";
				}
				if (strlen ($ilinks))
				{
					$ilinks = $this->gloss_helper->calcul_ilinks ($ilinks);
					$ilinks = "<br>$str_ilinks $ilinks";
				}
				if (strlen ($elinks))
				{
					if (strlen ($label))
					{
						$elinks = "<br>$str_elinks <a class=\"ilinks\" href=\"$elinks\">$label</a>";
					}
					else
					{
						$elinks = "<br>$str_elinks <a class=\"ilinks\" href=\"$elinks\">$elinks</a>";
					}
				}
				if ($pict != "nopict.jpg")
				{
					$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glosspict', 'code' => $code, 'term' =>$term, 'pict' => $pict));
					$pict = '<a href="' . $url . '">' . $str_action . '</a>';
				}
				else
				{
					$pict= "";
				}
				$this->template->assign_block_vars('gaff', array(
					'ANCHOR'	=> $anchor,
					'TERM'	=> $term,
					'DEF'	=> $arow['description'],
					'PICT'	=> $pict,
					'CAT'	=> $cat,
					'ELINKS'	=> $elinks,
					'ILINKS'	=> $ilinks,
					));
				$cpt++;
			}
			$this->db->sql_freeresult ($result2);
		}
		$this->db->sql_freeresult ($result);

		if ($this->auth->acl_get('u_lmdi_glossary') || $this->auth->acl_get('a_lmdi_glossary'))
		{
			$str_admin = $this->user->lang['GLOSS_EDITION'];
			$abc_links .= '</p><p><b><a href="';
			$abc_links .= $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit'));
			$abc_links .= '">' . $str_admin . '</a></b>';
		}
		$abc_links .= "</p><br />";

		// Bibliographie - Bibliographic part
		$biblio = $this->user->lang['GLOSS_BIBLIO'];
		if (strlen($biblio) < 50)
		{
			$biblio = "";
		}

		$this->template->assign_vars (array (
			'TITLE'		=> $this->user->lang['GLOSS_VIEW'],
			'ABC'		=> $abc_links,
			'ILLUST'		=> $this->user->lang['ILLUSTRATION'],
			'BIBLIO'		=> $biblio,
			'BACKTOP'		=> $this->user->lang['LMDI_BACK_TOP'],
			));
		return $this->helper->render('glossaire.html', $this->user->lang['TGLOSSAIRE']);
	}
}

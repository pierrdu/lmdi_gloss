<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* @copyright (c) 2015-2016 LMDI - Pierre Duhem
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
		\phpbb\user $user,
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
		$this->user 			= $user;
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

	var $u_action;

	function main()
	{
		// SELECT DISTINCT UPPER(LEFT(TRIM(term),1)) AS a FROM phpbb3_glossary ORDER BY a
		$sql  = 'SELECT DISTINCT UPPER(LEFT(TRIM(term),1)) AS a
				FROM ' . $this->glossary_table . '
				ORDER BY a';
		$result = $this->db->sql_query($sql);

		$abc_links = '<span id="haut"></span><br /><p class="glossa">';

		$str_terme  = $this->user->lang['GLOSS_ED_TERM'];
		$str_defin  = $this->user->lang['GLOSS_ED_DEF'];
		$str_illus  = $this->user->lang['GLOSS_ED_PICT'];
		$str_action = $this->user->lang['GLOSS_DISPLAY'];
		$str_ilinks = $this->user->lang['GLOSS_ILINKS'];
		$str_elinks = $this->user->lang['GLOSS_ELINKS'];

		$corps  = '<table class="deg"><tr class="deg">';
		$corps .= '<th class="deg0">' . $str_terme . '</th>';
		$corps .= '<th class="deg0">' . $str_defin . '</th>';
		$corps .= '<th class="deg1">' . $str_illus . '</th></tr>';

		$cpt  = 0;
		$top = $this->ext_path_web . "/styles/top.gif";
		while ($row = $this->db->sql_fetchrow($result))
		{
			$l = $row['a'];
			$abc_links .= "&nbsp;<a class=\"cap\" href =\"#$l\">$l</a>&nbsp;" ;

			$sql  = "SELECT * 
					FROM " . $this->glossary_table . "
					WHERE LEFT($this->glossary_table.term, 1) = '$l' 
					ORDER BY term";
			$result2 = $this->db->sql_query ($sql);

			$cpt++;
			$corps .= "<tr class=\"deg\"><td class=\"glossi\" colspan=\"2\" id=$l>&nbsp;$l</td>";
			$corps .= "<td class=\"haut\"><a href=\"#haut\"><img src=\"$top\"></a></td></tr>";
			while ($arow = $this->db->sql_fetchrow($result2))
			{
				$code   = $arow['term_id'];
				$vari   = $arow['variants'];
				$term   = $arow['term'];
				$desc   = $arow['description'];
				$cat    = $arow['cat'];
				$ilinks = $arow['ilinks'];
				$elinks = $arow['elinks'];
				$label  = $arow['label'];
				$pict   = $arow['picture'];
				$corps .= "\n<tr class='deg'>";
				$corps .= "<td class='deg0' id=\"$code\"><b>$term</b>";
				if (strlen ($cat))
				{
					$corps .= "<br>$cat";
				}
				$corps .= "</td>";
				$corps .= "<td class='deg0'>$desc";
				if (strlen ($ilinks))
				{
					$ilinks = $this->gloss_helper->calcul_ilinks ($ilinks);
					$corps .= "<br>$str_ilinks $ilinks";
				}
				if (strlen ($elinks))
				{
					if (strlen ($label))
					{
						$corps .= "<br>$str_elinks <a class=\"ilinks\" href=\"$elinks\">$label</a>";
					}
					else
					{
						$corps .= "<br>$str_elinks <a class=\"ilinks\" href=\"$elinks\">$elinks</a>";
					}
				}
				$corps .= "</td>";
				$corps .= "<td class='deg1'>";
				/*	Lien cliquable si l'image est différente de nopict.
					Link only if the picture is not nopict.jpg.
					*/
				if ($pict != "nopict.jpg")
				{
					$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glosspict', 'code' => $code, 'term' =>$term, 'pict' => $pict));
					$corps .= '<a href="' . $url . '">' . $str_action . '</a></td>';
				}
				else
				{
					$corps .= "&nbsp;</td>";
				}
				$corps .= "</tr>";
			}	// Fin du while sur le contenu - End of while on contents
			$this->db->sql_freeresult ($result2);
		}	// Fin du while sur les initiales - End of while on initial caps
		$this->db->sql_freeresult ($result);
		$corps .= "</table>";
		// End of the ABC links with a link to the Edition page for administrators/editors
		// Fermeture de la ligne de liens avec un lien vers la page d'édition
		$str_admin = $this->user->lang['GLOSS_EDITION'];
		if ($this->auth->acl_get('u_lmdi_glossary') || $this->auth->acl_get('a_lmdi_glossary'))
		{
			$abc_links .= '</p><p><b><a href="';
			$abc_links .= append_sid("{$this->phpbb_root_path}app.php/gloss?mode=glossedit");
			$abc_links .= '">' . $str_admin . '</a></b>';
		}
		$abc_links .= "</p><br />";

		// Bibliographie - Bibliography part
		$biblio = $this->user->lang['GLOSS_BIBLIO'];

		// Information sur l'existence d'une illustration
		// Comment string about the presence of a picture
		$illustration = $this->user->lang['ILLUSTRATION'];

		$titre = $this->user->lang['TGLOSSAIRE'];
		page_header($titre);
		$this->template->set_filenames (array(
			'body' => 'glossaire.html',
		));
		$this->template->assign_vars (array (
			'TITLE'		=> $titre,
			'ABC'			=> $abc_links,
			'ILLUST'		=> $illustration,
			'CORPS'		=> $corps,
			'BIBLIO'		=> $biblio,
		));

		page_footer();
	}
}

<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* @copyright (c) 2015-2020 LMDI - Pierre Duhem
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace lmdi\gloss\core;

class glossedit
{
	protected $template;
	protected $language;
	protected $db;
	protected $ext_manager;
	protected $path_helper;
	protected $helper;
	protected $config;
	protected $cache;
	protected $files_factory;
	protected $gloss_helper;
	protected $phpEx;
	protected $phpbb_root_path;
	protected $glossary_table;
	protected $ext_path;
	protected $ext_path_web;

	public function __construct(
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\extension\manager $ext_manager,
		\phpbb\path_helper $path_helper,
		\phpbb\controller\helper $helper,
		\phpbb\config\config $config,
		\phpbb\request\request $request,
		\phpbb\cache\service $cache,
		\lmdi\gloss\core\helper $gloss_helper,
		$phpEx,
		$phpbb_root_path,
		$glossary_table,
		\phpbb\files\factory $files_factory = null
		)
	{
		$this->template 		= $template;
		$this->language		= $language;
		$this->db 			= $db;
		$this->ext_manager		= $ext_manager;
		$this->path_helper		= $path_helper;
		$this->helper			= $helper;
		$this->config			= $config;
		$this->request			= $request;
		$this->cache			= $cache;
		$this->gloss_helper		= $gloss_helper;
		$this->phpEx 			= $phpEx;
		$this->phpbb_root_path 	= $phpbb_root_path;
		$this->glossary_table 	= $glossary_table;

		if ($files_factory)
		{
			$this->files_factory = $files_factory;
		}

		$this->ext_path = $this->ext_manager->get_extension_path('lmdi/gloss', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public $u_action;

	public function main()
	{
		$action = 'edit';
		$num		= $this->request->variable('code', 0);
		$delete	= $this->request->variable('delete', "rien");
		$save	= $this->request->variable('save', "rien");
		if ($delete != 'rien')
		{
			$action = 'delete';
		}
		if ($save != 'rien')
		{
			$action = 'save';
		}

		switch ($action)
		{
			default :		// Edition
				// Breadcrumbs
				$this->template->assign_block_vars('navlinks', array(
					'U_VIEW_FORUM'	=> $str_glossedit,
					'FORUM_NAME'	=> $this->language->lang('GLOSS_EDIT'),
				));

				$sql  = "SELECT * FROM " . $this->glossary_table . " WHERE term_id = $num";
				$result = $this->db->sql_query($sql);
				$row = $this->db->sql_fetchrow($result);
				$code   = $row['term_id'];
				$vari   = $row['variants'];
				$term   = $row['term'];
				$desc   = $row['description'];
				$cat    = $row['cat'];
				$ilinks = $row['ilinks'];
				$elinks = $row['elinks'];
				$label  = $row['label'];
				$pict   = $row['picture'];
				$lang   = $row['lang'];
				$this->db->sql_freeresult($result);
				$action = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit'));
				$this->template->assign_vars(array(
					'TITLE'		=> $this->language->lang('GLOSS_EDIT'),
					'ACTION'		=> $action,
					'CODE'		=> $code,
					'VARI'		=> $vari,
					'TERM'		=> $term,
					'DESC'		=> $desc,
					'CAT'		=> $cat,
					'ILINKS'		=> $ilinks,
					'ELINKS'		=> $elinks,
					'LABEL'		=> $label,
					'LANG'		=> $lang,
					'PICT'		=> $pict,
					'S_EDIT'		=> 1,		// 0 = creation, 1 = edition
					'S_PICT'		=> ($pict == "nopict.jpg" || $pict == '') ? 1 : 0,
					));

				$titre = $this->language->lang('GLOSS_EDIT');
				page_header($titre);
				$this->template->set_filenames (array(
					'body' => 'glossform.html',
				));
				page_footer();
			break;
		case 'save' :
			$term = $this->db->sql_escape(trim($this->request->variable('term',"",true)));
			$term_id = $this->db->sql_escape(trim($this->request->variable('term_id', 0)));
			$variants = $this->db->sql_escape(trim($this->request->variable('vari',"",true)));
			$descript = $this->db->sql_escape(trim($this->request->variable('desc',"",true)));
			if (mb_strlen($descript) > 511)
			{
				$descript = mb_substr($descript, 0, 511);
			}
			$cat = $this->db->sql_escape(trim($this->request->variable('cat',"",true)));
			$ilinks = $this->db->sql_escape(trim($this->request->variable('ilinks',"",true)));
			$elinks = $this->db->sql_escape(trim($this->request->variable('elinks',"",true)));
			$label = $this->db->sql_escape(trim($this->request->variable('label',"",true)));
			$lang = $this->db->sql_escape($this->request->variable('lang',"fr",true));
			$coche = $this->request->variable('upload', "", true);
			$picture = '';
			switch ($coche)
			{
				case "existe":
					$picture = $this->request->variable('pict', "", true);
				break;
				case "noup":
				break;
				case "reuse":
					$picture = $this->request->variable('reuse', "", true);
				break;
				case "nouv":
					$errors = array();
					$picture = $this->gloss_helper->upload_32x($errors);
					if (!$picture)
					{
						$nb = count($errors);
						$message = "";
						for ($i = 0; $i < $nb; $i++)
						{
							$message .= $errors[$i];
							$message .= "<br>";
						}
						$message .= $this->language->lang('LMDI_CLICK_BACK');
						trigger_error($message, E_USER_WARNING);
					}
					else
					{
						$picture = $this->db->sql_escape($picture);
					}
				break;
			}
			if ($term_id == 0)
			{
				$sql  = "INSERT INTO " . $this->glossary_table . "
					(variants, term, description, cat, ilinks, elinks, label, picture, lang) 
					VALUES (\"$variants\", \"$term\", \"$descript\", \"$cat\", \"$ilinks\", 
					'$elinks', \"$label\", \"$picture\", \"$lang\")";
				$this->db->sql_query($sql);
				$term_id = $this->db->sql_nextid();
			}
			else
			{
				$sql = "UPDATE " . $this->glossary_table . " SET 
					term_id = $term_id, 
					variants = \"$variants\", 
					term = \"$term\", 
					description = \"$descript\", 
					cat = \"$cat\", 
					ilinks = \"$ilinks\", 
					elinks = \"$elinks\", 
					label = \"$label\", 
					picture = \"$picture\", 
					lang = \"$lang\" 
					WHERE term_id = \"$term_id\"";
				$this->db->sql_query_limit($sql, 1);
			}

			// Purge the cache
			$this->cache->destroy('_glossterms');
			$this->cache->destroy('_gloss_table');
			$this->cache->destroy('_gloss_abc_table');

			// Information message et redirection
			$params = "mode=glossadmin&amp;code=$term_id";
			$url = append_sid($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', $params);
			$url .= "#$term_id"; // Anchor target = term_id
			$url = "<a href=\"$url\">";
			$message = sprintf ($this->language->lang('GLOSS_ED_SAVE'), $term, $url, '</a>');
			trigger_error($message);
			break;
		case 'delete' :
			$term_id = $this->request->variable('term_id', 0);
			$sql  = "DELETE FROM " . $this->glossary_table . " WHERE term_id = $term_id";
			$this->db->sql_query_limit($sql, 1);
			// Purge the caches
			$this->cache->destroy('_glossterms');
			$this->cache->destroy('_gloss_table');
			$this->cache->destroy('_gloss_abc_table');
			// Redirection
			$term = $this->request->variable('term', "", true);
			$cap = substr($term, 0, 1);
			$params = "mode=glossadmin";
			$url = append_sid($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', $params);
			$url .= "#$cap";		// Anchor target = initial cap
			$url = "<a href=\"$url\">";
			$message = sprintf ($this->language->lang('GLOSS_ED_DELETE'), $term, $url, '</a>');
			trigger_error($message);
			break;
		}	// switch
	}	// main
}

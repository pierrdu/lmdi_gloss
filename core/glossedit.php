<?php
// glossedit.php
// (c) 2015-2018 - LMDI Pierre Duhem
// Page d'édition du glossaire pour les administrateurs
// Glossary edition page for administrators

namespace lmdi\gloss\core;

class glossedit
{
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\user */
	protected $user;
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	/** @var \phpbb\extension\manager "Extension Manager" */
	protected $ext_manager;
	/** @var \phpbb\path_helper */
	protected $path_helper;
	/** @var \phpbb\controller\helper */
	protected $helper;
	/** @var \phpbb\config\config */
	protected $config;
	/** @var \phpbb\cache\service */
	protected $cache;
	/** @var \phpbb\files\factory */
	protected $files_factory;
	/** @var \lmdi\gloss\core\helper */
	protected $gloss_helper;
	// Strings
	protected $phpEx;
	protected $phpbb_root_path;
	protected $glossary_table;
	protected $ext_path;
	protected $ext_path_web;

	public function __construct(
		\phpbb\template\template $template,
		\phpbb\user $user,
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
		$this->template		= $template;
		$this->user			= $user;
		$this->db				= $db;
		$this->ext_manager		= $ext_manager;
		$this->path_helper		= $path_helper;
		$this->helper			= $helper;
		$this->config			= $config;
		$this->request			= $request;
		$this->cache			= $cache;
		$this->gloss_helper		= $gloss_helper;
		$this->phpEx			= $phpEx;
		$this->phpbb_root_path	= $phpbb_root_path;
		$this->glossary_table	= $glossary_table;

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
		$abc_links = "";
		$illustration = "";
		$corps = "";
		$biblio = "";
		$table = $this->glossary_table;
		$str_nopict = "nopict.jpg";

		$num		= $this->request->variable('code', 0);
		$action	= $this->request->variable('action', "rien");
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

		$str_colon = $this->user->lang['COLON'];
		$str_action = $this->user->lang['GLOSS_VIEW'];

		switch ($action)
		{
			case "edit":
				if ($num < 0)	// Item creation - Création d'une fiche
				{
					$code = "";
					$vari = "";
					$term = "";
					$desc = "";
					$pict = $str_nopict;
					$cat = "";
					$ilinks = "";
					$elinks = "";
					$label = "";
					$lang = $this->gloss_helper->get_def_language($table, 'lang');
					$title = $this->user->lang['GLOSS_CREAT'];
					$sw = 0;
				}
				else			// Item edition - Édition d'une fiche
				{
					$sql  = "SELECT * FROM $table WHERE term_id = $num ";
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
					$title = $this->user->lang['GLOSS_EDIT'];
					$sw = 1;
				}
				
				$action = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit'));
				$this->template->assign_vars(array(
					'TITLE'		=> $title,
					'R_ACTION'	=> $action,
					'ABC'		=> $abc_links,
					'S_EDIT'		=> $sw,		// 0 =creation, 1 = edition
					'S_PICT'		=> $pict == $str_nopict ? 1 : 0,
					'VARIANTS'	=> $this->user->lang['GLOSS_VARIANTS'],
					'VAREX'		=> $this->user->lang['GLOSS_VARIANTS_EX'],
					'TERME'		=> $this->user->lang['GLOSS_TERM'],
					'TEREX'		=> $this->user->lang['GLOSS_TERM_EX'],
					'DESCR'		=> $this->user->lang['GLOSS_DESC'],
					'CATE'		=> $this->user->lang['GLOSS_ED_CAT'],
					'CATEX'		=> $this->user->lang['GLOSS_ED_CATEX'],
					'ILINKE'		=> $this->user->lang['GLOSS_ED_ILINKS'],
					'ILEX'		=> $this->user->lang['GLOSS_ED_ILEXP'],
					'ELINKE'		=> $this->user->lang['GLOSS_ED_ELINKS'],
					'ELEX'		=> $this->user->lang['GLOSS_ED_ELEXP'],
					'LABELE'		=> $this->user->lang['GLOSS_ED_LABEL'],
					'LABEX'		=> $this->user->lang['GLOSS_ED_LABEX'],
					'LANGE'		=> $this->user->lang['GLOSS_LANG'],
					'PICTE'		=> $this->user->lang['GLOSS_ED_PICT'],
					'PICTEX'		=> $this->user->lang['GLOSS_ED_PIEXPL'],
					'UPLOAD'		=> $this->user->lang['GLOSS_ED_UPLOAD'],
					'NOUPLD'		=> $this->user->lang['GLOSS_ED_NOUP'],
					'REUSE'		=> $this->user->lang['GLOSS_ED_REUSE'],
					'EXISTE'		=> $this->user->lang['GLOSS_ED_EXISTE'],
					'REGIS'		=> $this->user->lang['GLOSS_REGIS'],
					'SUPPR'		=> $this->user->lang['GLOSS_SUPPR'],
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
					));
				return $this->helper->render('glossform.html', $title);
				break;
			case "save":
				$term_id = $this->db->sql_escape(trim($this->request->variable('term_id', 0)));
				$term = $this->db->sql_escape(trim($this->request->variable('term',"",true)));
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
				$picture = $str_nopict;
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
						if (version_compare($this->config['version'], '3.2.*', '>='))
						{
							$picture = $this->upload_32x($errors);
						}
						else
						{
							$picture = $this->upload_31x($errors);
						}
						if (!$picture)
						{
							$nb = count($errors);
							$message = "";
							for ($i = 0; $i < $nb; $i++)
							{
								$message .= $errors[$i];
								$message .= "<br>";
							}
							$message .= $this->user->lang['LMDI_CLICK_BACK'];
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
					$sql_ary = array (
						'variants' => $variants,
						'term' => $term,
						'description' => $descript,
						'cat' => $cat,
						'ilinks' => $ilinks,
						'elinks' => $elinks,
						'label' => $label,
						'picture' => $picture,
						'lang' => $lang,
						);
					$sql  = "INSERT INTO $table " . $this->db->sql_build_array ('INSERT', $sql_ary);
					var_dump ($sql);
					$this->db->sql_query($sql);
					$term_id = $this->db->sql_nextid();
				}
				else
				{
					$sql_ary = array (
						'term_id' => $term_id,
						'variants' => $variants,
						'term' => $term,
						'description' => $descript,
						'cat' => $cat,
						'ilinks' => $ilinks,
						'elinks' => $elinks,
						'label' => $label,
						'picture' => $picture,
						'lang' => $lang,
						);
					$sql = "UPDATE $table SET " . $this->db->sql_build_array ('UPDATE', $sql_ary) . "
						WHERE term_id = $term_id";
					var_dump ($sql);
					$this->db->sql_query_limit($sql, 1);
				}
				// Purge the cache
				$this->cache->destroy('_glossterms');
				// Redirection
				$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit', 'code' => $term_id));
				$url .= "#$term_id"; // Anchor target = term_id
				redirect($url);
				break;
			case "delete":
				$term_id = $this->db->sql_escape($this->request->variable('term_id', 0));
				$sql  = "DELETE FROM $table WHERE term_id = $term_id";
				$this->db->sql_query_limit($sql, 1);
				// Purge the cache
				$this->cache->destroy('_glossterms');
				// Redirection
				$cap = substr($this->request->variable('term', "", true), 0, 1);
				$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit'));
				$url .= "#$cap";		// Anchor target = initial cap
				redirect($url);
				break;
			case "rien":
				$str_action = $this->user->lang['GLOSS_EDITION'];
				$str_ilinks = $this->user->lang['GLOSS_ILINKS'];
				$str_elinks = $this->user->lang['GLOSS_ELINKS'];
				$str_edit2 = $this->user->lang['GLOSS_ED_EDIT'];

				$abc_links = "";

				$sql  = "SELECT DISTINCT UPPER(LEFT(TRIM(term),1)) AS a FROM $table ORDER BY a" ;
				$result = $this->db->sql_query($sql);

				while ($row = $this->db->sql_fetchrow($result))
				{
					$l = $row['a'];
					$abc_links .= "&nbsp;<a class=\"cap\" href =\"#$l\">$l</a>&nbsp;" ;

					// For each letter
					$sql = "SELECT * FROM $table WHERE LEFT($table.term, 1) = '$l' ORDER BY term";
					$result2 = $this->db->sql_query($sql);
					$cpt = 0;
					while ($arow = $this->db->sql_fetchrow($result2))
					{
						$code = $arow['term_id'];
						$pict = $arow['picture'];
						$term = $arow['term'];
						$label = $arow['label'];
						if ($pict != $str_nopict)
						{
							$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glosspict', 'code' => -1, 'pict' => $pict, 'terme' => $term));
							$pict = "<a href=\"$url\">$pict</a>";
						}
						$act = "<a href=\"";
						$act .= $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit', 'code' => $code, 'action' => 'edit'));
						$act .= "\">$str_edit2</a></td>";
						$cat   = $arow['cat'];
						$ilinks= $arow['ilinks'];
						$elinks= $arow['elinks'];
						if (strlen($ilinks))
						{
							$ilinks = $this->gloss_helper->calcul_ilinks($ilinks);
							$ilinks = "<br>$str_ilinks $ilinks";
						}
						if (strlen($elinks))
						{
							if (strlen($label))
							{
								$elinks = "<br>$str_elinks <a class=\"ilinks\" href=\"$elinks\">$label</a>";
							}
							else
							{
								$elinks = "<br>$str_elinks <a class=\"ilinks\" href=\"$elinks\">$elinks</a>";
							}
						}
						if (!$cpt)
						{
							$anchor = "<span id='$l'></span>";
						}
						else
						{
							$anchor = "";
						}
						$this->template->assign_block_vars('ged', array(
							'ANCHOR'	=> $anchor,
							'ID'		=> $code,
							'TERM'	=> $term,
							'DEF'	=> $arow['description'],
							'PICT'	=> $pict,
							'ACT'	=> $act,
							'CAT'	=> $cat,
							'ELINKS'	=> $elinks,
							'ILINKS'	=> $ilinks,
							));
						$cpt++;
					}
					$this->db->sql_freeresult($result2);
				}
				$this->db->sql_freeresult($result);

				$string = $this->user->lang['GLOSS_ED_EXPL'];
				$url  = "<a href=\"";
				$url .= $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit', 'code' => -1, 'action' => 'edit'));
				$url .= "\"";
				$ed_explain = sprintf($string, $url, "</a>");
				$this->template->assign_vars(array(
					'TITLE'		=> $str_action,
					'ED_EXPLAIN'	=> $ed_explain,
					'ABC'		=> $abc_links,
					'BACKTOP'		=> $this->user->lang['LMDI_BACK_TOP'],
					));

				return $this->helper->render ('glossedit.html', $this->user->lang['TGLOSSAIRE']);
				break;
			}

		$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit'));
				$params = "mode=glossedit";
		$this->template->assign_block_vars('navlinks', array(
			'U_VIEW_FORUM'	=> $url,
			'FORUM_NAME'	=> $this->user->lang['GLOSS_EDITION'],
			));

		$this->template->assign_vars(array(
			'TITLE'		=> $str_action,
			'ABC'		=> $abc_links,
			'ILLUST'		=> $illustration,
			'CORPS'		=> $corps,
			'BIBLIO'		=> $biblio,
			));

		return $this->helper->render('glossaire.html', $this->user->lang['TGLOSSAIRE']);
	}

	// Uploading function for phpBB 3.1.x
	private function upload_31x(&$errors)
	{
		include_once($this->phpbb_root_path . 'includes/functions_upload.' . $this->phpEx);
		// Set upload directory
		$upload_dir = $this->ext_path . 'glossaire';
		// Upload file
		$upload = new \fileupload();
		$upload->set_error_prefix('LMDI_GLOSS_');
		$upload->set_allowed_extensions(array('jpg', 'jpeg', 'gif', 'png'));
		$pixels = (int) $this->config['lmdi_glossary_pixels'];
		$upload->set_allowed_dimensions(false, false, $pixels, $pixels);
		$weight = $this->config['lmdi_glossary_weight'];
		$weight *= 1024;
		$upload->set_max_filesize($weight);
		$file = $upload->form_upload('upload_file');
		if (empty($file->filename))
		{
			$errors = array_merge($errors, array($this->user->lang('LMDI_GLOSS_NOFILE')));
			return (false);
		}
		$file->move_file($upload_dir, true);
		if ($file->filesize > $weight || $file->width > $pixels || $file->height > $pixels)
		{
			if (sizeof($file->error))
			{
				$errors = array_merge($errors, $file->error);
				$file->remove();
				return (false);
			}
		}
		$filename = $file->uploadname;
		@chmod($upload_dir . '/' . $filename, 0644);
		return ($filename);
	}

	// Uploading function for phpBB 3.2.x
	private function upload_32x(&$errors)
	{
		// Set upload directory
		$upload_dir = $this->ext_path . 'glossaire';

		/** @var \phpbb\files\upload $upload */
		$upload = $this->files_factory->get('upload');
		$upload->set_error_prefix('LMDI_GLOSS_');
		$upload->set_allowed_extensions(array('jpg', 'jpeg', 'gif', 'png'));
		$pixels = (int) $this->config['lmdi_glossary_pixels'];
		$pmini = 0;
		$upload->set_allowed_dimensions($pmini, $pmini, $pixels, $pixels);
		$weight = (int) $this->config['lmdi_glossary_weight'];
		$weight *= 1024;
		$upload->set_max_filesize($weight);
		// Uploading from a form, form name
		$file = $upload->handle_upload('files.types.form', 'upload_file');
		$file->move_file($upload_dir, true);
		$filesize = $file->get('filesize');
		if ($filesize > $weight)
		{
			if (sizeof($file->error))
			{
				$errors = array_merge($errors, $file->error);
				$file->remove();
				return (false);
			}
		}
		$filename = $file->get('realname');
		$filepath = $upload_dir . '/' . $filename;
		$fdata = getimagesize($filepath);
		$width = $fdata[0];
		$height = $fdata[1];
		if ($width > $pixels || $height > $pixels)
		{
			$errors[] = $this->user->lang('LMDI_GLOSS_WRONG_SIZE',
				$this->user->lang('PIXELS', (int) $pmini),
				$this->user->lang('PIXELS', (int) $pmini),
				$this->user->lang('PIXELS', (int) $pixels),
				$this->user->lang('PIXELS', (int) $pixels),
				$this->user->lang('PIXELS', (int) $width),
				$this->user->lang('PIXELS', (int) $height));
			$file->remove();
			return (false);
		}
		@chmod($filepath, 0644);
		return($filename);
	}
}

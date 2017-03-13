<?php
// glossedit.php
// (c) 2015-2017 - LMDI Pierre Duhem
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

	/**
	* Constructor
	*
	*/
	public function __construct(
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\extension\manager $ext_manager,
		\phpbb\path_helper $path_helper,
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
		$this->user 			= $user;
		$this->db 			= $db;
		$this->ext_manager	 	= $ext_manager;
		$this->path_helper	 	= $path_helper;
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

	public function get_def_language ($table, $colonne)
	{
		$sql = "SELECT DEFAULT($colonne) lg 
			FROM (SELECT 1) AS dummy
			LEFT JOIN $table ON True LIMIT 1";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow ($result);
		$default = $row['lg'];
		$this->db->sql_freeresult ($result);
		return ($default);
	}

	function main()
	{
		$abc_links = "";
		$illustration = "";
		$corps = "";
		$biblio = "";
		$table = $this->glossary_table;
		$str_nopict = "nopict.jpg";

		$num		= $this->request->variable ('code', 0);
		$action	= $this->request->variable ('action', "rien");
		$delete	= $this->request->variable ('delete', "rien");
		$save	= $this->request->variable ('save', "rien");
		if ($delete != 'rien')
		{
			$action = 'delete';
		}
		if ($save != 'rien')
		{
			$action = 'save';
		}

		$str_colon = $this->user->lang['COLON'];

		switch ($action)
		{
			case "edit" :
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
					$lang = $this->get_def_language ($table, 'lang');
					$str_action = $this->user->lang['GLOSS_CREAT'];
				}
				else			// Item edition - Édition d'une fiche
				{
					$sql  = "SELECT * ";
					$sql .= "FROM $table ";
					$sql .= "WHERE term_id = '$num' ";
					$result = $this->db->sql_query ($sql);
					$row = $this->db->sql_fetchrow ($result);
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
					$this->db->sql_freeresult ($result);
					$str_action = $this->user->lang['GLOSS_EDIT'];
				}
				$str_variants = $this->user->lang['GLOSS_VARIANTS'] . $str_colon;
				$str_terme = $this->user->lang['GLOSS_TERM'] . $str_colon;
				$str_varex = $this->user->lang['GLOSS_VARIANTS_EX'];
				$str_terex = $this->user->lang['GLOSS_TERM_EX'];
				$str_desc  = $this->user->lang['GLOSS_DESC'] . $str_colon;
				$str_pict  = $this->user->lang['GLOSS_ED_PICT'] . $str_colon;
				$str_pictex= $this->user->lang['GLOSS_ED_PIEXPL'];
				$str_upload= $this->user->lang['GLOSS_ED_UPLOAD'];
				$str_noup  = $this->user->lang['GLOSS_ED_NOUP'];
				$str_reuse = $this->user->lang['GLOSS_ED_REUSE'] . $str_colon;
				$str_existe= $this->user->lang['GLOSS_ED_EXISTE'] . $str_colon;
				$str_cat   = $this->user->lang['GLOSS_ED_CAT'] . $str_colon;
				$str_catex = $this->user->lang['GLOSS_ED_CATEX'];
				$str_ilinks= $this->user->lang['GLOSS_ED_ILINKS'] . $str_colon;
				$str_ilex  = $this->user->lang['GLOSS_ED_ILEXP'];
				$str_elinks= $this->user->lang['GLOSS_ED_ELINKS'] . $str_colon;
				$str_elex  = $this->user->lang['GLOSS_ED_ELEXP'];
				$str_label = $this->user->lang['GLOSS_ED_LABEL'] . $str_colon;
				$str_labex = $this->user->lang['GLOSS_ED_LABEX'];
				$str_lang  = $this->user->lang['GLOSS_LANG'] . $str_colon;
				$str_regis = $this->user->lang['GLOSS_REGIS'];
				$str_suppr = $this->user->lang['GLOSS_SUPPR'];
				$form  = "<form action=\"";
				$form .= append_sid ($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss?mode=glossedit');
				$form .= "\" method=\"post\" id=\"glossedit\" enctype=\"multipart/form-data\">";
				$form .= "\n<div class=\"panel\">\n<div class=\"inner\">\n<div class=\"content\">";
				$form .= "\n<input type=\"hidden\" name=\"term_id\" id=\"term_id\" value=\"$code\">";
				$form .= "<fieldset class=\"fields1\">";
				$form .= "\n<dl>";
				$form .= "<dt><label for=\"vari\">$str_variants</label><br />";
				$form .= "<span>$str_varex</span></dt>";
				$form .= "<dd><input type=\"text\" tabindex=\"1\" name=\"vari\" ";
				$form .= "id=\"term\" size=\"50\" value=\"$vari\" class=\"inputbox autowidth\" /></dd>";
				$form .= "</dl>";
				$form .= "\n<dl>";
				$form .= "<dt><label for=\"term\">$str_terme</label><br />";
				$form .= "<span>$str_terex</span></dt>";
				$form .= "<dd><input type=\"text\" tabindex=\"2\" name=\"term\" ";
				$form .= "id=\"term\" size=\"25\" value=\"$term\" class=\"inputbox autowidth\" /></dd>";
				$form .= "</dl>";
				$form .= "\n<dl>";
				$form .= "<dt><label for=\"desc\">$str_desc</label></dt>";
				$form .= "<dd><textarea tabindex=\"3\" rows=\"2\" cols=\"40\" name=\"desc\">$desc</textarea>";
				$form .= "</dd>";
				$form .= "</dl>";
				$form .= "\n<dl>";
				$form .= "<dt><label for=\"cat\">$str_cat</label><br />";
				$form .= "<span>$str_catex</span></dt>";
				$form .= "<dd><input type=\"text\" tabindex=\"4\" name=\"cat\" ";
				$form .= "id=\"lang\" size=\"25\" value=\"$cat\" class=\"inputbox autowidth\" /></dd>";
				$form .= "</dd>";
				$form .= "</dl>";
				$form .= "\n<dl>";
				$form .= "<dt><label for=\"ilinks\">$str_ilinks</label><br />";
				$form .= "<span>$str_ilex</span></dt>";
				$form .= "<dd><input type=\"text\" tabindex=\"5\" name=\"ilinks\" ";
				$form .= "id=\"lang\" size=\"60\" value=\"$ilinks\" class=\"inputbox autowidth\" /></dd>";
				$form .= "</dd>";
				$form .= "</dl>";
				$form .= "\n<dl>";
				$form .= "<dt><label for=\"elinks\">$str_elinks</label><br />";
				$form .= "<span>$str_elex</span></dt>";
				$form .= "<dd><input type=\"text\" tabindex=\"6\" name=\"elinks\" ";
				$form .= "id=\"lang\" size=\"60\" value=\"$elinks\" class=\"inputbox autowidth\" /></dd>";
				$form .= "</dd>";
				$form .= "</dl>";
				$form .= "\n<dl>";
				$form .= "<dt><label for=\"label\">$str_label</label><br />";
				$form .= "<span>$str_labex</span></dt>";
				$form .= "<dd><input type=\"text\" tabindex=\"7\" name=\"label\" ";
				$form .= "id=\"lang\" size=\"25\" value=\"$label\" class=\"inputbox autowidth\" /></dd>";
				$form .= "</dd>";
				$form .= "</dl>";
				$form .= "\n<dl>";
				$form .= "<dt><label for=\"lang\">$str_lang</label></dt>";
				$form .= "<dd><input type=\"text\" tabindex=\"8\" name=\"lang\" ";
				$form .= "id=\"lang\" size=\"10\" value=\"$lang\" class=\"inputbox autowidth\" /></dd>";
				$form .= "</dl>";
				// Radio button block - Pavé de boutons radio
				$form .= "\n<dl>";
				$form .= "<dt><label for=\"upload_file\">$str_pict</label><br />";
				$form .= "<span>$str_pictex</span></dt>";
				if ($num > 0)
				{
					if ($pict == $str_nopict)
					{
						$form .= "<dd><input type=\"radio\" name=\"upload\" value=\"noup\" tabindex=\"9\" checked>$str_noup<br>";
					}
					else
					{
						$form .= "<input type=\"radio\" name=\"upload\" value=\"existe\" tabindex=\"10\" checked>$str_existe $pict<br>";
						$form .= "<input type=\"hidden\" name=\"pict\" id=\"pict\" value=\"$pict\">";
					}
				}
				else
				{
					$form .= "<dd><input type=\"radio\" name=\"upload\" value=\"noup\" tabindex=\"9\" checked>$str_noup<br>";
				}
				$form .= "<input type=\"radio\" name=\"upload\" value=\"nouv\" tabindex=\"10\">$str_upload";
				$form .= "&nbsp;&nbsp;&nbsp;<input type=\"file\" name=\"upload_file\" tabindex=\"11\" id=\"upload_file\" class=\"inputbox autowidth\" /><br>";
				$form .= "<input type=\"radio\" name=\"upload\" value=\"reuse\" tabindex=\"12\" >$str_reuse";
				$form .= "&nbsp;&nbsp;&nbsp;<input type=\"text\" tabindex=\"13\" name=\"reuse\" class=\"inputbox autowidth\" />";
				$form .= "</dd>";
				$form .= "</dl>";
				// End of radio button block
				$form .= "\n<dl>";
				$form .= "<dt>&nbsp;</dt>";
				$form .= "<dd><input type=\"submit\" name=\"save\" id=\"save\" tabindex=\"5\" value=\"$str_regis\" class=\"button1\" />&nbsp;&nbsp;";
				$form .= "<input type=\"submit\" name=\"delete\" id=\"delete\" tabindex=\"6\" value=\"$str_suppr\" class=\"button1\" /></dd>";
				$form .= "</dl>";
				$form .= "</fieldset>";
				$form .= "\n</div></div></div>";
				$abc_links = $form;
				break;
			case "save" :
				$term_id	= $this->db->sql_escape(trim($this->request->variable('term_id', 0)));
				$term	= $this->db->sql_escape(trim($this->request->variable('term',"",true)));
				$variants	= $this->db->sql_escape(trim($this->request->variable('vari',"",true)));
				$descript	= $this->db->sql_escape(trim($this->request->variable('desc',"",true)));
				if (mb_strlen ($descript) > 511)
				{
					$descript = mb_substr ($descript, 0, 511);
				}
				$cat		= $this->db->sql_escape(trim($this->request->variable ('cat',"",true)));
				$ilinks	= $this->db->sql_escape(trim($this->request->variable ('ilinks',"",true)));
				$elinks	= $this->db->sql_escape(trim($this->request->variable ('elinks',"",true)));
				$label	= $this->db->sql_escape(trim($this->request->variable ('label',"",true)));
				$lang	= $this->db->sql_escape($this->request->variable ('lang',"fr",true));
				$coche	= $this->request->variable ('upload', "", true);
				switch ($coche)
				{
					case "existe":
						$picture = $this->request->variable ('pict', "", true);
					break;
					case "noup":
						$picture = $str_nopict;
					break;
					case "reuse":
						$picture = $this->request->variable ('reuse', "", true);
					break;
					case "nouv":
						$errors = array ();
						if (version_compare ($this->config['version'], '3.2.*', '>='))
						{
							$picture = $this->upload_32x ($errors);
						}
						else
						{
							$picture = $this->upload_31x ($errors);
						}
						if (!$picture)
						{
							$nb = count ($errors);
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
							$picture = $this->db->sql_escape ($picture);
						}
					break;
				}
				if ($term_id == 0)
				{
					$sql  = "INSERT INTO $table ";
					$sql .= "(variants, term, description, cat, ilinks, elinks, label, picture, lang) ";
					$sql .= " VALUES ";
					$sql .= "(\"$variants\", \"$term\", \"$descript\", \"$cat\", \"$ilinks\", '$elinks', \"$label\", \"$picture\", \"$lang\")";
					$this->db->sql_query ($sql);
					$term_id = $this->db->sql_nextid();
				}
				else
				{
					$sql  = "UPDATE $table SET ";
					$sql .= "term_id		= \"$term_id\", ";
					$sql .= "variants		= \"$variants\", ";
					$sql .= "term			= \"$term\", ";
					$sql .= "description	= \"$descript\", ";
					$sql .= "cat			= \"$cat\", ";
					$sql .= "ilinks		= \"$ilinks\", ";
					$sql .= "elinks		= \"$elinks\", ";
					$sql .= "label			= \"$label\", ";
					$sql .= "picture		= \"$picture\", ";
					$sql .= "lang			= \"$lang\" ";
					$sql .= "WHERE term_id   = \"$term_id\" ";
					$sql .= "LIMIT 1";
					$this->db->sql_query ($sql);
				}
				// Purge the cache
				$this->cache->destroy('_glossterms');
				// Redirection
				// /*
				$params = "mode=glossedit&code=$term_id";
				$url  = append_sid ($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', $params);
				$url .= "#$term_id";	// Anchor target = term_id
				redirect ($url);
				// */
				break;
			case "delete" :
				$term_id = $this->db->sql_escape ($this->request->variable ('term_id', 0));
				$sql  = "DELETE ";
				$sql .= "FROM $table ";
				$sql .= "WHERE term_id = \"$term_id\" ";
				$sql .= "LIMIT 1";
				$this->db->sql_query ($sql);
				// Purge the cache
				$this->cache->destroy('_glossterms');
				// Redirection
				$cap = substr ($this->request->variable ('term', "", true), 0, 1);
				$params = "mode=glossedit";
				$url  = append_sid ($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', $params);
				$url .= "#$cap";		// Anchor target = initial cap
				redirect ($url);
				break;
			case "rien" :
				$sql  = "SELECT DISTINCT UPPER(LEFT(TRIM(term),1)) AS a ";
				$sql .= "FROM $table ";
				// $sql .= " WHERE lang = '" . $this->user->lang['USER_LANG'] . "'";
				$sql .= " ORDER BY a" ;
				$result = $this->db->sql_query ($sql);

				$str_action = $this->user->lang['GLOSS_EDITION'];
				$str_terme  = $this->user->lang['GLOSS_ED_TERM'];
				$str_defin  = $this->user->lang['GLOSS_ED_DEF'];
				$str_illus  = $this->user->lang['GLOSS_ED_PICT'];
				$str_edit   = $this->user->lang['GLOSS_ED_ACT'];
				$str_ilinks = $this->user->lang['GLOSS_ILINKS'];
				$str_elinks = $this->user->lang['GLOSS_ELINKS'];

				$abc_links = "<span id=\"haut\"></span>";

				$corps  = "<table class=\"deg\"><tr class=\"deg\">";
				$corps .= "<th class=\"deg0\">$str_terme</th>";
				$corps .= "<th class=\"deg0\">$str_defin</th>";
				$corps .= "<th class=\"deg1\">$str_illus</th>";
				$corps .= "<th class=\"deg1\">$str_edit</th></tr>";

				$cpt  = 0;
				$str_edit2  = $this->user->lang['GLOSS_ED_EDIT'];
				$top = $this->ext_path_web . "/styles/top.gif";
				while ($row = $this->db->sql_fetchrow ($result))
				{
					$l = $row['a'];
					$abc_links .= "&nbsp;<a class=\"cap\" href =\"#$l\">$l</a>&nbsp;" ;

					$sql  = "SELECT * ";
					$sql .= "FROM $table ";
					$sql .= "WHERE LEFT($table.term, 1) = \"$l\" ";
					// $sql .= "WHERE lang = '" . $this->user->lang['USER_LANG'] . "' ";
					$sql .= "ORDER BY term";
					$result2 = $this->db->sql_query ($sql);

					$cpt++;
					$corps .= "\n<tr class=\"deg\"><td class=\"glossi\" colspan=\"3\" id=\"$l\">&nbsp;$l</td>";
					$corps .= "<td class=\"haut\"><a href=\"#haut\"><img src=\"$top\"></a></td></tr>";
					while ($arow = $this->db->sql_fetchrow ($result2))
					{
						$code  = $arow['term_id'];
						$vari  = $arow['variants'];
						$term  = $arow['term'];
						$desc  = $arow['description'];
						$cat   = $arow['cat'];
						$ilinks= $arow['ilinks'];
						$elinks= $arow['elinks'];
						$label = $arow['label'];
						$pict  = $arow['picture'];
						$corps .= "\n<tr class=\"deg\">";
						$corps .= "<td class=\"deg0\" id=\"$code\"><b>$term</b>";
						if (strlen ($cat))
						{
							$corps .= "<br>$cat";
						}
						$corps .= "<br>$code";
						$corps .= "</td>";
						$corps .= "<td class=\"deg0\">$desc";
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
						if ($pict != $str_nopict)
						{
							$params  = "mode=glosspict&code=-1&pict=$pict&terme=$term";
							$url = append_sid ($this->phpbb_root_path .'app.'.$this->phpEx .'/gloss', $params);
							$corps .= "<td class=\"deg1\"><a href=\"$url\">$pict</a></td>";
						}
						else
						{
							$corps .= "<td class=\"deg1\">$pict</td>";
						}
						$corps .= "<td class=\"deg1\">";
						$corps .= "<a href=\"";
						$params = "mode=glossedit&code=$code&action=edit";
						$corps .= append_sid ($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', $params);
						$corps .= "\">$str_edit2</a></td>";
						$corps .= "</tr>";
					}	// Fin du while sur le contenu - End of while on contents
					$this->db->sql_freeresult ($result2);
				}	// Fin du while sur les initiales - End of while on initial caps
				$this->db->sql_freeresult ($result);
				$corps .= "</table>";
				$abc_links .= "</p>\n";

				$string = $this->user->lang['GLOSS_ED_EXPL'];
				$url  = "<a href=\"";
				$url .= append_sid ($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', 'mode=glossedit&code=-1&action=edit');
				$url .= "\"";
				$illustration = sprintf ($string, $url, "</a>");
				break;
			}

		page_header($this->user->lang['TGLOSSAIRE']);

		$this->template->set_filenames (array(
			'body' => 'glossaire.html',
		));

		$params = "mode=glossedit";
		$str_glossedit = append_sid ($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', $params);
		$this->template->assign_block_vars('navlinks', array(
				'U_VIEW_FORUM'	=> $str_glossedit,
				'FORUM_NAME'	=> $this->user->lang['GLOSS_EDITION'],
			));

		$this->template->assign_vars (array (
			'TITLE'		=> $str_action,
			'ABC'		=> $abc_links,
			'ILLUST'		=> $illustration,
			'CORPS'		=> $corps,
			'BIBLIO'		=> $biblio,
			));

		make_jumpbox(append_sid($this->phpbb_root_path . 'viewforum.' . $this->phpEx));
		page_footer();
	}

	// Uploading function for phpBB 3.1.x
	function upload_31x (&$errors)
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
			$errors = array_merge ($errors, array ($this->user->lang('LMDI_GLOSS_NOFILE')));
			return (false);
		}
		$file->move_file($upload_dir, true);
		if ($file->filesize > $weight)
		{
			if (sizeof($file->error))
			{
				$errors = array_merge ($errors, $file->error);
				$file->remove();
				return (false);
			}
		}
		if ($file->width > $pixels || $file->height > $pixels)
		{
			if (sizeof($file->error))
			{
				$errors = array_merge ($errors, $file->error);
				$file->remove();
				return (false);
			}
		}
		$filename = $file->uploadname;
		@chmod($upload_dir . '/' . $filename, 0644);
		return ($filename);
	}

	// Uploading function for phpBB 3.2.x
	function upload_32x (&$errors)
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
		$file = $upload->handle_upload ('files.types.form', 'upload_file');
		$file->move_file($upload_dir, true);
		$filesize = $file->get('filesize');
		if ($filesize > $weight)
		{
			if (sizeof($file->error))
			{
				$errors = array_merge ($errors, $file->error);
				$file->remove();
				return (false);
			}
		}
		$filename = $file->get('realname');
		$filepath = $upload_dir . '/' . $filename;
		$fdata = getimagesize ($filepath);
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
		return ($filename);
	}
}

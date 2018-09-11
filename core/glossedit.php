<?php
// glossedit.php
// @copyright (c) 2015-2018 - LMDI Pierre Duhem
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
				else		// Item edition - Édition d'une fiche
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
					'S_EDIT'		=> $sw,		// 0 = creation, 1 = edition
					'S_PICT'		=> $pict == $str_nopict ? 1 : 0,
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
			case "save":
				$term_id = $this->request->variable('term_id', 0);
				$term = trim($this->request->variable('term',"",true));
				$variants = trim($this->request->variable('vari',"",true));
				$descript = trim($this->request->variable('desc',"",true));
				if (mb_strlen($descript) > 511)
				{
					$descript = mb_substr($descript, 0, 511);
				}
				$cat = trim($this->request->variable('cat',"",true));
				$ilinks = trim($this->request->variable('ilinks',"",true));
				$elinks = trim($this->request->variable('elinks',"",true));
				$label = trim($this->request->variable('label',"",true));
				$lang = trim($this->request->variable('lang',"fr",true));
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
				if (!$term_id)
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
					$this->db->sql_query_limit($sql, 1);
				}
				// Purge the cache
				$this->cache->destroy('_glossterms');
				$this->cache->destroy('_gloss_table');
				$this->cache->destroy('_gloss_abc_table');
				// Redirection
				$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit', 'code' => $term_id));
				$url .= "#$term_id"; // Anchor target = term_id
				redirect($url);
				break;
			case "delete":
				$term_id = $this->request->variable('term_id', 0);
				$sql  = "DELETE FROM $table WHERE term_id = $term_id";
				$this->db->sql_query_limit($sql, 1);
				// Purge the cache
				$this->cache->destroy('_glossterms');
				$this->cache->destroy('_gloss_table');
				$this->cache->destroy('_gloss_abc_table');
				// Redirection
				$cap = substr($this->request->variable('term', "", true), 0, 1);
				$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit'));
				$url .= "#$cap";		// Anchor target = initial cap
				redirect($url);
				break;
			case "rien":
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

				$str_action = $this->user->lang['GLOSS_ED_EDIT'];
				$str_display = $this->user->lang['GLOSS_DISPLAY'];
				$str_ilinks = $this->user->lang['GLOSS_ILINKS'];
				$str_elinks = $this->user->lang['GLOSS_ELINKS'];
				$str_edit2  = $this->user->lang['GLOSS_ED_EDIT'];

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
							$str_url = $str_display;
						}
						else
						{
							$url= "";
							$str_url = "";
						}
						$act = "<a href=\"";
						$act .= $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit', 'code' => $code, 'action' => 'edit'));
						$act .= "\">$str_edit2</a></td>";
						$this->template->assign_block_vars('ged', array(
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
							'ACTION'  => $act,
							));
						$cpt++;
					}	// Inner foreach
				}	// Outer foreach

				$ed_url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit', 'code' => -1, 'action' => 'edit'));
				$this->template->assign_vars(array(
					'ED_EXPLAIN'	=> $this->user->lang['GLOSS_ED_EXPL'],
					'ED_URL'		=> $ed_url,
					'ED_ANCHOR'	=> $this->user->lang['GLOSS_ED_ANCHOR'],
					'BACKTOP'		=> $this->user->lang['LMDI_BACK_TOP'],
					));

				return $this->helper->render ('glossedit.html', $this->user->lang['TGLOSSAIRE']);
			}	// case rien
		}	// switch

	}	// main


	// Uploading function for phpBB 3.1.x
	private function upload_31x(&$errors)
	{
		if (!class_exists('upload'))
		{
			include($this->phpbb_root_path . 'includes/functions_upload.' . $this->phpEx);
		}

		// Set upload directory
		mkdir('store/lmdi');
		mkdir('store/lmdi/gloss');
		$upload_dir = $this->php_root_path . 'store/lmdi/gloss/';

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
	}	// upload_31x


	// Uploading function for phpBB 3.2.x
	private function upload_32x(&$errors)
	{
		global $phpbb_container;

		// Set upload directory
		$filesystem = $phpbb_container->get('filesystem');
		$filesystem->mkdir('store/lmdi/gloss');
		$upload_dir = $this->php_root_path . 'store/lmdi/gloss/';

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
	}	// upload_32x

}

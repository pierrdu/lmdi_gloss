<?php
// glossclean.php
// (c) 2016 - LMDI - Pierre Duhem
// PHP script displaying the MySQL commands to be run to delete all entries
// created by the extension in the database.
// To be used if the normal desactivation/data deletion doesn't work.
// Don't forget to empty the forum cache afterwards.
// To be launched as: app.php/gloss?mode=glossclean

namespace lmdi\gloss\core;

class glossclean
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\user */
	protected $user;
	/** @var \phpbb\auth\auth */
	protected $auth;
	/** @var \phpbb\request\request */
	protected $request;
	/** @var \phpbb\cache\service */
	protected $cache;
	/** @var string */
	protected $phpEx;
	/** @var string phpBB root path */
	protected $phpbb_root_path;
	/** @var \phpbb\extension\manager "Extension Manager" */
	protected $ext_manager;
	/** @var \phpbb\path_helper */
	protected $path_helper;
	// Strings
	protected $table_prefix;
	protected $ext_path;
	protected $ext_path_web;

	/**
	* Constructor
	*
	*
	*/
	public function __construct(
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\extension\manager $ext_manager,
		\phpbb\path_helper $path_helper,
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\cache\service $cache,
		$table_prefix,
		$phpEx,
		$phpbb_root_path
		)
	{
		$this->template 		= $template;
		$this->user 			= $user;
		$this->ext_manager	 	= $ext_manager;
		$this->path_helper	 	= $path_helper;
		$this->request 		= $request;
		$this->db 			= $db;
		$this->cache			= $cache;
		$this->table_prefix 	= $table_prefix;
		$this->phpEx 			= $phpEx;
		$this->phpbb_root_path 	= $phpbb_root_path;

		$this->ext_path = $this->ext_manager->get_extension_path('lmdi/gloss', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	var $u_action;

	function main()
	{
		$abc_links = "";
		$illustration = "";
		$corps = "";
		$biblio = "";

		$prefix = $this->table_prefix;

		$mig = $this->db->sql_escape ("\lmdi\gloss\migrations\release_1");
		$sql1 = "DELETE FROM ${prefix}acl_roles WHERE role_name LIKE 'ROLE_GLOSS%'";
		$sql2 = "DELETE FROM ${prefix}acl_options WHERE auth_option LIKE '%lmdi_glossary'";
		$sql3 = "DELETE FROM ${prefix}config WHERE config_name LIKE 'lmdi_glossary%'";
		$sql4 = "ALTER TABLE ${prefix}users DROP COLUMN lmdi_gloss";
		$sql5 = "DELETE FROM ${prefix}ext WHERE ext_name = 'lmdi/gloss'";
		$sql6 = "DELETE FROM ${prefix}migrations WHERE migration_name = '$mig'";
		$sql7 = "DROP TABLE ${prefix}glossary";

		$corps  = "<p>";
		$corps .= "Request 1 = $sql1<br>\n";
		$corps .= "Request 2 = $sql2<br>\n";
		$corps .= "Request 3 = $sql3<br>\n";
		$corps .= "Request 4 = $sql4<br>\n";
		$corps .= "Request 5 = $sql5<br>\n";
		$corps .= "Request 6 = $sql6<br>\n";
		$corps .= "Request 7 = $sql7<br>\n";
		$corps .= "</p>";

		/*
		$this->db->sql_query($sql1);
		$this->db->sql_query($sql2);
		$this->db->sql_query($sql3);
		$this->db->sql_query($sql4);
		$this->db->sql_query($sql5);
		$this->db->sql_query($sql6);
		$this->db->sql_query($sql7);
		$this->cache->purge ();
		*/

		$titre = $this->user->lang['TGLOSSAIRE'];
		page_header($titre);

		$this->template->set_filenames (array(
			'body' => 'gloss/glossaire.html',
		));

		$params = "mode=glossedit";
		$str_glossedit = append_sid ($this->phpbb_rooU_path . 'app.' . $this->phpEx . '/gloss', $params);
		$this->template->assign_block_vars('navlinks', array(
				'U_VIEW_FORUM'	=> $str_glossedit,
				'FORUM_NAME'	=> $this->user->lang['GLOSS_EDITION'],
			));

		$this->template->assign_vars (array (
			'U_TITRE'			=> $titre,
			'U_ABC'			=> $abc_links,
			'U_ILLUST'		=> $illustration,
			'U_CORPS'			=> $corps,
			'U_BIBLIO'		=> $biblio,
			));

		make_jumpbox(append_sid($this->phpbb_root_path . 'viewforum.' . $this->phpEx));
		page_footer();
	}
}

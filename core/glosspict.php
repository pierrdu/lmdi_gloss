<?php
// glosspict.php
// (c) 2015-2016 - LMDI - Pierre Duhem
// Page d'affichage d'une image centrée complétant le terme du glossaire
// Page displaying a centered picture attached to the glossary term

namespace lmdi\gloss\core;

class glosspict
{
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\user */
	protected $user;
	/** @var \phpbb\auth\auth */
	protected $auth;
	/** @var \phpbb\request\request */
	protected $request;
	/** @var string */
	protected $phpEx;
	/** @var string phpBB root path */
	protected $phpbb_root_path;
	/** @var \phpbb\extension\manager "Extension Manager" */
	protected $ext_manager;
	/** @var \phpbb\path_helper */
	protected $path_helper;
	// Strings
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
		$phpEx,
		$phpbb_root_path
		)
	{
		$this->template 		= $template;
		$this->user 			= $user;
		$this->ext_manager	 	= $ext_manager;
		$this->path_helper	 	= $path_helper;
		$this->request 		= $request;
		$this->phpEx 			= $phpEx;
		$this->phpbb_root_path 	= $phpbb_root_path;

		$this->ext_path = $this->ext_manager->get_extension_path('lmdi/gloss', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public $u_action;

	function main()
	{

		$click = $this->user->lang['GLOSS_CLICK'];
		$view = $this->user->lang['GLOSS_VIEW'];
		$pict = $this->request->variable ('pict', '');
		$pict = $this->ext_path_web . "glossaire/" . $pict;
		$term = $this->request->variable ('term', '', true);
		$code = $this->request->variable ('code', '0', true);
		$terme = "<p class=\"copyright\"><b>$term</b></p>";
		$corps = "<p class=\"copyright\"><a href=\"javascript:history.go(-1);\"><img src=\"$pict\"></a></p>";
		$retour = "<p class=\"copyright\">$click</p>";

		page_header($view);
		$this->template->set_filenames (array (
			'body' => 'glossaire.html',
		));

		if ($code == -1)
		{
			$params = "mode=glossedit";
			$str_glossedit = append_sid ($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', $params);
			$this->template->assign_block_vars('navlinks', array(
				'U_VIEW_FORUM'	=> $str_glossedit,
				'FORUM_NAME'	=> $this->user->lang['GLOSS_EDITION'],
			));
		}

		$params = "mode=glosspict";
		$str_glosspict = append_sid ($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', $params);
		$this->template->assign_block_vars('navlinks', array(
			'U_VIEW_FORUM'	=> $str_glosspict,
			'FORUM_NAME'	=> $this->user->lang['GLOSS_VIEW'],
		));

		$this->template->assign_vars (array (
			'TITRE'			=> $view,
			'ILLUST'		=> $terme,
			'CORPS'			=> $corps,
			'BIBLIO'		=> $retour,
		));

		make_jumpbox(append_sid("{$this->phpbb_root_path}viewforum.$this->phpEx"));
		page_footer();
	}
}

<?php
// glosspict.php
// @copyright (c) 2015-2018 - LMDI - Pierre Duhem
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
	/** @var \phpbb\controller\helper */
	protected $helper;
	// Strings
	protected $ext_path;
	protected $ext_path_web;

	public function __construct(
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\controller\helper $helper,
		\phpbb\request\request $request,
		$phpEx,
		$phpbb_root_path
		)
	{
		$this->template		= $template;
		$this->user			= $user;
		$this->helper			= $helper;
		$this->request			= $request;
		$this->phpEx			= $phpEx;
		$this->phpbb_root_path	= $phpbb_root_path;
	}

	public $u_action;

	public function main()
	{

		$term = $this->request->variable('term', '', true);
		$code = $this->request->variable('code', 0);
		$pict = $this->request->variable('pict', '');
		$pict = $this->phpbb_root_path . "../store/lmdi/gloss/" . $pict;
		// Without .., since we are in app.php/gloss?mode=glosspict, the picture doesn't display.

		if ($code == -1)
		{
			$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossedit'));
			$this->template->assign_block_vars('navlinks', array(
				'U_VIEW_FORUM'	=> $url,
				'FORUM_NAME'	=> $this->user->lang['GLOSS_EDITION'],
			));
		}
		else
		{
			$url = $this->helper->route('lmdi_gloss_controller', array('mode' => 'glosspict'));
			$this->template->assign_block_vars('navlinks', array(
				'U_VIEW_FORUM'	=> $url,
				'FORUM_NAME'	=> $this->user->lang['GLOSS_VIEW'],
			));
		}

		$this->template->assign_vars(array(
			'TITLE'		=> $this->user->lang['GLOSS_VIEW'],
			'TERM'		=> $term,
			'PICT'		=> $pict,
			'EXPLAIN'		=> $this->user->lang['GLOSS_CLICK'],
		));

		return $this->helper->render ('glossview.html', $this->user->lang['GLOSS_VIEW']);
	}	// main
}

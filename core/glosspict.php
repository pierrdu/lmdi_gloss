<?php
// glosspict.php
// (c) 2015-2019 - LMDI - Pierre Duhem
// Page d'affichage d'une image centrée complétant le terme du glossaire
// Page displaying a centered picture attached to the glossary term

namespace lmdi\gloss\core;

class glosspict
{
	protected $template;
	protected $language;
	protected $auth;
	protected $request;
	protected $phpEx;
	protected $phpbb_root_path;
	protected $ext_manager;
	protected $path_helper;
	protected $ext_path;
	protected $ext_path_web;

	public function __construct(
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		\phpbb\extension\manager $ext_manager,
		\phpbb\path_helper $path_helper,
		\phpbb\request\request $request,
		$phpEx,
		$phpbb_root_path
		)
	{
		$this->template		= $template;
		$this->language		= $language;
		$this->ext_manager	 	= $ext_manager;
		$this->path_helper	 	= $path_helper;
		$this->request			= $request;
		$this->phpEx			= $phpEx;
		$this->phpbb_root_path	= $phpbb_root_path;

		$this->ext_path = $this->ext_manager->get_extension_path('lmdi/gloss', true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->ext_path);
	}

	public $u_action;

	public function main()
	{

		$this->language->add_lang ('edit_gloss', 'lmdi/gloss');
		$pict = $this->request->variable('pict', '');
		$pict = $this->phpbb_root_path . "../store/lmdi/gloss/" . $pict;
		$code = $this->request->variable('code', '0', true);
		$terme = "<p class=\"copyright\"><b>$term</b></p>";
		$corps = "<p class=\"copyright\"><a href=\"javascript:history.go(-1);\"><img src=\"$pict\"></a></p>";
		$retour = "<p class=\"copyright\">$click</p>";

		page_header($view);
		$this->template->set_filenames(array(
			'body' => 'glosspict.html',
		));

		$params = "mode=glosspict";
		$str_glosspict = append_sid($this->phpbb_root_path . 'app.' . $this->phpEx . '/gloss', $params);
		$this->template->assign_block_vars('navlinks', array(
			'U_VIEW_FORUM'	=> $str_glosspict,
			'FORUM_NAME'	=> $this->language->lang('GLOSS_VIEW'),
		));

		$this->template->assign_vars(array(
			'TERM'		=> $term,
			'PICT'		=> $corps,
		));

		make_jumpbox(append_sid("{$this->phpbb_root_path}viewforum.$this->phpEx"));
		page_footer();
	}
}

<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* @copyright (c) 2015-2019 LMDI - Pierre Duhem
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace lmdi\gloss\controller;

class main
{
	protected $glossadmin;
	protected $glossaire;
	protected $glossedit;
	protected $glossnew;
	protected $glosspict;
	protected $lexicon;
	protected $template;
	protected $user;
	protected $language;
	protected $request;
	protected $helper;
	protected $phpbb_root_path;
	protected $phpEx;

	public function __construct(
		\lmdi\gloss\core\glossadmin $glossadmin,
		\lmdi\gloss\core\glossaire $glossaire,
		\lmdi\gloss\core\glossedit $glossedit,
		\lmdi\gloss\core\glossnew $glossnew,
		\lmdi\gloss\core\glosspict $glosspict,
		\lmdi\gloss\core\lexicon $lexicon,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\language\language $language,
		\phpbb\request\request $request,
		\phpbb\controller\helper $helper,
		$phpbb_root_path,
		$phpEx)
	{
		$this->glossadmin		= $glossadmin;
		$this->glossaire		= $glossaire;
		$this->glossedit		= $glossedit;
		$this->glossnew		= $glossnew;
		$this->glosspict		= $glosspict;
		$this->lexicon			= $lexicon;
		$this->template		= $template;
		$this->user			= $user;
		$this->language		= $language;
		$this->request			= $request;
		$this->helper			= $helper;
		$this->phpbb_root_path	= $phpbb_root_path;
		$this->phpEx 			= $phpEx;
	}


	public function handle_gloss()
	{
		// Exclude Bots
		if ($this->user->data['is_bot'])
		{
			redirect(append_sid($this->phpbb_root_path . 'index.' . $this->phpEx));
		}

		// Variables
		$mode = $this->request->variable('mode', '');

		// String loading
		$this->language->add_lang('edit_gloss', 'lmdi/gloss');

		// Add the base entry into the breadcrump at top
		$this->template->assign_block_vars('navlinks', array(
			'U_VIEW_FORUM'	=> $this->helper->route('lmdi_gloss_controller'),
			'FORUM_NAME'	=> $this->language->lang('LGLOSSAIRE'),
		));

		switch ($mode)
		{
			case 'glossadmin':
				$this->glossadmin->main();
			break;
			case 'glossedit':
				$this->glossedit->main();
			break;
			case 'glossnew':
				$this->glossnew->main();
			break;
			case 'glosspict':
				$this->glosspict->main();
			break;
			case 'lexicon':
				$this->lexicon->main();
			break;
			default:
				$this->glossaire->main();
			break;
		}
	}
}

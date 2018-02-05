<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* @copyright (c) 2015-2018 LMDI - Pierre Duhem
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace lmdi\gloss\controller;

class main
{
	protected $glossaire;
	protected $glossedit;
	protected $glosspict;
	protected $lexicon;
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\user */
	protected $user;
	/** @var \phpbb\request\request */
	protected $request;
	/** @var \phpbb\controller\helper */
	protected $helper;
	/** @var string phpBB root path */
	protected $phpbb_root_path;
	/** @var string phpEx */
	protected $phpEx;

	public function __construct(
		\lmdi\gloss\core\glossaire $glossaire,
		\lmdi\gloss\core\glossedit $glossedit,
		\lmdi\gloss\core\glosspict $glosspict,
		\lmdi\gloss\core\lexicon $lexicon,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\request\request $request,
		\phpbb\controller\helper $helper,
		$phpbb_root_path,
		$phpEx)
	{
		$this->glossaire		= $glossaire;
		$this->glossedit		= $glossedit;
		$this->glosspict		= $glosspict;
		$this->lexicon			= $lexicon;
		$this->template		= $template;
		$this->user			= $user;
		$this->request			= $request;
		$this->helper			= $helper;
		$this->phpbb_root_path	= $phpbb_root_path;
		$this->phpEx			= $phpEx;
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
		$this->user->add_lang_ext('lmdi/gloss', 'edit_gloss');

		// Add the base entry into the breadcrump at top
		$this->template->assign_block_vars('navlinks', array(
			'U_VIEW_FORUM'	=> $this->helper->route('lmdi_gloss_controller'),
			'FORUM_NAME'	=> $this->user->lang['LGLOSSAIRE'],
		));

		switch ($mode)
		{
			case 'glosspict':
				return $this->glosspict->main();
			case 'glossedit':
				return $this->glossedit->main();
			case 'lexicon':
				$this->lexicon->main();
			break;
			case 'glossaire':
			default:
				return $this->glossaire->main();
		}
	}

}

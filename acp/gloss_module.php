<?php
/**
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2017 Pierre Duhem - LMDI
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace lmdi\gloss\acp;

class gloss_module {

	protected $gloss_helper;
	public $u_action;
	protected $action;

	public function main($id, $mode)
	{
		global $db, $user, $template, $cache, $request;
		global $config, $table_prefix, $phpbb_container;

		$this->gloss_helper = $phpbb_container->get('lmdi.gloss.core.helper');

		$user->add_lang_ext('lmdi/gloss', 'gloss');
		$this->tpl_name = 'acp_gloss_body';
		$this->page_title = $user->lang('ACP_GLOSS_TITLE');
		$action = $request->variable('action', '');
		$action_config = $this->u_action . "&action=config";

		if ($action == 'config')
		{
			if (!check_form_key('acp_gloss_body'))
			{
				trigger_error('FORM_INVALID');
			}
			else
			{
				// Update configuration
				$acp = (int) $request->variable('lmdi_gloss_acp', 0);
				$config->set('lmdi_glossary_acp', $acp);

				// Update the lmdi_gloss column in table users
				$sql = "UPDATE " . USERS_TABLE . " SET lmdi_gloss = $acp ";
				$db->sql_query($sql);

				// Tooltip validation
				$title = $request->variable('lmdi_gloss_title', 0);
				if ($title != $config['lmdi_gloss_title'])
				{
					$config->set('lmdi_glossary_title', $title);
					$cache->destroy('_glossterms');
				}

				// Tooltip length
				$titlength = $request->variable('titlength', 0);
				if ($titlength != $config['lmdi_glossary_tooltip'])
				{
					$config->set('lmdi_glossary_tooltip', $titlength);
					$cache->destroy('_glossterms');
				}

				// Language selection
				$lang = $request->variable('lang', '');
				$table = $table_prefix . 'glossary';
				$lg = $this->gloss_helper->get_def_language($table, 'lang');
				if ($lang != $lg)
				{
					$sql = "ALTER TABLE $table ALTER COLUMN lang SET DEFAULT '$lang'";
					$db->sql_query($sql);
				}

				// Pixel limit
				$px = $request->variable('pixels', 400);
				$config->set('lmdi_glossary_pixels', $px);

				// Picture weight
				$ko = $request->variable('weight', 200);
				$config->set('lmdi_glossary_weight', $ko);

				// Usergroup creation/deletion
				$ug = $request->variable('lmdi_gloss_ugroup', 0);
				if ($config['lmdi_glossary_usergroup'] != $ug)
				{
					$config->set('lmdi_glossary_usergroup', $ug);
					$usergroup = $user->lang('GROUP_GLOSS_EDITOR');
					$groupdesc = $user->lang('GROUP_DESCRIPTION_GLOSS_EDITOR');
					$userrole  = 'ROLE_GLOSS_EDITOR';
					if ($ug)
					{
						$this->gloss_helper->group_creation($usergroup, $groupdesc);
						$this->gloss_helper->role_addition($usergroup, $userrole);
					}
					else
					{
						$this->gloss_helper->role_deletion($usergroup, $userrole);
						$this->gloss_helper->group_deletion($usergroup);
					}
				}

				// Admin group creation/deletion
				$ag = $request->variable('lmdi_gloss_agroup', 0);
				if ($config['lmdi_glossary_admingroup'] != $ag)
				{
					$config->set('lmdi_glossary_admingroup', $ag);
					$admingroup = $user->lang('GROUP_GLOSS_ADMIN');
					$groupdesc  = $user->lang('GROUP_DESCRIPTION_GLOSS_ADMIN');
					$adminrole  = 'ROLE_GLOSS_ADMIN';
					if ($ag)
					{
						$this->gloss_helper->group_creation($admingroup, $groupdesc);
						$this->gloss_helper->role_addition($admingroup, $adminrole);
					}
					else
					{
						$this->gloss_helper->role_deletion($admingroup, $adminrole);
						$this->gloss_helper->group_deletion($admingroup);
					}
				}

				// Forum enabling/disabling
				$enabled_forums = implode(',', $request->variable('mark_glossary_forum', array(0), true));
				$sql = 'UPDATE ' . FORUMS_TABLE . '
					SET lmdi_glossary = 0';
				$db->sql_query($sql);
				if (!empty($enabled_forums))
				{
					$sql = 'UPDATE ' . FORUMS_TABLE . '
						SET lmdi_glossary = 1
						WHERE forum_id IN (' . $enabled_forums . ')';
					$db->sql_query($sql);
					$farray = explode(',', $enabled_forums);
					$cache->put('_gloss_forums', $farray, 86400);	// 24 h
				}
				else
				{
					$cache->destroy('_gloss_forums');
				}

				// Information message
				$message = $user->lang['CONFIG_UPDATED'];
				trigger_error($message . adm_back_link($this->u_action));
			}
		}

		$form_key = 'acp_gloss_body';
		add_form_key($form_key);
		$select = $this->gloss_helper->build_lang_select();
		$pixels = $config['lmdi_glossary_pixels'];
		if (!$pixels)
		{
			$pixels = 500;
		}
		$weight  = $config['lmdi_glossary_weight'];
		if (!$weight)
		{
			$weight = 150;
		}
		$titlength = $config['lmdi_glossary_tooltip'];
		if (!$titlength)
		{
			$titlength = 50;
		}
		$forum_list = $this->get_forum_list();
		foreach ($forum_list as $row)
		{
			$template->assign_block_vars('forums', array(
				'FORUM_NAME'			=> $row['forum_name'],
				'FORUM_ID'			=> $row['forum_id'],
				'CHECKED_ENABLE_FORUM'	=> $row['lmdi_glossary'] ? 'checked="checked"' : '',
			));
		}

		$template->assign_vars(array(
			'C_ACTION'		=> $action_config,
			'ALLOW_FEATURE_NO'	=> $config['lmdi_glossary_acp'] == 0 ? 'checked="checked"' : '',
			'ALLOW_FEATURE_YES'	=> $config['lmdi_glossary_acp'] == 1 ? 'checked="checked"' : '',
			'ALLOW_TITLE_NO'	=> $config['lmdi_glossary_title'] == 0 ? 'checked="checked"' : '',
			'ALLOW_TITLE_YES'	=> $config['lmdi_glossary_title'] == 1 ? 'checked="checked"' : '',
			'CREATE_UGROUP_NO'	=> $config['lmdi_glossary_usergroup'] == 0 ? 'checked="checked"' : '',
			'CREATE_UGROUP_YES'	=> $config['lmdi_glossary_usergroup'] == 1 ? 'checked="checked"' : '',
			'CREATE_AGROUP_NO'	=> $config['lmdi_glossary_admingroup'] == 0 ? 'checked="checked"' : '',
			'CREATE_AGROUP_YES'	=> $config['lmdi_glossary_admingroup'] == 1 ? 'checked="checked"' : '',
			'S_PIXELS'		=> $pixels,
			'S_WEIGHT'		=> $weight,
			'S_LANG_OPTIONS'	=> $select,
			'S_TITLENGTH'		=> $titlength,
			));
	}

	protected function get_forum_list()
	{
		global $db;
		$sql = 'SELECT forum_id, forum_name, lmdi_glossary
			FROM ' . FORUMS_TABLE . '
			WHERE forum_type = ' . FORUM_POST . '
			ORDER BY left_id ASC';
		$result = $db->sql_query($sql);
		$forum_list = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		return $forum_list;
	}

}

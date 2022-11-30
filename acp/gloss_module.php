<?php
/**
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2021 Pierre Duhem - LMDI
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
		global $db, $language, $template, $cache, $request, $config, $table_prefix, $phpbb_container;
		$form_valid = 'acp_gloss_body';

		$this->gloss_helper = $phpbb_container->get('lmdi.gloss.core.helper');

		$language->add_lang('gloss', 'lmdi/gloss');
		$this->tpl_name = 'acp_gloss_body';
		$this->page_title = $language->lang('ACP_GLOSS_TITLE');
		$action = $request->variable('action', '');
		$action_config = $this->u_action . "&action=config";

		if ($action == 'config')
		{
			if (!check_form_key($form_valid))
			{
				trigger_error('FORM_INVALID');
			}
			else
			{
				// General validation of the extension
				$acp = (int) $request->variable('lmdi_glossary_acp', 0);
				if ($acp != $config['lmdi_glossary_acp'])
				{
					$config->set('lmdi_glossary_acp', $acp);
					// Update the lmdi_gloss column in table users
					$sql = "UPDATE " . USERS_TABLE . " SET lmdi_gloss = $acp ";
					$db->sql_query($sql);
				}

				// Tooltip validation
				$title = (int) $request->variable('lmdi_glossary_title', 0);
				if ($title != $config['lmdi_glossary_title'])
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
				$ug = (int) $request->variable('lmdi_glossary_ugroup', 0);
				if ($ug != $config['lmdi_glossary_ugroup'])
				{
					$config->set('lmdi_glossary_ugroup', $ug);
					$usergroup = $language->lang('GROUP_GLOSS_EDITOR');
					$groupdesc = $language->lang('GROUP_DESCRIPTION_GLOSS_EDITOR');
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
				$ag = (int) $request->variable('lmdi_glossary_agroup', 0);
				if ($ag != $config['lmdi_glossary_agroup'])
				{
					$config->set('lmdi_glossary_agroup', $ag);
					$admingroup = $language->lang('GROUP_GLOSS_ADMIN');
					$groupdesc  = $language->lang('GROUP_DESCRIPTION_GLOSS_ADMIN');
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
				$sql = 'UPDATE ' . FORUMS_TABLE . ' SET lmdi_glossary = 0';
				$db->sql_query($sql);
				if (!empty($enabled_forums))
				{
					$sql = 'UPDATE ' . FORUMS_TABLE . '
						SET lmdi_glossary = 1
						WHERE forum_id IN (' . $enabled_forums . ')';
					$db->sql_query($sql);
					$farray = explode(',', $enabled_forums);
					$cache->put('_gloss_forums', $farray, 86400); // 24 h
				}
				else
				{
					$cache->destroy('_gloss_forums');
				}

				// Information message
				trigger_error($language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
			}
		}

		add_form_key($form_valid);
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
			'CREATE_UGROUP_NO'	=> $config['lmdi_glossary_ugroup'] == 0 ? 'checked="checked"' : '',
			'CREATE_UGROUP_YES'	=> $config['lmdi_glossary_ugroup'] == 1 ? 'checked="checked"' : '',
			'CREATE_AGROUP_NO'	=> $config['lmdi_glossary_agroup'] == 0 ? 'checked="checked"' : '',
			'CREATE_AGROUP_YES'	=> $config['lmdi_glossary_agroup'] == 1 ? 'checked="checked"' : '',
			'S_PIXELS'		=> $pixels,
			'S_WEIGHT'		=> $weight,
			'S_LANG_OPTIONS'	=> $select,
			'S_TITLENGTH'		=> $titlength,
			));
	}	// main


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
	}	// get_forum_list

}

<?php
// helper.php
// (c) 2015-2018 - LMDI - Pierre Duhem
// Helper class

namespace lmdi\gloss\core;

class helper
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	/** @var string $table_prefix */
	protected $table_prefix;
	/** @var string */
	protected $glossary_table;
	/** @var string $phpbb_root_path */
	protected $phpbb_root_path;
	/** @var string phpEx */
	protected $php_ext;


	public function __construct(
		\phpbb\db\driver\driver_interface $db,
		$table_prefix,
		$glossary_table,
		$phpbb_root_path, $php_ext)
	{
		$this->db 			= $db;
		$this->table_prefix 	= $table_prefix;
		$this->glossary_table 	= $glossary_table;
		$this->phpbb_root_path	= $phpbb_root_path;
		$this->php_ext			= $php_ext;
	}

	public function calcul_ilinks($ilinks)
	{
		$table = $this->glossary_table;
		$data = explode(",", $ilinks);
		$nb = count($data);
		$string = "";
		for ($i = 0; $i < $nb; $i++)
		{
			$term0 = trim($data[$i]);
			$term1 = $this->db->sql_escape($term0);
			$sql = "SELECT term_id FROM $table WHERE term = '$term1'";
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$code = $row['term_id'];
			$this->db->sql_freeresult($result);
			if ($code)
			{
				if (strlen($string))
				{
					$string .= ", ";
				}
				$string .= "<a class=\"ilinks\" href=\"#$code\">$term0</a>";
			}
			else
			{
				if (strlen($string))
				{
					$string .= ", ";
				}
				$string .= $term0;
			}
		}
		return ($string);
	}	// calcul_links


	public function get_role_id($role_name)
	{
		$prefix = $this->table_prefix;
		$role_name = $this->db->sql_escape($role_name);
		$sql = "SELECT role_id FROM {$prefix}acl_roles WHERE role_name = '$role_name'";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$role_id = (int) $row['role_id'];
		$this->db->sql_freeresult($result);
		return ($role_id);
	}


	public function get_group_id($group_name)
	{
		$prefix = $this->table_prefix;
		$group_name = $this->db->sql_escape($group_name);
		$sql = "SELECT group_id FROM {$prefix}groups WHERE group_name = '$group_name'";
		$result = $this->db->sql_query($sql);
		$group_id = (int) $this->db->sql_fetchfield('group_id');
		$this->db->sql_freeresult($result);
		return ($group_id);
	}


	public function group_deletion($group)
	{
		$group_id = $this->get_group_id($group);
		if ($group_id)
		{
			if (!function_exists('group_delete'))
			{
				include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
			}
			group_delete($group_id, $group);
		}
	}


	public function get_def_language($table, $colonne)
	{
		$sql = "SELECT DEFAULT($colonne) lg FROM (SELECT 1) AS dummy LEFT JOIN $table ON True";
		$result = $this->db->sql_query_limit($sql, 1);
		$row = $this->db->sql_fetchrow($result);
		$default = $row['lg'];
		$this->db->sql_freeresult($result);
		return ($default);
	}


	public function role_addition($group, $role)
	{
		$prefix = $this->table_prefix;
		$group_id = $this->get_group_id($group);
		$role_id = $this->get_role_id($role);
		$sql = "INSERT into {$prefix}acl_groups 
			(group_id, forum_id, auth_option_id, auth_role_id, auth_setting)
			VALUES ($group_id, 0, 0, $role_id, 0)";
		$this->db->sql_query($sql);
	}


	public function role_deletion($group, $role)
	{
		$prefix = $this->table_prefix;
		$group_id = $this->get_group_id($group);
		$role_id = $this->get_role_id($role);
		$sql = "DELETE FROM {$prefix}acl_groups 
			WHERE group_id = $group_id AND auth_role_id = $role_id";
		$this->db->sql_query($sql);
	}


	public function group_creation($group, $desc)
	{
		$prefix = $this->table_prefix;
		$group_id = 0;
		$group_type = 0;
		$group_name = $group;
		$group_desc = $desc;

		$group_attributes = array(
			'group_colour'			=> '000000',
			'group_rank'			=> 0,
			'group_avatar'			=> 0,
			'group_avatar_type'		=> 0,
			'group_avatar_width'	=> 0,
			'group_avatar_height'	=> 0,
			'group_legend'			=> 0,
			'group_receive_pm'		=> 0,
			);
		// Function in file includes/functions_user.php
		if (!function_exists('group_create'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
		}
		group_create($group_id, $group_type, $group_name, $group_desc, $group_attributes);
		// Mark group hidden
		$sql = "UPDATE {$prefix}groups SET group_type = " . GROUP_HIDDEN . " WHERE group_id = $group_id";
		$this->db->sql_query($sql);
	}


	public function build_lang_select()
	{
		$table = $this->table_prefix . 'glossary';
		$lg = $this->get_def_language($table, 'lang');
		$select = "";

		$sql = 'SELECT lang_iso FROM ' . LANG_TABLE . ' ORDER BY lang_iso';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$lang = $row['lang_iso'];
			if ($lang == $lg)
			{
				$select .= "<option value=\"$lang\" selected>$lang</option>";
			}
			else
			{
				$select .= "<option value=\"$lang\">$lang</option>";
			}
		}
		$this->db->sql_freeresult($result);
		return ($select);
	}

}

<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* @copyright (c) 2015-2018 Pierre Duhem - LMDI
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace lmdi\gloss\migrations;

class release_1 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['lmdi_glossary']);
	}


	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\alpha2');
	}


	public function update_schema()
	{
		return array(
			'add_tables'   => array(
				$this->table_prefix . 'glossary'   => array(
					'COLUMNS'   => array(
						'term_id'		=> array('UINT', null, 'auto_increment'),
						'variants'	=> array('VCHAR:80', ''),
						'term'		=> array('VCHAR:80', ''),
						'description'	=> array('VCHAR:512', ''),
						'cat'		=> array('VCHAR:32', ''),
						'ilinks'		=> array('VCHAR:256', ''),
						'elinks'		=> array('VCHAR:256', ''),
						'label'		=> array('VCHAR:32', ''),
						'picture'		=> array('VCHAR:80', ''),
						'lang'		=> array('VCHAR:2', 'en'),
					),
					'PRIMARY_KEY'	=> 'term_id',
					'KEYS'  => array('term'  => array('INDEX', 'term')),
				),
			),
			'add_columns'	=> array(
				$this->table_prefix . 'users' => array(
					'lmdi_gloss' => array('BOOL', 1),
				),
			),
		);
	}


	public function update_data()
	{
		return array(
			// ACP modules
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_GLOSS_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_GLOSS_TITLE',
				array(
					'module_basename'	=> '\lmdi\gloss\acp\gloss_module',
					'auth'			=> 'ext_lmdi/gloss && acl_a_board',
					'modes'			=> array('settings'),
				),
			)),

			// Configuration rows
			array('config.add', array('lmdi_glossary', 1)),
			array('config.add', array('lmdi_glossary_acp', 0)),
			array('config.add', array('lmdi_glossary_title', 0)),
			array('config.add', array('lmdi_glossary_usergroup', 0)),
			array('config.add', array('lmdi_glossary_admingroup', 0)),
			array('config.add', array('lmdi_glossary_pixels', 500)),
			array('config.add', array('lmdi_glossary_weight', 150)),

			// Modify collation setting of the glossary table
			array('custom', array(array(&$this, 'utf8_unicode_ci'))),

			// Insertion of dummy entries in the glossary table
			array('custom', array(array(&$this, 'insert_sample_data'))),

			// Add roles
			array('permission.role_add', array('ROLE_GLOSS_ADMIN', 'a_', 'ROLE_DESCRIPTION_GLOSS_ADMIN')),
			array('permission.role_add', array('ROLE_GLOSS_EDITOR', 'u_', 'ROLE_DESCRIPTION_GLOSS_EDITOR')),

			// Add permissions (global = true, local = false)
			array('permission.add', array('a_lmdi_glossary', true)),
			array('permission.add', array('u_lmdi_glossary', true)),

			// Assign permissions to the roles
			array('permission.permission_set', array('ROLE_GLOSS_ADMIN', 'a_lmdi_glossary', 'role')),
			array('permission.permission_set', array('ROLE_GLOSS_EDITOR', 'u_lmdi_glossary', 'role')),
		);
	}


	public function utf8_unicode_ci()
	{
		$sql = "alter table {$this->table_prefix}glossary convert to character set utf8 collate utf8_unicode_ci";
		$this->db->sql_query($sql);
	}


	public function insert_sample_data()
	{
		// Define sample data
		$sample_data = array(
				array(
					'variants' => 'test, tests, tested',
					'term' => 'Test',
					'description' => 'Test definition, etc.',
					'cat' => 'Noun',
					'ilinks' => 'trial',
					'elinks' =>'',
					'label' =>'',
					'picture' => 'nopict.jpg',
					'lang' => 'en',
				),
				array(
					'variants' => 'try, demo, trial',
					'term' => 'Trial',
					'description' => 'Second test definition, etc.',
					'cat' => 'Noun',
					'ilinks' => 'test',
					'elinks' =>'',
					'label' =>'',
					'picture' => 'nopict.jpg',
					'lang' => 'en',
				),
			);
		// Insert sample data
		$this->db->sql_multi_insert($this->table_prefix . 'glossary', $sample_data);
	}


	public function revert_schema()
	{
		$table = $this->table_prefix . 'glossary';
			return array(
				'drop_columns'	=> array(
					$table_prefix . 'users' => array(
						'lmdi_gloss',
					),
				),
				'drop_tables'   => array(
					$table,
				)
			);
	}

}

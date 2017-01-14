<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* @copyright (c) 2015-2017 Pierre Duhem - LMDI
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace lmdi\gloss\migrations;

class release_1_2 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'forums', 'ldmi_glossary');
	}

	static public function depends_on()
	{
		return array('\lmdi\gloss\migrations\release_1_1');
	}

	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'forums' => array('lmdi_glossary' => array('BOOL', 1),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'forums' => array('lmdi_glossary',),
			),
		);
	}

}

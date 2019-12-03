<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* @copyright (c) 2015-2019 Pierre Duhem - LMDI
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace lmdi\gloss\migrations;

class release_3 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return ($this->config['lmdi_glossary_version'] == 3000);
	}

	static public function depends_on()
	{
		return array('\lmdi\gloss\migrations\release_1_3');
	}

	public function update_schema()
	{
		return array(
			array('config.update' => array('lmdi_glossary_version', 3000)),
			array('custom', array (array(&$this, 'nopict_deletion'))),
		);
	}

	private function nopict_deletion()
	{
		$sql = 'UPDATE ' . $this->table_prefix . 'glossary SET picture = "" WHERE picture = "nopict.jpg"';
		$this->db->sql_query($sql);
	}

}

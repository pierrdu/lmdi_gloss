<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* @copyright (c) 2015-2016 Pierre Duhem - LMDI
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace lmdi\gloss\migrations;

class release_1_1 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['lmdi_glossary_tooltip']);
	}

	public static function depends_on()
	{
		return array('\lmdi\gloss\migrations\release_1');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('lmdi_glossary_tooltip', 50)),
		);
	}

}

<?php
/**
*
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2018 Pierre Duhem - LMDI
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace lmdi\gloss\acp;

class gloss_info
{
	public function module()
	{
		return array(
			'filename'	=> '\lmdi\gloss\acp\gloss_module',
			'title'		=> 'ACP_GLOSS_TITLE',
			'version'		=> '1.0.0',
			'modes'		=> array(
				'settings' => array('title' => 'ACP_GLOSS',
						'auth' => 'ext_lmdi/gloss',
						'cat' => array('ACP_GLOSS_TITLE')),
			),
		);
	}
}

<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* @copyright (c) 2015-2017 LMDI - Pierre Duhem
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace lmdi\gloss\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\cache\service */
	protected $cache;
	/* @var \phpbb\user */
	protected $user;
	/* @var \phpbb\db\driver\driver_interface */
	protected $db;
	/* @var \phpbb\template\template */
	protected $template;
	/* @var \phpbb\config\config */
	protected $config;
	/* @var \phpbb\controller\helper */
	protected $helper;
	protected $glossary_table;

	public function __construct(
		\phpbb\db\driver\driver_interface $db,
		\phpbb\config\config $config,
		\phpbb\controller\helper $helper,
		\phpbb\template\template $template,
		\phpbb\cache\service $cache,
		\phpbb\user $user,
		$glossary_table
		)
	{
		$this->db = $db;
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->cache = $cache;
		$this->user = $user;
		$this->glossary_table = $glossary_table;
	}

	static public function getSubscribedEvents ()
	{
	return array(
		'core.user_setup'				=> 'load_language_on_setup',
		'core.page_header'				=> 'build_url',
		'core.permissions'				=> 'add_permissions',
		'core.viewtopic_post_rowset_data'	=> 'glossary_insertion',
		);
	}

	public function load_language_on_setup($event)
	{
		// Initial reset of the module_display row in the module table
		if (!$this->config['lmdi_glossary_ucp'])
		{
			$sql  = "UPDATE " . MODULES_TABLE;
			$sql .= " SET module_display = 0 ";
			$sql .= "WHERE module_langname = 'UCP_GLOSS'";
			$this->db->sql_query($sql);
		}
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'lmdi/gloss',
			'lang_set' => 'gloss',
			);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function build_url ($event)
	{
		if (version_compare ($this->config['version'], '3.2.x', '<'))
		{
			$gloss_class = 0;
		}
		else
		{
			$gloss_class = 1;
		}
		$this->template->assign_vars(array(
			'U_GLOSSAIRE'	=> $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossaire')),
			'L_GLOSSAIRE'	=> $this->user->lang['LGLOSSAIRE'],
			'T_GLOSSAIRE'	=> $this->user->lang['TGLOSSAIRE'],
			'S_320'	=> $gloss_class,
		));
	}

	/**
	* Add custom permissions language variables
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function add_permissions($event)
	{
		$permissions = $event['permissions'];
		$permissions['u_lmdi_glossary'] = array('lang' => 'ACL_U_LMDI_GLOSSARY', 'cat' => 'misc');
		$permissions['a_lmdi_glossary'] = array('lang' => 'ACL_A_LMDI_GLOSSARY', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}

	// Event: core.viewtopic_post_rowset_data
	public function glossary_insertion($event)
	{
		static $enabled_forums = "";
		if (empty ($enabled_forums))
		{
			$enabled_forums = $this->cache->get('_gloss_forums');
			if (empty ($enabled_forums))
			{
				$this->rebuild_cache_forums ();
				$enabled_forums = $this->cache->get('_gloss_forums');
			}
		}
		if (!empty ($enabled_forums))
		{
			if ($this->user->data['lmdi_gloss'])
			{
				$rowset_data = $event['rowset_data'];
				$forum_id = $rowset_data['forum_id'];
				if (in_array ($forum_id, $enabled_forums))
				{
					$post_text = $rowset_data['post_text'];
					$post_text = $this->glossary_pass ($post_text);
					$rowset_data['post_text'] = $post_text;
					$event['rowset_data'] = $rowset_data;
				}
			}
		}
	}	// glossary_insertion


	function glossary_pass ($texte)
	{
		static $glossterms;
		if (!isset ($glossterms) || !is_array ($glossterms))
		{
			$this->compute_glossary_list();
			$glossterms = $this->cache->get('_glossterms');
		}
		if (sizeof($glossterms))
		{
			$rech = $glossterms['rech'];
			$remp = $glossterms['remp'];
			preg_match_all ('#[][><][^][><]*|[^][><]+#', $texte, $matches);
			$parts = $matches[0];
			if (empty($parts))
			{
				return '';
			}
			foreach ($parts as $index => $part)
			{
				// Acronyms
				if (strstr($part, '<acronym'))
				{
					$acro = true;
				}
				if (!empty($acro) && strstr($part, '</acronym'))
				{
					$acro = false;
				}
				// Code
				if (strstr($part, '[code'))
				{
					$code = true;
				}
				if (!empty($code) && strstr($part, '[/code'))
				{
					$code = false;
				}
				// Images - Pictures
				if (strstr($part, '[img'))
				{
					$img = true;
				}
				if (!empty($img) && strstr($part, '[/img'))
				{
					$img = false;
				}
				// Liens <a> - <a> links
				if (strstr($part, '<a '))
				{
					$link = true;
				}
				if (!empty($link) && strstr($part, '</a'))
				{
					$link = false;
				}
				// Liens [url] - [url] links
				if (strstr($part, '[url'))
				{
					$link = true;
				}
				if (!empty($link) && strstr($part, '[/url'))
				{
					$link = false;
				}
				// Script
				if (strstr($part, '<script '))
				{
					$script = true;
				}
				if (!empty($script) && strstr($part, '</script'))
				{
					$script = false;
				}
				if (!($part{0} == '<') && !($part{0} == '[') &&
					empty($acro) && empty($img) && empty($code) && empty($link) && empty($script))
				{
					$part = preg_replace ($rech, $remp, $part);
					$parts[$index] = $part;
				}
			}
			unset ($part);
			return implode ("", $parts);
		}
	// Totally empty glossary, we must at least return the raw text.
	else
	{
		return ($texte);
	}
	}	// glossary_pass


	/*	Production of the term list and the replacement list, in an array named glossterms.
		The replacement string follows this model:
		<acronym class='id302' title=''>$1</acronym>
		The title element can contain the first 50 characters of description (see ACP).
		Production de la liste des termes et calcul d'une chaîne de remplacement.
		Les éléments sont placés dans le tableau glossterms. Ce tableau contient pour
		chaque rubrique un élément rech qui est la chaîne à rechercher et un
		élément remp qui est la chaîne de remplacement :
		<acronym class='id302' title=''>$1</acronym>
		L'élément 'title' peut contenir les 50 premiers caractères de la chaîne de
		description (voir le panneau d'administration).
		*/
	function compute_glossary_list()
	{
		$glossterms = $this->cache->get('_glossterms');
		if ($glossterms === false)
		{
			$sql  = "SELECT * FROM $this->glossary_table ";
			// WHERE lang = '" . $user->data['user_lang'] . "'
			$sql .= "ORDER BY LENGTH(TRIM(variants)) DESC";
			$result = $this->db->sql_query($sql);
			$glossterms = array();
			$title = $this->config['lmdi_glossary_title'];
			while ($row = $this->db->sql_fetchrow($result))
			{
				$variants = explode (",", $row['variants']);
				$term_id  = $row['term_id'];
				if ($title)
				{
					$desc = trim ($row['description']);
					if (strlen ($desc) > 500)
					{
						$desc = mb_substr ($desc, 0, 500);
					}
				}
				else
				{
					$desc = '';
				}
				$cnt = count ($variants);
				$done = array ();
				for ($i = 0; $i < $cnt; $i++)
				{
					$variant = trim ($variants[$i]);
					// comma at end of variants => empty string
					if (!strlen ($variant))
					{
						continue;
					}
					$variant = strtolower ($variant);
					$variant = preg_quote ($variant, '/');
					if (!in_array ($variant, $done))
					{
						$done[] = $variant;
						$remp  = "<acronym class=\"id{$term_id}\" title=\"$desc\">$1</acronym>";
						$firstspace = '/\b(';
						$lastspace = ')\b/ui';	// PCRE - u = UTF-8 - i = case insensitive
						$rech = $firstspace . $variant . $lastspace;
						$glossterms['rech'][] = $rech;
						$glossterms['remp'][] = $remp;
					}
				}
			}
			$this->db->sql_freeresult($result);
			$this->cache->put('_glossterms', $glossterms, 86400); // 24 h
		}
	}	// compute_glossary_list


	function rebuild_cache_forums ()
	{
		$sql = 'SELECT * 
				FROM ' . FORUMS_TABLE . '
				WHERE lmdi_glossary = 1';
		$result = $this->db->sql_query ($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$forum_list[] = $row['forum_id'];
		}
		$this->db->sql_freeresult($result);
		$this->cache->put('_gloss_forums', $forum_list, 86400);
	}	// rebuild_cache_forums

}

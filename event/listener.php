<?php
/**
*
* @package phpBB Extension - LMDI Glossary extension
* @copyright (c) 2015-2018 LMDI - Pierre Duhem
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
	protected $cache;
	protected $user;
	protected $db;
	protected $template;
	protected $config;
	protected $helper;
	protected $request;
	protected $glossary_table;
	protected $tid;
	protected $gloss;

	public function __construct(
		\phpbb\db\driver\driver_interface $db,
		\phpbb\config\config $config,
		\phpbb\controller\helper $helper,
		\phpbb\template\template $template,
		\phpbb\cache\service $cache,
		\phpbb\user $user,
		\phpbb\request\request $request,
		$glossary_table
		)
	{
		$this->db = $db;
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->cache = $cache;
		$this->user = $user;
		$this->request = $request;
		$this->glossary_table = $glossary_table;
	}


	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'lmdi/gloss',
			'lang_set' => 'gloss',
			);
		$event['lang_set_ext'] = $lang_set_ext;
	}


	public function build_url($event)
	{
		if (version_compare($this->config['version'], '3.2.x', '<'))
		{
			$gloss_320 = 0;
		}
		else
		{
			$gloss_320 = 1;
		}
		$this->template->assign_vars(array(
			'U_GLOSSAIRE'	=> $this->helper->route('lmdi_gloss_controller', array('mode' => 'glossaire')),
			'L_GLOSSAIRE'	=> $this->user->lang['LGLOSSAIRE'],
			'T_GLOSSAIRE'	=> $this->user->lang['TGLOSSAIRE'],
			'S_320'		=> $gloss_320,
		));
	}


	public function add_permissions($event)
	{
		$permissions = $event['permissions'];
		$permissions['u_lmdi_glossary'] = array('lang' => 'ACL_U_LMDI_GLOSSARY', 'cat' => 'misc');
		$permissions['a_lmdi_glossary'] = array('lang' => 'ACL_A_LMDI_GLOSSARY', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}


	public static function getSubscribedEvents()
	{
		return array(
			'core.user_setup'				=> 'load_language_on_setup',
			'core.page_header'				=> 'build_url',
			'core.permissions'				=> 'add_permissions',
			'core.viewtopic_post_rowset_data'	=> 'glossary_insertion',
			// For version 3.2.x
			'core.text_formatter_s9e_render_before' => 's9e_before',
			'core.text_formatter_s9e_render_after' => 's9e_after',
			);
	}


	public function s9e_before ($event)
	{
		$xml = $event['xml'];
		// Texts tagged with <t> are dumped as is. Texts with <r> are so-called raw
		// and are parsed. We have to protect ourselves against this parser.
		if ($this->config['lmdi_glossary_acp'] && substr($xml, 0, 3) === '<r>')
		{
			$this->tid = $this->request->variable('t', 0);
			$pos1 = strpos($xml, '<lmdigloss');
			while ($pos1 !== false)
			{
				$pos2 = strpos($xml, '</lmdigloss>', 0);
				$lg = ($pos2 - $pos1) + 12;
				$item = substr($xml, $pos1, $lg);
				$pos3 = strpos($item, 'class="id');
				$pos3 += 9;
				$num = substr($item, $pos3, $pos3 + 6);
				$pos4 = strpos($num, '"');
				$num = (int) substr($num, 0, $pos4);
				if (!isset($this->gloss[$num]))
				{
					$this->gloss[$num] = $item;
				}
				else
				{
					$slot = $this->gloss[$num];
					while ($slot !== $item)
					{
						$num += 10000;
						if (!isset($this->gloss[$num]))
						{
							$this->gloss[$num] = $item;
							break;
						}
						else
						{
							$slot = $this->gloss[$num];
							if ($slot === $item)
							{
								break;
							}
						}
					}
				}
				$remp = "lmdigloss*($num)*lmdigloss";
				$xml = str_replace($item, $remp, $xml);
				$pos1 = strpos($xml, '<lmdigloss', 0);
			}
		$event['xml'] = $xml;
		}
	} // s9e_before


	public function s9e_after($event)
	{
		if ($this->tid == $this->request->variable('t', 0))
		{
			$html = $event['html'];
			while (1)
			{
				$pos1 = strpos($html, 'lmdigloss*(');
				if ($pos1 === false)
				{
					break;
				}
				else
				{
					$pos2 = strpos($html, ')*lmdigloss');
					$lg = ($pos2 - $pos1) + 11;
					$item = substr($html, $pos1, $lg);
					$pos3 = strpos($item, '(');
					$pos4 = strpos($item, ')');
					$lg = ($pos4) - ($pos3 + 1);
					$num = (int) substr($item, $pos3 + 1, $lg);
					$tag = $this->gloss[$num];
					$html = str_replace($item, $tag, $html);
				}
			}
			$event['html'] = $html;
		}
	} // s9e_after


	public function glossary_insertion($event)
	{
		static $enabled_forums;
		if ($this->config['lmdi_glossary_acp'])
		{
			if (empty($enabled_forums))
			{
				$enabled_forums = $this->cache->get('_gloss_forums');
				if (empty($enabled_forums))
				{
					$this->rebuild_cache_forums();
					$enabled_forums = $this->cache->get('_gloss_forums');
				}
			}
			if (!empty($enabled_forums))
			{
				$rowset_data = $event['rowset_data'];
				$forum_id = $rowset_data['forum_id'];
				if (in_array($forum_id, $enabled_forums))
				{
					$post_text = $rowset_data['post_text'];
					$post_text = $this->glossary_pass($post_text);
					$rowset_data['post_text'] = $post_text;
					$event['rowset_data'] = $rowset_data;
				}
			}
		}
	}	// glossary_insertion


	private function glossary_pass($texte)
	{
		static $glossterms;
		if (!isset($glossterms) || !is_array($glossterms))
		{
			$this->compute_glossary_list();
			$glossterms = $this->cache->get('_glossterms');
		}
		if (sizeof($glossterms))
		{
			$code = $quote = $img = $link = $script = false;
			$rech = $glossterms['rech'];
			$remp = $glossterms['remp'];
			preg_match_all('#[][><][^][><]*|[^][><]+#', $texte, $matches);
			$parts = $matches[0];
			if (empty($parts))
			{
				return '';
			}
			foreach ($parts as $index => $part)
			{
				// Code
				if (strstr($part, '[code'))
				{
					$code = true;
				}
				if (!empty($code) && strstr($part, '[/code'))
				{
					$code = false;
				}
				// quote 3.2.0
				if (strstr($part, '[quote'))
				{
					$quote = true;
				}
				if (!empty($quote) && strstr($part, '[/quote'))
				{
					$quote = false;
				}
				// Pictures
				if (strstr($part, '[img'))
				{
					$img = true;
				}
				if (!empty($img) && strstr($part, '[/img'))
				{
					$img = false;
				}
				// <a> - <a> links
				if (strstr($part, '<a '))
				{
					$link = true;
				}
				if (!empty($link) && strstr($part, '</a'))
				{
					$link = false;
				}
				// [url] - [url] links
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
					empty($code) && empty($quote) &&
					empty($img) && empty($link) && empty($script))
				{
					$part2 = preg_replace($rech, $remp, $part);
					$parts[$index] = $part2;
				}
			}
			unset($part);
			return implode("", $parts);
		}
	// Totally empty glossary, we must at least return the raw text.
	else
	{
		return ($texte);
	}
	}	// glossary_pass

	/*	Production of the term list and the replacement list, in an array named glossterms.
		The replacement string follows this model:
		<lmdigloss class='id302' title=''>$1</lmdigloss>
		The title element can contain the first 50 characters of description text (see ACP).
		*/
	private function compute_glossary_list()
	{
		$glossterms = $this->cache->get('_glossterms');
		if ($glossterms === false)
		{
			$sql  = "SELECT * FROM $this->glossary_table 
				ORDER BY LENGTH(TRIM(variants)) DESC"; // To try longest variants first
			$result = $this->db->sql_query($sql);
			$glossterms = array();
			while ($row = $this->db->sql_fetchrow($result))
			{
				$variants = explode(",", $row['variants']);
				$term_id  = $row['term_id'];
				if ($this->config['lmdi_glossary_title'])
				{
					$desc = trim($row['description']);
					if (mb_strlen($desc) > 500)
					{
						$desc = mb_substr($desc, 0, 500);
					}
					$str_title = " title=\"$desc\"";
				}
				else
				{
					$str_title = '';
				}
				$cnt = count($variants);
				$done = array();
				for ($i = 0; $i < $cnt; $i++)
				{
					$variant = trim($variants[$i]);
					// If the user puts a comma at end of variants => empty string
					if (!strlen($variant))
					{
						continue;
					}
					$variant = strtolower($variant);
					$variant = preg_quote($variant, '/');
					if (!in_array($variant, $done))
					{
						$done[] = $variant;
						$remp  = "<lmdigloss class=\"id{$term_id}\"$str_title>$1</lmdigloss>";
						// $glossterms['rech'][] = $variant;
						$begin = '/\b(';
						$end = ')\b/ui'; // PCRE - u = UTF-8 - i = case insensitive
						$rech = $begin . $variant . $end;
						$glossterms['rech'][] = $rech;
						$glossterms['remp'][] = $remp;
					}
				}
			}
			$this->db->sql_freeresult($result);
			$this->cache->put('_glossterms', $glossterms, 86400); // 24 h
		}
	}	// compute_glossary_list


	private function rebuild_cache_forums()
	{
		$forum_list = array();
		$sql = 'SELECT * 
				FROM ' . FORUMS_TABLE . '
				WHERE lmdi_glossary = 1';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$forum_list[] = $row['forum_id'];
		}
		$this->db->sql_freeresult($result);
		$this->cache->put('_gloss_forums', $forum_list, 86400);
	}	// rebuild_cache_forums


}

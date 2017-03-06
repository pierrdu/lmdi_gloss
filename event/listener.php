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
		'core.viewtopic_modify_post_data'	=> 'examination',
		'core.viewtopic_modify_post_action_conditions'	=> 'examination2',
		'core.viewtopic_modify_post_row'	=> 'examination3',
		'core.viewtopic_post_row_after'	=> 'examination4',
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
			'S_320'	=> $gloss_320,
		));
	}


	/**
	* Add custom permissions language variables
	*
	*/
	public function add_permissions($event)
	{
		$permissions = $event['permissions'];
		$permissions['u_lmdi_glossary'] = array('lang' => 'ACL_U_LMDI_GLOSSARY', 'cat' => 'misc');
		$permissions['a_lmdi_glossary'] = array('lang' => 'ACL_A_LMDI_GLOSSARY', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}


	/**
	* Event to modify the post, poster and attachment data before assigning the posts
	*
	* @event core.viewtopic_modify_post_data
	* @var	int		forum_id	Forum ID
	* @var	int		topic_id	Topic ID
	* @var	array	topic_data	Array with topic data
	* @var	array	post_list	Array with post_ids we are going to display
	* @var	array	rowset		Array with post_id => post data
	* @var	array	user_cache	Array with prepared user data
	* @var	int		start		Pagination information
	* @var	int		sort_days	Display posts of previous x days
	* @var	string	sort_key	Key the posts are sorted by
	* @var	string	sort_dir	Direction the posts are sorted by
	* @var	bool	display_notice				Shall we display a notice instead of attachments
	* @var	bool	has_approved_attachments	Does the topic have approved attachments
	* @var	array	attachments					List of attachments post_id => array of attachments
	* @var	array	permanently_banned_users	List of permanently banned users
	* @var	array	can_receive_pm_list			Array with posters that can receive pms
	* @since 3.1.0-RC3
	* Line 1590 & ss.
	*/
	public function examination ($event)
	{
		$rowset = $event['rowset'];
		// var_dump ($rowset);
	}	// examination


	/**
	* This event allows you to modify the conditions for the "can edit post" and "can delete post" checks
	*
	* @event core.viewtopic_modify_post_action_conditions
	* @var	array	row			Array with post data
	* @var	array	topic_data	Array with topic data
	* @var	bool	force_edit_allowed		Allow the user to edit the post (all permissions and conditions are ignored)
	* @var	bool	s_cannot_edit			User can not edit the post because it's not his
	* @var	bool	s_cannot_edit_locked	User can not edit the post because it's locked
	* @var	bool	s_cannot_edit_time		User can not edit the post because edit_time has passed
	* @var	bool	force_delete_allowed		Allow the user to delete the post (all permissions and conditions are ignored)
	* @var	bool	s_cannot_delete				User can not delete the post because it's not his
	* @var	bool	s_cannot_delete_lastpost	User can not delete the post because it's not the last post of the topic
	* @var	bool	s_cannot_delete_locked		User can not delete the post because it's locked
	* @var	bool	s_cannot_delete_time		User can not delete the post because edit_time has passed
	* @since 3.1.0-b4
	* Line 1835 & ss.
	*/
	public function examination2 ($event)
	{
		$row = $event['row'];
		// var_dump ($row);
	}	// examination


	/**
	* Modify the posts template block
	*
	* @event core.viewtopic_modify_post_row
	* @var	int		start				Start item of this page
	* @var	int		current_row_number	Number of the post on this page
	* @var	int		end					Number of posts on this page
	* @var	int		total_posts			Total posts count
	* @var	int		poster_id			Post author id
	* @var	array	row					Array with original post and user data
	* @var	array	cp_row				Custom profile field data of the poster
	* @var	array	attachments			List of attachments
	* @var	array	user_poster_data	Poster's data from user cache
	* @var	array	post_row			Template block array of the post
	* @var	array	topic_data			Array with topic data
	* @since 3.1.0-a1
	* @change 3.1.0-a3 Added vars start, current_row_number, end, attachments
	* @change 3.1.0-b3 Added topic_data array, total_posts
	* @change 3.1.0-RC3 Added poster_id
	* Line 2002 & ss.
	*/
	public function examination3 ($event)
	{
		$row = $event['row'];
		// var_dump ($row);
	}	// examination


/**
	* Event after the post data has been assigned to the template
	*
	* @event core.viewtopic_post_row_after
	* @var	int		start				Start item of this page
	* @var	int		current_row_number	Number of the post on this page
	* @var	int		end					Number of posts on this page
	* @var	int		total_posts			Total posts count
	* @var	array	row					Array with original post and user data
	* @var	array	cp_row				Custom profile field data of the poster
	* @var	array	attachments			List of attachments
	* @var	array	user_poster_data	Poster's data from user cache
	* @var	array	post_row			Template block array of the post
	* @var	array	topic_data			Array with topic data
	* @since 3.1.0-a3
	* @change 3.1.0-b3 Added topic_data array, total_posts
	* Line 2103 & ss.
	*/
	public function examination4 ($event)
	{
		$row = $event['row'];
		var_dump ($row);
	}	// examination


	/**
	* Modify the post rowset containing data to be displayed with posts
	*
	* @event core.viewtopic_post_rowset_data
	* @var	array	rowset_data	Array with the rowset data for this post
	* @var	array	row			Array with original user and post data
	* @since 3.1.0-a1
	*/
	// Line 1292 of viewtopic.php
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
				// var_dump ($rowset_data);
				// $row = $event['row'];
				// var_dump ($row);
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
			$acro = $code = $quote = $img = $link = $script = false;
			$rech = $glossterms['rech'];
			$remp = $glossterms['remp'];
			preg_match_all ('#[][><][^][><]*|[^][><]+#', $texte, $matches);
			$parts = $matches[0];
			if (empty($parts))
			{
				return '';
			}
			// var_dump ("Nouvelle passe");
			// var_dump ($parts);
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
				// quote 3.2.0
				if (strstr($part, '[quote'))
				{
					$quote = true;
				}
				if (!empty($quote) && strstr($part, '[/quote'))
				{
					$quote = false;
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
					empty($acro) && empty($code) && empty($quote) &&
					empty($img) && empty($link) && empty($script))
				{
					$part2 = preg_replace ($rech, $remp, $part);
					$parts[$index] = $part2;
					/*
					if ($part != $part2)
					{
						var_dump ($index);
						var_dump ($part);
						var_dump ($part2);
					}
					*/
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
					if (mb_strlen ($desc) > 500)
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

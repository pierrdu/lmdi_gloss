<?php
/**
* gloss.php
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2016 LMDI - Pierre Duhem
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge ($lang, array(
	'LGLOSSAIRE'			=> 'Glossaire',
	'TGLOSSAIRE'			=> 'Glossaire',
// UCP
	'UCP_GLOSS_TITLE'	=> 'Glossaire',
	'UCP_GLOSS'		=> 'Page principale',
	'UCP_CONFIG_SAVED'	=> 'La configuration a bien été enregistrée.<br /><br />%sCliquez ici pour revenir à la page précédente.%s',
	'UCP_ENABLE'		=> 'Validation de la fonction de glossaire',
	'UCP_ENABLE_EXPLAIN' => 'Le glossaire affiche une explication (et éventuellement une illustration) lorsque vous cliquez sur les termes techniques dans les sujets du forum.',

// Installation
	'ROLE_GLOSS_ADMIN'	=> 'Administrateurs du glossaire',
	'ROLE_GLOSS_EDITOR'	=> 'Éditeurs du glossaire',
	'ROLE_DESCRIPTION_GLOSS_ADMIN' => 'Modèle des administrateurs chargés de gérer le glossaire et ses éditeurs',
	'ROLE_DESCRIPTION_GLOSS_EDITOR' => 'Modèle des utilisateurs chargés de l’édition du glossaire',
	'GROUP_GLOSS_ADMIN'				=> 'Administrateurs du glossaire',
	// 'G_GROUP_GLOSS_ADMIN'				=> 'Administrateurs du glossaire',
	'GROUP_DESCRIPTION_GLOSS_ADMIN'	=> 'Groupe des administrateurs du glossaire',
	'GROUP_GLOSS_EDITOR'			=> 'Éditeurs du glossaire',
	// 'G_GROUP_GLOSS_EDITOR'			=> 'Éditeurs du glossaire',
	'GROUP_DESCRIPTION_GLOSS_EDITOR'	=> 'Groupe des éditeurs du glossaire',

// ACP
	'ACP_GLOSS_TITLE'	=> 'Glossaire',
	'ACP_GLOSS'	=> 'Paramétrage de l’extension',
	'ALLOW_FEATURE'        => 'Validation de la fonction de glossaire',
	'ALLOW_FEATURE_EXPLAIN'        => 'Vous pouvez valider ou inhiber la fonction au niveau du forum. Si vous validez, l’utilisateur pourra s’il le souhaite inhiber l’affichage des termes du glossaire dans les messages du forum (à partir du panneau de l’utilisateur).',
	'ALLOW_TITLE'        => 'Validation des infobulles',
	'ALLOW_TITLE_EXPLAIN'        => 'Vous pouvez valider ou inhiber l’affichage d’une infobulle lorsque le curseur passe au-dessus du terme dans les messages du forum.',
	'CREATE_UGROUP'		=> 'Création d’un groupe d’utilisateurs',
	'CREATE_UGROUP_EXPLAIN'	=> 'Vous pouvez créer un groupe d’utilisateurs auquel vous attribuerez le rôle d’éditeur des rubriques du glossaire qui a été créé lors de l’installation de l’extension. Vous pouvez ensuite placer dans ce groupe les utilisateurs chargés de cette tâche.',
	'CREATE_AGROUP'		=> 'Création d’un groupe d’administrateurs',
	'CREATE_AGROUP_EXPLAIN'	=> 'Vous pouvez créer un groupe pour gérer les administrateurs du glossaire. Vous pouvez ensuite y ajouter les administrateurs sélectionnés.',
	'LANGUAGE'		=> 'Langue par défaut',
	'LANGUAGE_EXPLAIN'	=> 'Code de langue (par défaut langue du forum) qui est enregistré si vous ne spécifiez pas une autre langue dans le formulaire de saisie.',
	'GLOSS_PIXELS'			=> 'Dimensions des images en pixels',
	'GLOSS_PIXELS_EXPLAIN'	=> 'Indiquez ici la taille maximale de l’image en pixels (sur le plus grand côté).',
	'POIDS'			=> 'Poids de l’image',
	'POIDS_EXPLAIN'	=> 'Indiquez ici le poids maximal de l’image téléchargée (en ko).',
	'TITLE_LENGTH'		=> 'Longueur du texte de l’infobulle',
	'TITLE_LENGTH_EXPLAIN'	=>'Indiquez ici le nombre de caractères auquel le texte de l’infobulle doit être tronqué si la description est trop longue.',
	'ACP_GLOSS_FORUMS'	=> 'Sélection des forums',
	'ACP_GLOSS_ENABLED'	=> 'Valider le glossaire dans',
	'ACP_GLOSS_CHECKALL'				=> 'Sélection/désélection',
	'ACP_GLOSS_CHECKALL_EXPLAIN'			=> 'Vous pouvez sélectionner/déselectionner tous les forums en une seule fois.',
	'ACP_GLOSS_ALL_FORUMS'				=> 'Tous les forums',


));

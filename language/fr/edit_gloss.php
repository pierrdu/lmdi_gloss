<?php
/**
* edit_gloss.php
* @package phpBB Extension - LMDI Glossary
* @copyright (c) 2015-2019 LMDI - Pierre Duhem
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

$lang = array_merge($lang, array(
// Static Glossary page
	'ILLUSTRATION'	=>  "<p>Certains termes sont illustrés.<br>Il existe dans ce cas un lien cliquable au bout de la ligne, cliquez dessus pour afficher l'illustration.<br>Sur la page de visualisation, cliquez sur l'image pour revenir.</p>",
	'GLOSS_DISPLAY'	=> 'Afficher',
	'GLOSS_CLICK'		=> 'Cliquez sur l’image pour revenir à la page précédente.',
	'GLOSS_VIEW'		=> 'Afficheur du glossaire',
	'GLOSS_ILINKS'		=> 'Voir aussi&nbsp;: ',
	'GLOSS_ELINKS'		=> 'Lien externe&nbsp;: ',
	'BACKTOP'			=> 'Haut',
// Glossary edition page
	'GLOSS_EDIT'	=>'Édition d’une fiche du glossaire',
	'GLOSS_CREAT'	=>'Création d’une fiche du glossaire',
	'GLOSS_VARIANTS' => 'Termes à rechercher',
	'GLOSS_VARIANTS_EX' => 'Un ou plusieurs termes, séparés par des virgules.',
	'GLOSS_TERM'	=> 'Terme affiché',
	'GLOSS_TERM_EX' => 'Terme utilisé comme titre dans la fenêtre.',
	'GLOSS_DESC'	=> 'Définition du terme',
	'GLOSS_PICT'	=> 'Illustration',
	'GLOSS_REGIS'	=> 'Enregistrer',
	'GLOSS_SUPPR'	=> 'Supprimer',
// Glossary form (édition and creation)
	'GLOSS_ADMINISTRATION'	=> 'Page d’administration du glossaire',
	'GLOSS_EDITION'	=> 'Page d’édition du glossaire',
	'GLOSS_ED_TERM'	=> 'Terme',
	'GLOSS_ED_DEF'		=> 'Définition',
	'GLOSS_ED_CAT'		=> 'Catégorie',
	'GLOSS_ED_CATEX'	=> 'Par exemple grammaticale.',
	'GLOSS_ED_ILINKS'	=> 'Liens internes',
	'GLOSS_ED_ILEXP'	=> 'Termes du glossaire vers lesquels des liens doivent être créés, utilisez la virgule comme séparateur.',
	'GLOSS_ED_ELINKS'	=> 'Lien externe',
	'GLOSS_ED_ELEXP'	=> 'Lien vers un sujet ou une page, etc. Syntaxe&nbsp;: URL complète.',
	'GLOSS_ED_LABEL'	=> 'Étiquette',
	'GLOSS_ED_LABEX'	=> 'Chaîne d’identification du lien externe.',
	'GLOSS_ED_PICT'	=> 'Illustration',
	'GLOSS_ED_PIEXPL'	=> 'Nom du fichier (jpg, jpeg, gif ou png). Téléchargement dans le dossier store/lmdi/gloss.',
	'GLOSS_ED_UPLOAD'	=> 'Téléchargement&nbsp;:',
	'GLOSS_ED_NOUP'	=> 'Pas de fichier à télécharger',
	'GLOSS_ED_REUSE'	=> 'Réutilisation du fichier',
	'GLOSS_ED_EXISTE'	=> 'Fichier enregistré',
	'GLOSS_ED_ACT'		=> 'Action',
	'GLOSS_ED_EXPL'	=> '<p>Un lien d’édition existe à l’extrémité de chaque ligne pour apporter des modifications.<br />%s<b>Cliquez ici</b>%s pour créer une nouvelle rubrique.</p>',
	'GLOSS_ED_EDIT'	=> 'Éditer',
	'GLOSS_LANG'		=> 'Langue',
	'GLOSS_ED_DOUBLON'	=> 'Le terme « %s » existe déjà.<br />%s<b>Cliquez ici</b>%s pour revenir à la page d’édition.',
	'GLOSS_ED_SAVE'	=> 'Le terme « %s » a bien été enregistré.<br />%s<b>Cliquez ici</b>%s pour revenir à la page d’administration.',
	'GLOSS_ED_DELETE'	=> 'Le terme « %s » a bien été supprimé.<br />%s<b>Cliquez ici</b>%s pour revenir à la page d’administration.',
	// Upload process
	'LMDI_GLOSS_NOFILE'	=> 'Aucun fichier n’a été sélectionné pour le téléchargement.',
	'LMDI_GLOSS_DISALLOWED_CONTENT'	=> 'Le téléchargement a été refusé parce que son contenu n’est pas autorisé.',
	'LMDI_GLOSS_DISALLOWED_EXTENSION'	=> 'Le téléchargement a été refusé parce que l’extension <strong>%s</strong> n’est pas autorisée.',
	'LMDI_GLOSS_EMPTY_FILEUPLOAD'		=> 'Le fichier téléchargé est vide.',
	'LMDI_GLOSS_EMPTY_REMOTE_DATA'	=> 'Le téléchargement a échoué parce que les données distantes semblent être invalides ou corrompues.',
	'LMDI_GLOSS_IMAGE_FILETYPE_MISMATCH'	=> 'Le type de fichie est incorrect : l’extension attendue était %1$s mais l’extension %2$s a été trouvée.',
	'LMDI_GLOSS_INVALID_FILENAME'		=> '%s est un nom de fichier invalide.',
	'LMDI_GLOSS_NOT_UPLOADED'		=> 'Aucun fichier n’a été téléchargé.',
	'LMDI_GLOSS_PARTIAL_UPLOAD'		=> 'Le fichier n’a été que partiellement téléchargé.',
	'LMDI_GLOSS_PHP_SIZE_NA'			=> 'La taille du fichier est trop élevée.<br />La taille maximale réglée dans php.ini n’a pas pu être déterminée.',
	'LMDI_GLOSS_PHP_SIZE_OVERRUN'		=> 'La taille du fichier est trop élevée. La taille maximale de téléchargement autorisée est de %d Mo.<br />Notez que ce paramètre est inscrit dans php.ini et ne peut pas être dépassé.',
	'LMDI_GLOSS_REMOTE_UPLOAD_TIMEOUT'	=> 'Le fichier n’a pas pu être téléchargé à cause d’une temporisation de la demande.',
	'LMDI_GLOSS_UNABLE_GET_IMAGE_SIZE'	=> 'Impossible de déterminer les dimensions du fichier.',
	'LMDI_GLOSS_WRONG_FILESIZE'		=> 'La taille du fichier doit être inférieure à %1d ko.',
	'LMDI_GLOSS_WRONG_SIZE'			=> 'Le fichier téléchargé a une largeur de %5$s et une hauteur de %6$s.<br>Les images doivent faire au moins %1$s de large et %2$s de haut, mais au plus %3$s de large et %4$s de haut.',
	'LMDI_CLICK_BACK'				=> '<a href="javascript:history.go(-1);"><b>Cliquez ici</b></a> pour revenir au formulaire d’édition.',
));

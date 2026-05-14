<?php
declare(strict_types=1);

namespace Plugins\MagixTextBlock\src;

use App\Backend\Controller\BaseController;
use Plugins\MagixTextBlock\db\TextBlockAdminDb;
use Magepattern\Component\HTTP\Request;
use Magepattern\Component\Tool\FormTool;
use Magepattern\Component\Tool\SmartyTool;
use App\Component\Cache\CacheManager;

class BackendController extends BaseController
{
    public function run(): void
    {
        SmartyTool::addTemplateDir('admin', ROOT_DIR . 'plugins' . DS . 'MagixTextBlock' . DS . 'views' . DS . 'admin');

        $action = $_GET['action'] ?? 'index';

        //  Route de sauvegarde (Création / Mise à jour via AJAX)
        if ($action === 'saveBlock' && Request::isMethod('POST')) {
            $this->processSaveBlock();
            return;
        }

        // Le routeur natif va détecter 'delete' et appeler la méthode associée
        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            $this->index();
        }
    }

    /**
     * Page d'accueil : Liste des blocs
     */
    private function index(): void
    {
        $db = new TextBlockAdminDb();
        $idLangDefault = (int)($this->defaultLang['id_lang'] ?? 1);

        // 1. Récupération des données brutes
        $blocksList = $db->getBlocksList($idLangDefault);

        // 2. Formatage des données en TEXTE BRUT uniquement
        if (!empty($blocksList)) {
            foreach ($blocksList as &$block) {
                // Le contexte brut
                $block['context_badge'] = $block['context'];

                // La balise Smarty brute, prête à copier
                $block['alias_smarty'] = '{textblock alias="' . $block['alias'] . '"}';

                // Nettoyage et troncature du texte
                $cleanText = strip_tags($block['content_tb'] ?? '');
                $block['content_preview'] = mb_strlen($cleanText) > 60 ? mb_substr($cleanText, 0, 60) . '...' : ($cleanText ?: 'Vide');
            }
        }

        // 3. Configuration des colonnes
        $targetColumns = ['id_tb', 'context_badge', 'alias_smarty', 'content_preview'];

        $rawScheme = [
            ['column' => 'id_tb', 'type' => 'int'],
            ['column' => 'context_badge', 'type' => 'varchar(255)'],
            ['column' => 'alias_smarty', 'type' => 'varchar(255)'],
            ['column' => 'content_preview', 'type' => 'text']
        ];

        //  C'est ICI qu'on applique le design via la clé 'class' !
        // Ces classes Bootstrap seront ajoutées directement sur les balises <td>
        $associations = [
            'context_badge'   => ['title' => 'Contexte', 'type' => 'text', 'class' => 'text-uppercase text-secondary fw-bold ps-4'],
            'alias_smarty'    => ['title' => 'Alias (Variable Smarty)', 'type' => 'text', 'class' => 'text-primary fw-bold font-monospace'],
            'content_preview' => ['title' => 'Extrait du contenu', 'type' => 'text', 'class' => 'text-muted small']
        ];

        // 4. Exécution
        $this->getScheme($rawScheme, $targetColumns, $associations);
        $this->getItems('textblocks', $blocksList, true);

        // 5. Assignation
        $this->view->assign([
            'idcolumn'   => 'id_tb',
            'controller' => 'MagixTextBlock',
            'hashtoken'  => $this->session->getToken(),
            'url_token'  => urlencode($this->session->getToken()),
            'sortable'   => false,
            'checkbox'   => true,
            'edit'       => true,
            'dlt'        => true
        ]);

        $this->view->display('index.tpl');
    }

    /**
     * Page : Ajouter un bloc
     */
    private function add(): void
    {
        $db = new TextBlockAdminDb();
        $langs = $db->fetchLanguages();

        $this->view->assign([
            'langs'     => $langs,
            'hashtoken' => $this->session->getToken()
        ]);

        $this->view->display('add.tpl');
    }

    /**
     * Page : Éditer un bloc
     */
    private function edit(): void
    {
        $idTb = (int)($_GET['edit'] ?? 0);

        //  FIX : Utilisation du header natif comme dans le ProductController
        if ($idTb === 0) {
            header('Location: index.php?controller=MagixTextBlock');
            exit;
        }

        $db = new TextBlockAdminDb();
        $langs = $db->fetchLanguages();
        $blockData = $db->getBlockFull($idTb);

        //  FIX : Redirection si l'ID n'existe pas en DB
        if (!$blockData) {
            header('Location: index.php?controller=MagixTextBlock');
            exit;
        }

        $this->view->assign([
            'langs'     => $langs,
            'block'     => $blockData,
            'hashtoken' => $this->session->getToken()
        ]);

        $this->view->display('edit.tpl');
    }

    /**
     * Traitement AJAX de la sauvegarde
     */
    private function processSaveBlock(): void
    {
        $token = $_POST['hashtoken'] ?? '';
        if (!$this->session->validateToken($token)) {
            $this->jsonResponse(false, 'Session expirée.');
        }

        $idTb = (int)($_POST['id_tb'] ?? 0);
        $db = new TextBlockAdminDb();

        //  Nettoyage strict de l'alias (Slugification pour Smarty)
        $rawAlias = $_POST['alias'] ?? '';
        $alias = preg_replace('/[^a-z0-9_]/', '_', strtolower(trim($rawAlias)));
        $context = FormTool::simpleClean($_POST['context'] ?? 'home');

        if (empty($alias)) {
            $this->jsonResponse(false, 'L\'alias ne peut pas être vide.');
        }

        // Vérification d'unicité
        if ($db->aliasExists($alias, $idTb)) {
            $this->jsonResponse(false, 'Cet alias est déjà utilisé par un autre bloc. Veuillez en choisir un autre.');
        }

        $mainData = [
            'alias'   => $alias,
            'context' => $context
        ];

        // Préparation du contenu multilingue
        $contentData = [];
        if (isset($_POST['content_tb']) && is_array($_POST['content_tb'])) {
            foreach ($_POST['content_tb'] as $idLang => $content) {
                $contentData[$idLang] = [
                    // On ne nettoie pas avec simpleClean ici car c'est du HTML provenant de TinyMCE
                    'content_tb' => $content ?? ''
                ];
            }
        }

        if ($db->saveBlock($idTb, $mainData, $contentData)) {
            CacheManager::clearFrontend('magixtextblock');

            $msg = ($idTb === 0) ? 'Bloc de texte créé avec succès.' : 'Bloc de texte mis à jour.';
            $type = ($idTb === 0) ? 'add' : 'update';
            $this->jsonResponse(true, $msg, ['type' => $type]);
        } else {
            $this->jsonResponse(false, 'Erreur lors de l\'enregistrement du bloc.');
        }
    }

    /**
     * Traitement AJAX de la suppression (Standard Magix CMS pour table-forms)
     */
    public function delete(): void
    {
        // Nettoie le buffer pour éviter les erreurs JSON
        if (ob_get_length()) ob_clean();

        // 1. Vérification du jeton de sécurité
        $token = $_GET['hashtoken'] ?? '';
        if (!$this->session->validateToken(str_replace(' ', '+', $token))) {
            $this->jsonResponse(false, 'Token de sécurité invalide.');
        }

        // 2. Récupération des IDs (fonctionne pour 1 ou plusieurs lignes cochées)
        $ids = $_POST['ids'] ?? [$_POST['id'] ?? null];
        $cleanIds = array_filter(array_map('intval', (array)$ids));

        if (!empty($cleanIds)) {
            $db = new TextBlockAdminDb();
            $deletedCount = 0;

            // 3. Boucle de suppression
            foreach ($cleanIds as $idTb) {
                if ($db->deleteBlock($idTb)) {
                    $deletedCount++;
                }
            }

            // 4. Réponse JSON formatée pour le Javascript de table-forms
            if ($deletedCount > 0) {
                CacheManager::clearFrontend('magixtextblock');

                $msg = $deletedCount > 1 ? "$deletedCount blocs supprimés avec succès." : "Le bloc a été supprimé.";
                $this->jsonResponse(true, $msg, ['type' => 'delete', 'ids' => $cleanIds]);
            }
        }

        $this->jsonResponse(false, 'Erreur : Aucun bloc sélectionné ou suppression impossible.');
    }
}
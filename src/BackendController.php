<?php
declare(strict_types=1);

namespace Plugins\MagixTextBlock\src;

use App\Backend\Controller\BaseController;
use Plugins\MagixTextBlock\db\TextBlockAdminDb;
use Magepattern\Component\HTTP\Request;
use Magepattern\Component\Tool\FormTool;
use Magepattern\Component\Tool\SmartyTool;

class BackendController extends BaseController
{
    public function run(): void
    {
        SmartyTool::addTemplateDir('admin', ROOT_DIR . 'plugins' . DS . 'MagixTextBlock' . DS . 'views' . DS . 'admin');

        $action = $_GET['action'] ?? 'index';

        // 🟢 Route de sauvegarde (Création / Mise à jour via AJAX)
        if ($action === 'saveBlock' && Request::isMethod('POST')) {
            $this->processSaveBlock();
            return;
        }

        // 🟢 Route de suppression
        if ($action === 'deleteBlock') {
            $this->processDeleteBlock();
            return;
        }

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

        $blocksList = $db->getBlocksList($idLangDefault);

        $this->view->assign([
            'blocksList' => $blocksList,
            'hashtoken'  => $this->session->getToken()
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

        // 🟢 FIX : Utilisation du header natif comme dans le ProductController
        if ($idTb === 0) {
            header('Location: index.php?controller=MagixTextBlock');
            exit;
        }

        $db = new TextBlockAdminDb();
        $langs = $db->fetchLanguages();
        $blockData = $db->getBlockFull($idTb);

        // 🟢 FIX : Redirection si l'ID n'existe pas en DB
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

        // 🟢 Nettoyage strict de l'alias (Slugification pour Smarty)
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
            $msg = ($idTb === 0) ? 'Bloc de texte créé avec succès.' : 'Bloc de texte mis à jour.';
            $type = ($idTb === 0) ? 'add' : 'update';
            $this->jsonResponse(true, $msg, ['type' => $type]);
        } else {
            $this->jsonResponse(false, 'Erreur lors de l\'enregistrement du bloc.');
        }
    }

    /**
     * Traitement AJAX de la suppression
     */
    private function processDeleteBlock(): void
    {
        $idTb = (int)($_GET['id_tb'] ?? 0);
        $db = new TextBlockAdminDb();

        if ($idTb > 0 && $db->deleteBlock($idTb)) {
            $this->jsonResponse(true, 'Bloc de texte supprimé avec succès.', ['type' => 'delete']);
        }

        $this->jsonResponse(false, 'Erreur lors de la suppression.');
    }
}
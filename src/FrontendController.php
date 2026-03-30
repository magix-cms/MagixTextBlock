<?php
declare(strict_types=1);

namespace Plugins\MagixTextBlock\src;

use Plugins\MagixTextBlock\db\TextBlockFrontDb;
use Magepattern\Component\Tool\SmartyTool;
use Smarty\Template; // 🟢 FIX : On importe la classe exacte de Smarty 5

class FrontendController
{
    private static array $cacheTexts = [];
    private static bool $isLoaded = false;

    /**
     * Rendu appelé automatiquement par Smarty lorsqu'il croise {textblock alias="..."}
     * * 🟢 FIX : Le paramètre 2 exige désormais le type Smarty\Template
     */
    public static function renderTextBlock(array $params, Template $template): string
    {
        $alias = $params['alias'] ?? '';

        if (empty($alias)) {
            return '';
        }

        // On charge la BDD UNE SEULE FOIS par page
        if (!self::$isLoaded) {
            $view = SmartyTool::getInstance('front');
            $langData = $view->getTemplateVars('current_lang') ?: $view->getTemplateVars('lang') ?: ['id_lang' => 1];
            $idLang = (int)($langData['id_lang'] ?? 1);

            // Détection du contrôleur (ex: index.php?controller=news)
            $context = $_GET['controller'] ?? 'home';
            $context = strtolower($context);

            // Sécurité : Magix appelle souvent la page d'accueil "index"
            if ($context === 'index' || $context === '') {
                $context = 'home';
            }

            $db = new TextBlockFrontDb();
            $items = $db->getTextBlocksByContext($context, $idLang);

            // On stocke tous les textes de la page en mémoire
            foreach ($items as $item) {
                self::$cacheTexts[$item['alias']] = $item['content_tb'];
            }

            self::$isLoaded = true;
        }

        // On retourne le texte demandé.
        // Pas besoin de "nofilter", Smarty 5 interprète le HTML renvoyé par un plugin natif !
        return self::$cacheTexts[$alias] ?? '';
    }
}
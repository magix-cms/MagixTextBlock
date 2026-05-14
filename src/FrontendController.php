<?php
declare(strict_types=1);

namespace Plugins\MagixTextBlock\src;

use Plugins\MagixTextBlock\db\TextBlockFrontDb;
use Magepattern\Component\Tool\SmartyTool;
use Smarty\Template;

class FrontendController
{
    private static array $cacheTexts = [];
    private static bool $isLoaded = false;

    /**
     * Point d'entrée standard pour le système de Hook dynamique (Layout)
     */
    public static function renderWidget(array $params = []): string
    {
        // Le Layout système envoie généralement 'instance_slug' ou 'item_slug'
        $slug = $params['instance_slug'] ?? $params['item_slug'] ?? '';

        if (empty($slug)) return '';

        // On redirige vers notre méthode principale en lui passant l'alias
        return self::renderTextBlock(['alias' => $slug]);
    }

    /**
     * Point d'entrée pour la balise Smarty {textblock alias="..."}
     */
    public static function renderTextBlock(array $params, ?Template $template = null): string
    {
        // FUSION : On accepte 'alias' (Smarty) ou 'instance_slug' (Hook)
        $alias = $params['alias'] ?? $params['instance_slug'] ?? '';

        if (empty($alias)) {
            return '';
        }

        // On charge la BDD UNE SEULE FOIS par page
        if (!self::$isLoaded) {
            $view = SmartyTool::getInstance('front');
            $langData = $view->getTemplateVars('current_lang') ?: $view->getTemplateVars('lang') ?: ['id_lang' => 1];
            $idLang = (int)($langData['id_lang'] ?? 1);

            $context = $_GET['controller'] ?? 'home';
            $context = strtolower($context);

            if ($context === 'index' || $context === '') {
                $context = 'home';
            }

            $db = new TextBlockFrontDb();
            // Cette requête devra être mise en cache SQL (voir étape suivante)
            $items = $db->getTextBlocksByContext($context, $idLang);

            foreach ($items as $item) {
                self::$cacheTexts[$item['alias']] = $item['content_tb'];
            }

            self::$isLoaded = true;
        }

        return self::$cacheTexts[$alias] ?? '';
    }
}
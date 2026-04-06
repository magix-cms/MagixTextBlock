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
     * 🟢 NOUVEAU : Le routeur pour les HOOKS du CMS
     * Le HookManager envoie toujours le nom du hook dans $params['name']
     */
    public static function renderWidget(array $params = []): string
    {
        $hookName = $params['name'] ?? '';

        if ($hookName === 'displayHomeTop') {
            // On redirige vers notre méthode d'affichage avec l'alias forcé
            return self::renderTextBlock(['alias' => 'hook_home_top']);
        }

        if ($hookName === 'displayHomeBottom') {
            return self::renderTextBlock(['alias' => 'hook_home_bottom']);
        }

        return '';
    }

    /**
     * L'AFFICHAGE (Manuel ou par Hook)
     * Reste strictement identique à ce qu'on avait fait !
     */
    public static function renderTextBlock(array $params, ?Template $template = null): string
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

            $context = $_GET['controller'] ?? 'home';
            $context = strtolower($context);

            if ($context === 'index' || $context === '') {
                $context = 'home';
            }

            $db = new TextBlockFrontDb();
            $items = $db->getTextBlocksByContext($context, $idLang);

            foreach ($items as $item) {
                self::$cacheTexts[$item['alias']] = $item['content_tb'];
            }

            self::$isLoaded = true;
        }

        return self::$cacheTexts[$alias] ?? '';
    }
}
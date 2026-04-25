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
     * 🟢 MODIFICATION : On ajoute un "?" devant Template pour le rendre optionnel.
     * Ainsi, on peut l'appeler manuellement depuis les hooks !
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
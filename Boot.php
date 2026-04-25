<?php
declare(strict_types=1);

namespace Plugins\MagixTextBlock;

use Magepattern\Component\Tool\SmartyTool;
use Plugins\MagixTextBlock\src\FrontendController;
use App\Component\Hook\HookManager; // 🟢 N'oubliez pas d'importer la classe HookManager

class Boot
{
    public function register(): void
    {
        // 1. Déclaration de votre tag Smarty (qui fonctionnait déjà très bien)
        $smarty = SmartyTool::getInstance('front');
        $smarty->registerPlugin('function', 'textblock', [FrontendController::class, 'renderTextBlock']);

        // 2. 🟢 LA SOLUTION : On force manuellement le lien entre le CMS et vos méthodes,
        // en utilisant l'orthographe exacte (CamelCase) présente dans la table mc_hook_item !
        HookManager::register('displayHomeTop', 'MagixTextBlock', [$this, 'hookDisplayHomeTop']);
        HookManager::register('displayHomeBottom', 'MagixTextBlock', [$this, 'hookDisplayHomeBottom']);
    }

    /**
     * HOOK 1 : Haut de page
     */
    public function hookDisplayHomeTop(array $params): string
    {
        return FrontendController::renderTextBlock(['alias' => 'hook_home_top']);
    }

    /**
     * HOOK 2 : Bas de page
     */
    public function hookDisplayHomeBottom(array $params): string
    {
        return FrontendController::renderTextBlock(['alias' => 'hook_home_bottom']);
    }
}
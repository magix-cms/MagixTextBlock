<?php
declare(strict_types=1);

namespace Plugins\MagixTextBlock;

use Magepattern\Component\Tool\SmartyTool;
use Plugins\MagixTextBlock\src\FrontendController;
use App\Component\Hook\HookManager; // 🟢 Ne pas oublier cet import !

class Boot
{
    public function register(): void
    {
        // 1. ON GARDE L'APPEL MANUEL SMARTY : {textblock alias="..."}
        $smarty = SmartyTool::getInstance('front');
        $smarty->registerPlugin('function', 'textblock', [FrontendController::class, 'renderTextBlock']);

        // 2. ON AJOUTE LES HOOKS AUTOMATIQUES POUR MAGIX CMS
        HookManager::register(
            'displayHomeTop',
            'MagixTextBlock',
            [FrontendController::class, 'renderWidget']
        );

        HookManager::register(
            'displayHomeBottom',
            'MagixTextBlock',
            [FrontendController::class, 'renderWidget']
        );
    }
}
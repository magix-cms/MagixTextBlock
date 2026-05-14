<?php
declare(strict_types=1);

namespace Plugins\MagixTextBlock;

use Magepattern\Component\Tool\SmartyTool;
use Plugins\MagixTextBlock\src\FrontendController;

class Boot
{
    public function register(): void
    {
        // 1. Déclaration du tag manuel Smarty : {textblock alias="xyz"}
        $smarty = SmartyTool::getInstance('front');
        $smarty->registerPlugin('function', 'textblock', [FrontendController::class, 'renderTextBlock']);

        // Le système de Hook dynamique (mc_hook_item) s'occupe de faire le lien
        // automatiquement si le layout manager est bien configuré pour appeler
        // la méthode standard (FrontendController::renderWidget)
        // hook_home_top & hook_home_bottom
    }
}
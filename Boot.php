<?php
declare(strict_types=1);

namespace Plugins\MagixTextBlock;

use Magepattern\Component\Tool\SmartyTool;
use Plugins\MagixTextBlock\src\FrontendController;

class Boot
{
    public function register(): void
    {
        // On enregistre une balise Smarty personnalisée {textblock alias="..."}
        // Cela fonctionne partout, sans avoir besoin de hook !
        $smarty = SmartyTool::getInstance('front');
        $smarty->registerPlugin('function', 'textblock', [FrontendController::class, 'renderTextBlock']);
    }
}
<?php
declare(strict_types=1);

namespace Plugins\MagixTextBlock\db;

use App\Frontend\Db\BaseDb;
use Magepattern\Component\Database\QueryBuilder;

class TextBlockFrontDb extends BaseDb
{
    /**
     * Récupère tous les blocs de texte pour un contexte et une langue donnés
     */
    public function getTextBlocksByContext(string $context, int $idLang): array
    {
        $qb = new QueryBuilder();
        $qb->select(['tb.alias', 'tbc.content_tb'])
            ->from('mc_textblock', 'tb')
            ->join('mc_textblock_content', 'tbc', 'tb.id_tb = tbc.id_tb')
            // 🟢 MODIFICATION : On charge le contexte courant OU les contextes globaux
            ->where('(tb.context = :context OR tb.context = "footer" OR tb.context = "other")', ['context' => $context])
            ->where('tbc.id_lang = :lang', ['lang' => $idLang]);

        $result = $this->executeAll($qb);

        return $result ?: [];
    }
}
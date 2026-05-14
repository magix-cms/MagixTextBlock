<?php
declare(strict_types=1);

namespace Plugins\MagixTextBlock\db;

use App\Frontend\Db\BaseDb;
use Magepattern\Component\Database\QueryBuilder;

class TextBlockFrontDb extends BaseDb
{
    /**
     * Récupère tous les blocs de texte pour un contexte et une langue donnés (Avec Cache SQL)
     */
    public function getTextBlocksByContext(string $context, int $idLang): array
    {
        // 1. Instanciation du gestionnaire de cache SQL
        $cache = $this->getSqlCache();
        $qb = new QueryBuilder();

        $qb->select(['tb.alias', 'tbc.content_tb'])
            ->from('mc_textblock', 'tb')
            ->join('mc_textblock_content', 'tbc', 'tb.id_tb = tbc.id_tb')
            // Conservation de VOS conditions spécifiques (footer, other)
            ->where('(tb.context = :context OR tb.context = "footer" OR tb.context = "other")', ['context' => $context])
            ->where('tbc.id_lang = :lang', ['lang' => $idLang]);

        // 2. Génération de la clé de cache avec le Tag unique du plugin
        $cacheKey = $cache->generateKey($qb->getSql(), $qb->getParams(), 'magixtextblock');

        // 3. Vérification : Les données sont-elles déjà en cache ?
        $data = $cache->get($cacheKey);
        if ($data !== null) {
            return $data; // On retourne le cache (0 requête SQL)
        }

        // 4. Si le cache est vide, on interroge la base de données
        $result = $this->executeAll($qb) ?: [];

        // 5. On met le résultat en cache pour 24 heures (86400 secondes)
        $cache->set($cacheKey, $result, 86400);

        return $result;
    }
}
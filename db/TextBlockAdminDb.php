<?php
declare(strict_types=1);

namespace Plugins\MagixTextBlock\db;

use App\Backend\Db\BaseDb;
use Magepattern\Component\Database\QueryBuilder;

class TextBlockAdminDb extends BaseDb
{
    /**
     * Récupère la liste de tous les blocs pour le tableau principal
     */
    public function getBlocksList(int $idLang): array
    {
        $qb = new QueryBuilder();
        $qb->select([
            'tb.id_tb',
            'tb.alias',
            'tb.context',
            'tbc.content_tb'
        ])
            ->from('mc_textblock', 'tb')
            // Le QueryBuilder ne prend pas de "bind" sur le leftJoin, on sécurise la variable en l'injectant
            ->leftJoin('mc_textblock_content', 'tbc', 'tb.id_tb = tbc.id_tb AND tbc.id_lang = ' . $idLang)
            ->orderBy('tb.context', 'ASC')
            ->orderBy('tb.alias', 'ASC'); // Appeler orderBy() à nouveau l'ajoute à la liste

        return $this->executeAll($qb) ?: [];
    }

    /**
     * Récupère un bloc complet (structure + toutes les traductions) pour l'édition
     */
    public function getBlockFull(int $idTb): array|false
    {
        // 1. Structure de base (Alias et Contexte)
        $qb = new QueryBuilder();
        $qb->select('*')->from('mc_textblock')->where('id_tb = :id', ['id' => $idTb]);
        $block = $this->executeRow($qb);

        if (!$block) {
            return false;
        }

        // 2. Traductions du contenu
        $qbContent = new QueryBuilder();
        $qbContent->select('*')->from('mc_textblock_content')->where('id_tb = :id', ['id' => $idTb]);
        $contents = $this->executeAll($qbContent);

        $block['content'] = [];
        if ($contents) {
            foreach ($contents as $c) {
                $block['content'][$c['id_lang']] = $c;
            }
        }

        return $block;
    }

    /**
     * Vérifie si un alias existe déjà (sauf pour l'ID en cours d'édition)
     */
    public function aliasExists(string $alias, int $excludeId = 0): bool
    {
        $qb = new QueryBuilder();
        $qb->select(['id_tb'])->from('mc_textblock')->where('alias = :alias', ['alias' => $alias]);

        if ($excludeId > 0) {
            $qb->where('id_tb != :id', ['id' => $excludeId]); // Ajoute la condition (AND)
        }

        return (bool)$this->executeRow($qb);
    }

    /**
     * Sauvegarde un bloc complet (Création ou Mise à jour)
     */
    public function saveBlock(int $idTb, array $mainData, array $contentData): bool
    {
        // 1. Sauvegarde de la structure (mc_textblock)
        if ($idTb === 0) {
            $qb = new QueryBuilder();
            $qb->insert('mc_textblock', $mainData);
            if ($this->executeInsert($qb)) {
                $idTb = $this->getLastInsertId();
            } else {
                return false;
            }
        } else {
            $qb = new QueryBuilder();
            $qb->update('mc_textblock', $mainData)->where('id_tb = :id', ['id' => $idTb]);
            $this->executeUpdate($qb);
        }

        // 2. Sauvegarde des traductions (mc_textblock_content)
        foreach ($contentData as $idLang => $data) {
            $qbCheck = new QueryBuilder();
            $qbCheck->select(['id_content'])->from('mc_textblock_content')
                ->where('id_tb = :tb', ['tb' => $idTb])
                ->where('id_lang = :lang', ['lang' => $idLang]);

            $exists = $this->executeRow($qbCheck);
            $qb = new QueryBuilder();

            if ($exists) {
                $qb->update('mc_textblock_content', $data)
                    ->where('id_tb = :tb', ['tb' => $idTb])
                    ->where('id_lang = :lang', ['lang' => $idLang]);
                $this->executeUpdate($qb);
            } else {
                $data['id_tb']   = $idTb;
                $data['id_lang'] = $idLang;
                $qb->insert('mc_textblock_content', $data);
                $this->executeInsert($qb);
            }
        }

        return true;
    }

    /**
     * Supprime un bloc et ses traductions
     */
    public function deleteBlock(int $idTb): bool
    {
        $qbContent = new QueryBuilder();
        $qbContent->delete('mc_textblock_content')->where('id_tb = :id', ['id' => $idTb]);
        $resContent = $this->executeDelete($qbContent);

        $qbMain = new QueryBuilder();
        $qbMain->delete('mc_textblock')->where('id_tb = :id', ['id' => $idTb]);
        $resMain = $this->executeDelete($qbMain);

        return $resMain && $resContent;
    }
}
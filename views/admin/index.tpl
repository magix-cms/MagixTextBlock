{extends file="layout.tpl"}

{block name='head:title'}Gestion des blocs de texte{/block}

{block name='article'}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-card-text me-2"></i> Blocs de texte libres
        </h1>

        <div class="d-flex gap-2">
            <a href="index.php?controller=MagixTextBlock&action=add" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Ajouter un bloc
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Contexte</th>
                        <th>Alias (Variable Smarty)</th>
                        <th>Extrait du contenu</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    {if isset($blocksList) && $blocksList|count > 0}
                        {foreach $blocksList as $block}
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-secondary">{$block.context|escape:'html'}</span>
                                </td>
                                <td>
                                    <code class="text-primary fw-bold">{$smarty.ldelim}textblock alias="{$block.alias|escape:'html'}"{$smarty.rdelim}</code>
                                </td>
                                <td class="text-muted small">
                                    {$block.content_tb|strip_tags|truncate:60:'...'|default:'<i class="text-black-50">Vide</i>'}
                                </td>
                                <td class="text-end pe-4">
                                    <a href="index.php?controller=MagixTextBlock&action=edit&edit={$block.id_tb}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-url="index.php?controller=MagixTextBlock&action=deleteBlock&id_tb={$block.id_tb}" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        {/foreach}
                    {else}
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Aucun bloc de texte pour le moment.
                            </td>
                        </tr>
                    {/if}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{/block}
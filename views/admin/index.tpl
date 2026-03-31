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
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 fw-bold text-primary">Liste des blocs de texte</h6>
        </div>
        <div class="card-body p-0">
            {* Appel du composant générique avec les données pré-formatées *}
            {include file="components/table-forms.tpl"
            data=$textblocks
            idcolumn='id_tb'
            controller="MagixTextBlock"
            change_offset=false
            search=false}
        </div>
    </div>
{/block}
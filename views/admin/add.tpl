{extends file="layout.tpl"}

{block name='head:title'}Ajouter un bloc de texte{/block}

{block name='article'}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-plus-square me-2"></i> Ajouter un bloc
        </h1>
        <a href="index.php?controller=MagixTextBlock" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <form id="add_textblock_form" action="index.php?controller=MagixTextBlock&action=saveBlock" method="post" class="validate_form add_form">
        <input type="hidden" name="hashtoken" value="{$hashtoken}">
        <input type="hidden" name="id_tb" value="0">

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-primary">Configuration du bloc</h6>
            </div>
            <div class="card-body p-4 bg-light">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="alias" class="form-label fw-medium">Alias (Identifiant unique)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted">{$smarty.ldelim}textblock alias="</span>
                            <input type="text" class="form-control" id="alias" name="alias" value="" placeholder="ex: intro_page_accueil" required />
                            <span class="input-group-text bg-white text-muted">"{$smarty.rdelim}</span>
                        </div>
                        <div class="form-text small">Espaces interdits (utilisez des underscores `_`).</div>
                    </div>
                    <div class="col-md-6">
                        <label for="context" class="form-label fw-medium">Contexte (Module de destination)</label>
                        <select class="form-select" id="context" name="context">
                            <option value="home">Page d'accueil (Home)</option>
                            <option value="news">Actualités (News)</option>
                            <option value="product">Produits</option>
                            <option value="category">Catégories</option>
                            <option value="contact">Contact</option>
                            <option value="footer">Pied de page global (Footer)</option>
                            <option value="other">Autre / Global</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-primary">Contenu du texte</h6>
                {if isset($langs)}
                    {include file="components/dropdown-lang.tpl"}
                {/if}
            </div>

            <div class="card-body p-4">
                <div class="tab-content">
                    {if isset($langs)}
                        {foreach $langs as $id => $iso}
                            <fieldset class="tab-pane {if $iso@first}show active{/if}" id="lang-{$id}">
                                <div class="mb-3">
                                    <label for="content_tb_{$id}" class="form-label fw-medium">Texte ({$iso|upper}) :</label>
                                    <textarea class="form-control mceEditor" id="content_tb_{$id}" name="content_tb[{$id}]" rows="15"></textarea>
                                </div>
                            </fieldset>
                        {/foreach}
                    {/if}
                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-5">
                        <i class="bi bi-check-lg me-2"></i> Enregistrer le bloc
                    </button>
                </div>
            </div>
        </div>
    </form>
{/block}
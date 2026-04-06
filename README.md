# MagixTextBlock pour Magix CMS

[![Release](https://img.shields.io/github/release/magix-cms/magixtextblock.svg)](https://github.com/magix-cms/magixtextblock/releases/latest)
[![License](https://img.shields.io/github/license/magix-cms/magixtextblock.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-blue.svg)](https://php.net/)
[![Magix CMS](https://img.shields.io/badge/Magix%20CMS-4.x-success.svg)](https://www.magix-cms.com/)

**MagixTextBlock** est un plugin hybride open source pour **Magix CMS 4** qui révolutionne la gestion des contenus libres. Profitez du meilleur des deux mondes : injectez des blocs de texte riche multilingues au pixel près via un système de balisage Smarty natif, ou laissez le CMS les charger automatiquement via son système de Hooks natifs.

## 👥 Auteurs

* **Gerits Aurelien** (gtraxx) - [aurelien@magix-cms.com](mailto:aurelien@magix-cms.com)
* Communauté Magix CMS

## ☕ Soutenir le projet

Si vous souhaitez soutenir le développement de ce plugin, vous pouvez faire un don via PayPal :

[![Faire un don](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.com/donate/?business=BQBYN3XYGMDML&no_recurring=0&currency_code=EUR)

---

## ✨ Fonctionnalités clés

Ce plugin a été pensé pour offrir une liberté architecturale totale aux intégrateurs et webmasters, sans sacrifier les standards du CMS.

* **Architecture Hybride (Balise & Hooks) :** Appelez vos textes manuellement n'importe où dans vos templates via la balise `{textblock alias="..."}`, ou accrochez-les automatiquement aux zones par défaut du CMS (ex: `displayHomeTop`, `displayHomeBottom`).
* **Haute Performance (Mise en cache) :** Toutes les variables de texte d'une page sont chargées via **une seule requête SQL**, même si vous affichez 20 blocs différents simultanément.
* **Multilingue Natif :** Gestion transparente des traductions en fonction de la langue active du visiteur.
* **Édition Riche :** Intégration complète avec TinyMCE (et ses plugins comme MagixMedia) pour un formatage HTML parfait.
* **Chargement Contextuel :** Les textes sont liés à des contextes (home, news, footer...) pour ne charger en mémoire que ce qui est strictement nécessaire à la page affichée.

---

## 🚀 Installation & Configuration

1. Téléchargez et décompressez l'archive du plugin.
2. Placez le dossier `MagixTextBlock` dans le répertoire `plugins/` de votre installation Magix CMS.
3. Connectez-vous à l'administration de votre site.
4. Rendez-vous dans **Extensions** > **Plugins**.
5. Cliquez sur le bouton d'installation pour **MagixTextBlock**.
6. Accédez à la configuration du plugin via le bouton "Gérer" pour créer vos blocs.

---

## 💻 Comment l'utiliser ?

MagixTextBlock s'adapte à votre façon de travailler. Vous pouvez utiliser deux méthodes d'intégration :

### Méthode 1 : Placement Chirurgical (Balise Smarty)
Idéal pour insérer un texte en plein milieu d'une structure HTML complexe.

1. **Dans le Back-office :** Créez un nouveau bloc, assignez-lui le **contexte** de la page (ex: *home*) et un **alias libre** (ex: *intro_texte*).
2. **Dans votre thème (`.tpl`) :** Appelez simplement votre alias. Aucun attribut `nofilter` n'est requis, l'injection HTML est native dans Smarty 5.

**Exemple d'intégration :**

```smarty
<section class="hero-section">
    <div class="container text-center">
        {* Appel manuel à l'endroit exact souhaité *}
        {textblock alias="intro_texte"}
    </div>
</section>
```

### Méthode 2 : Affichage Automatique (Hooks Magix CMS)
Idéal pour confier la gestion de l'affichage au CMS sans toucher au code de votre thème.

1. **Dans le Back-office :** Créez un nouveau bloc et choisissez le contexte approprié (ex: *home* pour la page d'accueil).
2. **L'astuce de l'Alias :** Donnez à votre bloc un alias correspondant exactement au nom du hook souhaité, préfixé de `hook_` en minuscules.
    * Pour afficher dans le hook `displayHomeTop` ➔ Nommez l'alias **`hook_home_top`**
    * Pour afficher dans le hook `displayHomeBottom` ➔ Nommez l'alias **`hook_home_bottom`**
3. C'est tout ! Le texte apparaîtra automatiquement dans la zone prévue par votre layout.

---

## ⚙️ Prérequis

* **Magix CMS 4.x**
* **PHP 8.2** ou supérieur
* **Smarty 5** (Intégré nativement dans Magix CMS 4)

---

## 🛠 Dépannage (Troubleshooting)

| Problème | Cause possible | Solution |
| :--- | :--- | :--- |
| **Le texte ne s'affiche pas** | Mauvais contexte ou alias erroné | Vérifiez que l'alias défini dans le `.tpl` (ou via le nom du hook) correspond exactement à celui du back-office. |
| **Erreur de contexte** | La page courante n'est pas ciblée | Assurez-vous que le bloc est assigné au bon module (ex: `home` pour l'accueil, ou `footer` pour du global). |
| **Hook invisible** | Convention de nommage | Pour qu'un hook automatique fonctionne, l'alias doit impérativement s'appeler `hook_home_top` ou `hook_home_bottom`. |
| **Code HTML apparent** | Balise mal formattée | Utilisez la syntaxe stricte `{textblock alias="votre_alias"}`. N'ajoutez pas de `$`, ni de `nofilter`. |
| **Erreur Fatale Smarty** | Conflit de version Smarty | Ce plugin utilise le namespace `Smarty\Template` requis par Smarty 5. Vérifiez la version de votre moteur. |

---

## 🤝 Contribution

Ce projet est open source. Nous encourageons les développeurs à l'améliorer :
1. Forker le projet.
2. Créer une branche pour une nouvelle fonctionnalité (`git checkout -b feature/NouvelleIdee`).
3. Commiter les changements (`git commit -m 'Ajout de NouvelleIdee'`).
4. Push sur la branche (`git push origin feature/NouvelleIdee`).
5. Ouvrir une **Pull Request**.

---

## 📄 Licence

Ce projet est sous licence **GPLv3**. Voir le fichier [LICENSE](LICENSE) pour plus de détails.  
Copyright (C) 2008 - 2026 Gerits Aurelien (Magix CMS).  
Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le modifier selon les termes de la Licence Publique Générale GNU telle que publiée par la Free Software Foundation ; soit la version 3 de la Licence, ou (à votre discrétion) toute version ultérieure.
# MagixTextBlock pour Magix CMS

[![Release](https://img.shields.io/github/release/magix-cms/magixtextblock.svg)](https://github.com/magix-cms/magixtextblock/releases/latest)
[![License](https://img.shields.io/github/license/magix-cms/magixtextblock.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-blue.svg)](https://php.net/)
[![Magix CMS](https://img.shields.io/badge/Magix%20CMS-4.x-success.svg)](https://www.magix-cms.com/)

**MagixTextBlock** est un plugin hybride open source pour **Magix CMS 4** qui révolutionne la gestion des contenus libres. Profitez du meilleur des deux mondes : injectez des blocs de texte riche multilingues au pixel près via un système de balisage Smarty natif, ou placez-les dynamiquement (Glisser-Déposer) via le Gestionnaire de Layout du CMS.

## 👥 Auteurs

* **Gerits Aurelien** (gtraxx) - [aurelien@magix-cms.com](mailto:aurelien@magix-cms.com)
* Communauté Magix CMS

## ☕ Soutenir le projet

Si vous souhaitez soutenir le développement de ce plugin, vous pouvez faire un don via PayPal :

[![Faire un don](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.com/donate/?business=BQBYN3XYGMDML&no_recurring=0&currency_code=EUR)

---

## ✨ Fonctionnalités clés

Ce plugin a été pensé pour offrir une liberté architecturale totale aux intégrateurs et webmasters, sans sacrifier les standards du CMS.

* **Architecture 100% Hybride :** Appelez vos textes manuellement n'importe où dans vos templates via la balise `{textblock alias="..."}`, ou accrochez-les visuellement à n'importe quel hook via le Gestionnaire de Layout. Un même texte peut être utilisé des deux façons simultanément !
* **Haute Performance (Cache SQL) :** Le plugin intègre le système de cache natif de Magix CMS. Les requêtes en base de données sont mises en cache, offrant un affichage instantané même si vous affichez des dizaines de blocs différents sur une même page.
* **Multilingue Natif :** Gestion transparente des traductions en fonction de la langue active du visiteur.
* **Édition Riche :** Intégration complète avec TinyMCE pour un formatage HTML parfait.
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

### Méthode 1 : Placement Chirurgical (Balise Smarty Statique)
Idéal pour le développeur du thème qui souhaite insérer un texte en plein milieu d'une structure HTML complexe.

1. **Dans le Back-office :** Créez un nouveau bloc, assignez-lui le **contexte** de la page (ex: *home*) et un **alias libre** (ex: *intro_texte*).
2. **Dans votre thème (`.tpl`) :** Appelez simplement votre alias à l'endroit exact souhaité via la balise native.

**Exemple d'intégration :**

```smarty
<section class="hero-section">
    <div class="container text-center">
        {* Appel manuel à l'endroit exact souhaité *}
        {textblock alias="intro_texte"}
    </div>
</section>
```

### Méthode 2 : Affichage Dynamique (Gestionnaire de Layout)
Idéal pour le client final ou le webmaster qui souhaite ajouter des blocs de texte visuellement, sans toucher au code source du thème.

1. **Dans la configuration du plugin :** Créez votre bloc texte avec l'alias de votre choix (ex: `promo_footer`) et définissez son contexte.
2. **Dans le menu Apparence > Layout :** Ajoutez le module **MagixTextBlock** dans la zone de hook souhaitée (ex: `displayFooter`).
3. **La liaison :** Dans les paramètres de ce module fraîchement ajouté au layout, renseignez exactement votre alias (`promo_footer`) dans le champ **Identifiant / Slug**.
4. C'est tout ! Le texte apparaîtra automatiquement dans la zone prévue, géré dynamiquement par le CMS.

---

## ⚙️ Prérequis

* **Magix CMS 4.x**
* **PHP 8.2** ou supérieur
* **Smarty 5** (Intégré nativement dans Magix CMS 4)

---

## 🛠 Dépannage (Troubleshooting)

| Problème | Cause possible | Solution |
| :--- | :--- | :--- |
| **Le texte ne s'affiche pas** | Mauvais contexte ou alias erroné | Vérifiez que l'alias défini dans le `.tpl` (ou le slug dans le Layout) correspond *exactement* à celui du bloc créé. |
| **Erreur de contexte** | La page courante n'est pas ciblée | Assurez-vous que le bloc est assigné au bon contexte (ex: `home` pour l'accueil, ou `footer`/`other` pour un affichage global). |
| **Modification invisible en Front** | Cache SQL actif | Sauvegardez à nouveau le bloc depuis l'administration du plugin. Cela purgera automatiquement le cache Frontend (`ClearFrontend`). |
| **Code HTML apparent en texte brut** | Balise mal formattée | Utilisez la syntaxe stricte `{textblock alias="votre_alias"}`. N'ajoutez pas de `$`, ni de `nofilter` avec Smarty 5. |

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
# MagixTextBlock pour Magix CMS

[![Release](https://img.shields.io/github/release/magix-cms/magixtextblock.svg)](https://github.com/magix-cms/magixtextblock/releases/latest)
[![License](https://img.shields.io/github/license/magix-cms/magixtextblock.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-blue.svg)](https://php.net/)
[![Magix CMS](https://img.shields.io/badge/Magix%20CMS-4.x-success.svg)](https://www.magix-cms.com/)

**MagixTextBlock** est un plugin hybride open source pour **Magix CMS 4** qui révolutionne la gestion des contenus libres. Oubliez les contraintes des balises `{hook}` traditionnelles et des modules en boucle : ce plugin introduit un système de balisage Smarty natif permettant d'injecter des blocs de texte riche multilingues au pixel près, n'importe où dans votre thème.

## 👥 Auteurs

* **Gerits Aurelien** (gtraxx) - [aurelien@magix-cms.com](mailto:aurelien@magix-cms.com)
* Communauté Magix CMS

## ☕ Soutenir le projet

Si vous souhaitez soutenir le développement de ce plugin, vous pouvez faire un don via PayPal :

[![Faire un don](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.com/donate/?business=BQBYN3XYGMDML&no_recurring=0&currency_code=EUR)

---

## ✨ Fonctionnalités clés

Contrairement aux modules classiques qui affichent des listes ou nécessitent des positions figées, ce plugin a été pensé pour offrir une liberté architecturale totale aux intégrateurs et webmasters.

* **Placement Chirurgical (Zéro Hook) :** N'utilise aucun hook de positionnement. Vous appelez vos textes directement via une fonction Smarty personnalisée : `{textblock alias="..."}`.
* **Haute Performance (Mise en cache) :** Toutes les variables de texte d'une page sont chargées via **une seule requête SQL**, même si vous affichez 20 blocs différents.
* **Multilingue Natif :** Gestion transparente des traductions en fonction de la langue active du visiteur.
* **Édition Riche :** Intégration complète avec TinyMCE (et ses plugins comme MagixMedia) pour un formatage HTML parfait.
* **Chargement Contextuel :** Les textes sont liés à des contextes (home, news, footer...) pour ne charger en mémoire que ce qui est strictement nécessaire à la page affichée.

---

## 🚀 Installation & Configuration

1. Téléchargez et décompressez l'archive du plugin.
2. Placez le dossier `MagixTextBlock` dans le répertoire `plugins/` de votre installation Magix CMS.
3. Connectez-vous à l'administration de votre site.
4. Rendez-vous dans **Extensions** > **Gestionnaire**.
5. Cliquez sur le bouton d'installation pour **MagixTextBlock**.
6. Accédez à la configuration du plugin via le bouton "Gérer" pour créer votre premier bloc.

---

## 💻 Comment l'utiliser ?

L'utilisation se fait en deux étapes très simples :

1. **Dans le Back-office :** Créez un nouveau bloc de texte, assignez-lui un **contexte** (ex: *home*) et un **alias unique** (ex: *test_block*).
2. **Dans votre thème (Fichiers `.tpl`) :** Appelez simplement votre alias avec la balise dédiée. Aucun attribut `nofilter` n'est requis, l'injection HTML est gérée nativement par le cœur de Smarty 5.

**Exemple d'intégration :**

```smarty
<section class="hero-section">
    <div class="container text-center">
        {* On appelle la nouvelle balise magique ! *}
        {textblock alias="test_block"}
    </div>
</section>
```

---

## ⚙️ Prérequis

* **Magix CMS 4.x**
* **PHP 8.2** ou supérieur
* **Smarty 5** (Intégré nativement dans Magix CMS 4)

---

## 🛠 Dépannage (Troubleshooting)

| Problème | Cause possible | Solution |
| :--- | :--- | :--- |
| **Le texte ne s'affiche pas** | Mauvais contexte ou alias erroné | Vérifiez que l'alias écrit dans votre `.tpl` correspond exactement à celui du back-office, sans espaces. |
| **Erreur de contexte** | La page courante n'est pas ciblée | Assurez-vous que le bloc est assigné au bon module (ex: `home` pour l'accueil, ou `footer` pour du global). |
| **Code HTML apparent** | Balise mal formattée | Utilisez la syntaxe stricte `{textblock alias="votre_alias"}`. N'ajoutez pas de `$`, ni de `nofilter`. |
| **Erreur Fatale Smarty** | Conflit de version Smarty | Ce plugin utilise le namespace `Smarty\Template` requis par Smarty 5. Vérifiez la version de votre moteur de template. |

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
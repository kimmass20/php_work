# Gestion des Auditoires

Système de gestion des salles de classe et de planning pour université.

## 📁 Structure du Projet

```
gestion_auditoires/
│
├── index.php                # Page d'accueil (Dashboard)
│
├── data/                    # Fichiers JSON (base de données)
│   ├── salles.json         # Données des salles
│   ├── promotions.json     # Données des promotions
│   ├── cours.json          # Données des cours
│   └── planning.json       # Planning généré
│
├── frontend/                # Interface utilisateur
│   ├── css/
│   │   └── style.css       # Styles CSS modernes
│   │
│   ├── pages/
│   │   ├── ajouter_salle.php
│   │   ├── ajouter_promotion.php
│   │   ├── ajouter_cours.php
│   │   ├── afficher_donnees.php
│   │   └── afficher_planning.php
│   │
│   └── components/
│       ├── header.php      # En-tête et navigation
│       └── footer.php      # Pied de page
│
├── backend/                 # Logique métier
│   ├── read.php            # Fonctions de lecture
│   ├── write.php           # Fonctions d'écriture
│   ├── utils.php           # Utilitaires
│   └── generate_planning.php  # Génération de planning
│
└── routes/                  # Traitement des formulaires
    ├── traiter_salle.php
    ├── traiter_promotion.php
    ├── traiter_cours.php
    └── generer_planning.php
```

## 🚀 Installation

1. Cloner ou télécharger le projet dans votre serveur web (Apache/Nginx)
2. Assurez-vous que PHP 7.4+ est installé
3. Donner les permissions d'écriture au dossier `data/`:
   ```bash
   chmod -R 777 gestion_auditoires/data/
   ```
4. Accéder à l'application via votre navigateur: `http://localhost/gestion_auditoires/`

## ✨ Fonctionnalités

### 1. **Dashboard**
- Vue d'ensemble des statistiques
- Nombre de salles, cours, promotions
- Bouton de génération de planning

### 2. **Gestion des Salles**
- Ajouter des salles avec nom et capacité
- Visualiser toutes les salles

### 3. **Gestion des Promotions**
- Ajouter des promotions (L1, L2, L3, L4)
- Définir le nombre d'étudiants par promotion

### 4. **Gestion des Cours**
- Ajouter des cours
- Définir le type (Obligatoire/Optionnel)
- Assigner à une promotion

### 5. **Affichage des Données**
- Tableaux affichant toutes les salles, promotions et cours
- Interface claire et organisée

### 6. **Planning Hebdomadaire**
- Génération automatique du planning
- Vue hebdomadaire (Lundi - Vendredi)
- Créneaux horaires: 08:00, 10:00, 12:00, 14:00, 16:00
- Affichage: cours, promotion, salle

## 🎨 Design

- Interface moderne et minimaliste
- Couleurs douces: bleu, blanc, gris clair
- Coins arrondis et ombres subtiles
- Navigation latérale avec icônes
- Effets de survol
- Design responsive

## 🛠️ Technologies

- **Backend**: PHP 7.4+
- **Stockage**: JSON
- **Frontend**: HTML5, CSS3
- **Icônes**: Font Awesome 6
- **Architecture**: MVC simple

## 📝 Utilisation

1. **Ajouter des salles**: Définir nom et capacité
2. **Ajouter des promotions**: Choisir le niveau (L1-L4) et le nombre d'étudiants
3. **Ajouter des cours**: Définir nom, type et promotion assignée
4. **Générer le planning**: Cliquer sur "Générer Planning" depuis le dashboard
5. **Consulter le planning**: Accéder à la page Planning pour voir l'emploi du temps

## 🔒 Sécurité

- Validation des données côté serveur
- Nettoyage des entrées utilisateur (XSS protection)
- Vérification des méthodes HTTP
- Gestion des erreurs

## 📄 Licence

Projet éducatif libre d'utilisation.

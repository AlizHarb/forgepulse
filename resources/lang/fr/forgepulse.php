<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ForgePulse Language Lines (French)
    |--------------------------------------------------------------------------
    */

    'workflow' => [
        'title' => 'Flux de travail',
        'create' => 'Créer un flux de travail',
        'edit' => 'Modifier le flux de travail',
        'delete' => 'Supprimer le flux de travail',
        'name' => 'Nom du flux de travail',
        'description' => 'Description',
        'status' => 'Statut',
        'created_at' => 'Créé le',
        'updated_at' => 'Mis à jour le',
        'execute' => 'Exécuter le flux de travail',
        'save' => 'Enregistrer le flux de travail',
        'cancel' => 'Annuler',
    ],

    'status' => [
        'draft' => 'Brouillon',
        'active' => 'Actif',
        'inactive' => 'Inactif',
        'archived' => 'Archivé',
        'pending' => 'En attente',
        'running' => 'En cours',
        'completed' => 'Terminé',
        'failed' => 'Échoué',
        'cancelled' => 'Annulé',
        'skipped' => 'Ignoré',
    ],

    'step' => [
        'title' => 'Étapes',
        'add' => 'Ajouter une étape',
        'edit' => 'Modifier l\'étape',
        'delete' => 'Supprimer l\'étape',
        'name' => 'Nom de l\'étape',
        'type' => 'Type d\'étape',
        'configuration' => 'Configuration',
        'conditions' => 'Conditions',
        'enabled' => 'Activé',
        'disabled' => 'Désactivé',
    ],

    'step_types' => [
        'action' => 'Action',
        'condition' => 'Condition',
        'delay' => 'Délai',
        'notification' => 'Notification',
        'webhook' => 'Webhook',
        'event' => 'Événement',
        'job' => 'Tâche',
    ],

    'execution' => [
        'title' => 'Exécutions',
        'started_at' => 'Démarré le',
        'completed_at' => 'Terminé le',
        'duration' => 'Durée',
        'error' => 'Erreur',
        'retry' => 'Réessayer',
        'logs' => 'Journaux d\'exécution',
        'context' => 'Contexte',
        'output' => 'Sortie',
    ],

    'template' => [
        'title' => 'Modèles',
        'save_as' => 'Enregistrer comme modèle',
        'load' => 'Charger le modèle',
        'export' => 'Exporter le modèle',
        'import' => 'Importer le modèle',
        'name' => 'Nom du modèle',
    ],

    'builder' => [
        'title' => 'Constructeur de flux de travail',
        'zoom_in' => 'Zoom avant',
        'zoom_out' => 'Zoom arrière',
        'reset_zoom' => 'Réinitialiser le zoom',
        'grid_snap' => 'Aligner sur la grille',
        'save' => 'Enregistrer',
        'toolbar' => 'Barre d\'outils',
    ],

    'messages' => [
        'success' => [
            'created' => 'Flux de travail créé avec succès',
            'updated' => 'Flux de travail mis à jour avec succès',
            'deleted' => 'Flux de travail supprimé avec succès',
            'executed' => 'Flux de travail exécuté avec succès',
            'saved' => 'Modifications enregistrées avec succès',
        ],
        'error' => [
            'not_found' => 'Flux de travail introuvable',
            'execution_failed' => 'L\'exécution du flux de travail a échoué',
            'validation_failed' => 'La validation a échoué',
            'unauthorized' => 'Action non autorisée',
        ],
    ],

    'validation' => [
        'required' => 'Le champ :attribute est requis',
        'string' => 'Le champ :attribute doit être une chaîne',
        'max' => 'Le champ :attribute ne peut pas dépasser :max caractères',
        'array' => 'Le champ :attribute doit être un tableau',
    ],

    // Version History (v1.2.0)
    'version_history' => [
        'title' => 'Historique des versions',
        'no_versions' => 'Aucune version disponible',
        'versions' => 'Versions',
        'version_details' => 'Détails de la version',
        'version_number' => 'Version',
        'created' => 'Créée',
        'steps_count' => 'Étapes',
        'restored_at' => 'Restaurée le',
        'restored' => 'Restaurée',
        'steps' => 'étapes',
        'by' => 'par',
        'select_version' => 'Sélectionnez une version pour voir les détails',
        'compare' => 'Comparer',
        'restore' => 'Restaurer la version',
        'comparison' => 'Comparaison de versions',
        'steps_added' => 'Étapes ajoutées/supprimées',
        'steps_modified' => 'Étapes modifiées',
        'config_changed' => 'Configuration modifiée',
        'confirm_rollback' => 'Confirmer la restauration',
        'rollback_warning' => 'Êtes-vous sûr de vouloir restaurer cette version ? Cela remplacera la configuration et les étapes actuelles du flux de travail.',
        'note' => 'Remarque',
        'backup_created' => 'Une sauvegarde de l\'état actuel sera créée automatiquement avant la restauration.',
        'confirm_restore' => 'Restaurer la version',
    ],

    'yes' => 'Oui',
    'no' => 'Non',
    'cancel' => 'Annuler',
];

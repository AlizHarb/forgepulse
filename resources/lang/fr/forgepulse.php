<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ForgePulse Language Lines (French)
    |--------------------------------------------------------------------------
    */

    'workflow' => [
        'title' => 'Flux de Travail',
        'create' => 'Créer un Flux',
        'edit' => 'Modifier le Flux',
        'delete' => 'Supprimer le Flux',
        'name' => 'Nom du Flux',
        'description' => 'Description',
        'status' => 'Statut',
        'created_at' => 'Créé le',
        'updated_at' => 'Mis à jour le',
        'execute' => 'Exécuter le Flux',
        'save' => 'Enregistrer le Flux',
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
        'add' => 'Ajouter une Étape',
        'edit' => 'Modifier l\'Étape',
        'delete' => 'Supprimer l\'Étape',
        'name' => 'Nom de l\'Étape',
        'type' => 'Type d\'Étape',
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
        'logs' => 'Journaux d\'Exécution',
        'context' => 'Contexte',
        'output' => 'Sortie',
    ],

    'template' => [
        'title' => 'Modèles',
        'save_as' => 'Enregistrer comme Modèle',
        'load' => 'Charger le Modèle',
        'export' => 'Exporter le Modèle',
        'import' => 'Importer le Modèle',
        'name' => 'Nom du Modèle',
    ],

    'builder' => [
        'title' => 'Constructeur de Flux',
        'zoom_in' => 'Zoom Avant',
        'zoom_out' => 'Zoom Arrière',
        'reset_zoom' => 'Réinitialiser le Zoom',
        'grid_snap' => 'Alignement sur la Grille',
        'save' => 'Enregistrer',
        'toolbar' => 'Barre d\'Outils',
    ],

    'messages' => [
        'success' => [
            'created' => 'Flux créé avec succès',
            'updated' => 'Flux mis à jour avec succès',
            'deleted' => 'Flux supprimé avec succès',
            'executed' => 'Flux exécuté avec succès',
            'saved' => 'Modifications enregistrées avec succès',
        ],
        'error' => [
            'not_found' => 'Flux introuvable',
            'execution_failed' => 'Échec de l\'exécution du flux',
            'validation_failed' => 'Échec de la validation',
            'unauthorized' => 'Action non autorisée',
        ],
    ],

    'validation' => [
        'required' => 'Le champ :attribute est obligatoire',
        'string' => 'Le :attribute doit être une chaîne',
        'max' => 'Le :attribute ne peut pas dépasser :max caractères',
        'array' => 'Le :attribute doit être un tableau',
    ],
];

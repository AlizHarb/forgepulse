<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FlowForge Language Lines (German)
    |--------------------------------------------------------------------------
    */

    'workflow' => [
        'title' => 'Arbeitsabläufe',
        'create' => 'Arbeitsablauf Erstellen',
        'edit' => 'Arbeitsablauf Bearbeiten',
        'delete' => 'Arbeitsablauf Löschen',
        'name' => 'Arbeitsablauf Name',
        'description' => 'Beschreibung',
        'status' => 'Status',
        'created_at' => 'Erstellt am',
        'updated_at' => 'Aktualisiert am',
        'execute' => 'Arbeitsablauf Ausführen',
        'save' => 'Arbeitsablauf Speichern',
        'cancel' => 'Abbrechen',
    ],

    'status' => [
        'draft' => 'Entwurf',
        'active' => 'Aktiv',
        'inactive' => 'Inaktiv',
        'archived' => 'Archiviert',
        'pending' => 'Ausstehend',
        'running' => 'Läuft',
        'completed' => 'Abgeschlossen',
        'failed' => 'Fehlgeschlagen',
        'cancelled' => 'Abgebrochen',
        'skipped' => 'Übersprungen',
    ],

    'step' => [
        'title' => 'Schritte',
        'add' => 'Schritt Hinzufügen',
        'edit' => 'Schritt Bearbeiten',
        'delete' => 'Schritt Löschen',
        'name' => 'Schritt Name',
        'type' => 'Schritt Typ',
        'configuration' => 'Konfiguration',
        'conditions' => 'Bedingungen',
        'enabled' => 'Aktiviert',
        'disabled' => 'Deaktiviert',
    ],

    'step_types' => [
        'action' => 'Aktion',
        'condition' => 'Bedingung',
        'delay' => 'Verzögerung',
        'notification' => 'Benachrichtigung',
        'webhook' => 'Webhook',
        'event' => 'Ereignis',
        'job' => 'Auftrag',
    ],

    'execution' => [
        'title' => 'Ausführungen',
        'started_at' => 'Gestartet am',
        'completed_at' => 'Abgeschlossen am',
        'duration' => 'Dauer',
        'error' => 'Fehler',
        'retry' => 'Wiederholen',
        'logs' => 'Ausführungsprotokolle',
        'context' => 'Kontext',
        'output' => 'Ausgabe',
    ],

    'template' => [
        'title' => 'Vorlagen',
        'save_as' => 'Als Vorlage Speichern',
        'load' => 'Vorlage Laden',
        'export' => 'Vorlage Exportieren',
        'import' => 'Vorlage Importieren',
        'name' => 'Vorlagenname',
    ],

    'builder' => [
        'title' => 'Arbeitsablauf-Builder',
        'zoom_in' => 'Vergrößern',
        'zoom_out' => 'Verkleinern',
        'reset_zoom' => 'Zoom Zurücksetzen',
        'grid_snap' => 'Raster Einrasten',
        'save' => 'Speichern',
        'toolbar' => 'Symbolleiste',
    ],

    'messages' => [
        'success' => [
            'created' => 'Arbeitsablauf erfolgreich erstellt',
            'updated' => 'Arbeitsablauf erfolgreich aktualisiert',
            'deleted' => 'Arbeitsablauf erfolgreich gelöscht',
            'executed' => 'Arbeitsablauf erfolgreich ausgeführt',
            'saved' => 'Änderungen erfolgreich gespeichert',
        ],
        'error' => [
            'not_found' => 'Arbeitsablauf nicht gefunden',
            'execution_failed' => 'Ausführung des Arbeitsablaufs fehlgeschlagen',
            'validation_failed' => 'Validierung fehlgeschlagen',
            'unauthorized' => 'Nicht autorisierte Aktion',
        ],
    ],

    'validation' => [
        'required' => 'Das Feld :attribute ist erforderlich',
        'string' => 'Das :attribute muss eine Zeichenkette sein',
        'max' => 'Das :attribute darf nicht größer als :max Zeichen sein',
        'array' => 'Das :attribute muss ein Array sein',
    ],
];

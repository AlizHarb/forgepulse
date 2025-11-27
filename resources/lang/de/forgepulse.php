<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ForgePulse Language Lines (German)
    |--------------------------------------------------------------------------
    */

    'workflow' => [
        'title' => 'Workflows',
        'create' => 'Workflow erstellen',
        'edit' => 'Workflow bearbeiten',
        'delete' => 'Workflow löschen',
        'name' => 'Workflow-Name',
        'description' => 'Beschreibung',
        'status' => 'Status',
        'created_at' => 'Erstellt am',
        'updated_at' => 'Aktualisiert am',
        'execute' => 'Workflow ausführen',
        'save' => 'Workflow speichern',
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
        'add' => 'Schritt hinzufügen',
        'edit' => 'Schritt bearbeiten',
        'delete' => 'Schritt löschen',
        'name' => 'Schrittname',
        'type' => 'Schritttyp',
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
        'save_as' => 'Als Vorlage speichern',
        'load' => 'Vorlage laden',
        'export' => 'Vorlage exportieren',
        'import' => 'Vorlage importieren',
        'name' => 'Vorlagenname',
    ],

    'builder' => [
        'title' => 'Workflow-Builder',
        'zoom_in' => 'Vergrößern',
        'zoom_out' => 'Verkleinern',
        'reset_zoom' => 'Zoom zurücksetzen',
        'grid_snap' => 'Am Raster ausrichten',
        'save' => 'Speichern',
        'toolbar' => 'Symbolleiste',
    ],

    'messages' => [
        'success' => [
            'created' => 'Workflow erfolgreich erstellt',
            'updated' => 'Workflow erfolgreich aktualisiert',
            'deleted' => 'Workflow erfolgreich gelöscht',
            'executed' => 'Workflow erfolgreich ausgeführt',
            'saved' => 'Änderungen erfolgreich gespeichert',
        ],
        'error' => [
            'not_found' => 'Workflow nicht gefunden',
            'execution_failed' => 'Workflow-Ausführung fehlgeschlagen',
            'validation_failed' => 'Validierung fehlgeschlagen',
            'unauthorized' => 'Nicht autorisierte Aktion',
        ],
    ],

    'validation' => [
        'required' => 'Das Feld :attribute ist erforderlich',
        'string' => 'Das Feld :attribute muss eine Zeichenkette sein',
        'max' => 'Das Feld :attribute darf nicht mehr als :max Zeichen haben',
        'array' => 'Das Feld :attribute muss ein Array sein',
    ],

    // Version History (v1.2.0)
    'version_history' => [
        'title' => 'Versionsverlauf',
        'no_versions' => 'Keine Versionen verfügbar',
        'versions' => 'Versionen',
        'version_details' => 'Versionsdetails',
        'version_number' => 'Version',
        'created' => 'Erstellt',
        'steps_count' => 'Schritte',
        'restored_at' => 'Wiederhergestellt am',
        'restored' => 'Wiederhergestellt',
        'steps' => 'Schritte',
        'by' => 'von',
        'select_version' => 'Wählen Sie eine Version aus, um Details anzuzeigen',
        'compare' => 'Vergleichen',
        'restore' => 'Version wiederherstellen',
        'comparison' => 'Versionsvergleich',
        'steps_added' => 'Schritte hinzugefügt/entfernt',
        'steps_modified' => 'Schritte geändert',
        'config_changed' => 'Konfiguration geändert',
        'confirm_rollback' => 'Wiederherstellung bestätigen',
        'rollback_warning' => 'Sind Sie sicher, dass Sie diese Version wiederherstellen möchten? Dies ersetzt die aktuelle Workflow-Konfiguration und -Schritte.',
        'note' => 'Hinweis',
        'backup_created' => 'Vor der Wiederherstellung wird automatisch eine Sicherung des aktuellen Zustands erstellt.',
        'confirm_restore' => 'Version wiederherstellen',
    ],

    'yes' => 'Ja',
    'no' => 'Nein',
    'cancel' => 'Abbrechen',
];

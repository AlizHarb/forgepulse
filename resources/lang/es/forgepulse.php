<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ForgePulse Language Lines (Spanish)
    |--------------------------------------------------------------------------
    */

    'workflow' => [
        'title' => 'Flujos de trabajo',
        'create' => 'Crear flujo de trabajo',
        'edit' => 'Editar flujo de trabajo',
        'delete' => 'Eliminar flujo de trabajo',
        'name' => 'Nombre del flujo de trabajo',
        'description' => 'Descripción',
        'status' => 'Estado',
        'created_at' => 'Creado el',
        'updated_at' => 'Actualizado el',
        'execute' => 'Ejecutar flujo de trabajo',
        'save' => 'Guardar flujo de trabajo',
        'cancel' => 'Cancelar',
    ],

    'status' => [
        'draft' => 'Borrador',
        'active' => 'Activo',
        'inactive' => 'Inactivo',
        'archived' => 'Archivado',
        'pending' => 'Pendiente',
        'running' => 'En ejecución',
        'completed' => 'Completado',
        'failed' => 'Fallido',
        'cancelled' => 'Cancelado',
        'skipped' => 'Omitido',
    ],

    'step' => [
        'title' => 'Pasos',
        'add' => 'Agregar paso',
        'edit' => 'Editar paso',
        'delete' => 'Eliminar paso',
        'name' => 'Nombre del paso',
        'type' => 'Tipo de paso',
        'configuration' => 'Configuración',
        'conditions' => 'Condiciones',
        'enabled' => 'Habilitado',
        'disabled' => 'Deshabilitado',
    ],

    'step_types' => [
        'action' => 'Acción',
        'condition' => 'Condición',
        'delay' => 'Retraso',
        'notification' => 'Notificación',
        'webhook' => 'Webhook',
        'event' => 'Evento',
        'job' => 'Trabajo',
    ],

    'execution' => [
        'title' => 'Ejecuciones',
        'started_at' => 'Iniciado el',
        'completed_at' => 'Completado el',
        'duration' => 'Duración',
        'error' => 'Error',
        'retry' => 'Reintentar',
        'logs' => 'Registros de ejecución',
        'context' => 'Contexto',
        'output' => 'Salida',
    ],

    'template' => [
        'title' => 'Plantillas',
        'save_as' => 'Guardar como plantilla',
        'load' => 'Cargar plantilla',
        'export' => 'Exportar plantilla',
        'import' => 'Importar plantilla',
        'name' => 'Nombre de la plantilla',
    ],

    'builder' => [
        'title' => 'Constructor de flujos de trabajo',
        'zoom_in' => 'Acercar',
        'zoom_out' => 'Alejar',
        'reset_zoom' => 'Restablecer zoom',
        'grid_snap' => 'Ajustar a la cuadrícula',
        'save' => 'Guardar',
        'toolbar' => 'Barra de herramientas',
    ],

    'messages' => [
        'success' => [
            'created' => 'Flujo de trabajo creado exitosamente',
            'updated' => 'Flujo de trabajo actualizado exitosamente',
            'deleted' => 'Flujo de trabajo eliminado exitosamente',
            'executed' => 'Flujo de trabajo ejecutado exitosamente',
            'saved' => 'Cambios guardados exitosamente',
        ],
        'error' => [
            'not_found' => 'Flujo de trabajo no encontrado',
            'execution_failed' => 'La ejecución del flujo de trabajo falló',
            'validation_failed' => 'La validación falló',
            'unauthorized' => 'Acción no autorizada',
        ],
    ],

    'validation' => [
        'required' => 'El campo :attribute es obligatorio',
        'string' => 'El campo :attribute debe ser una cadena',
        'max' => 'El campo :attribute no puede tener más de :max caracteres',
        'array' => 'El campo :attribute debe ser un array',
    ],

    // Version History (v1.2.0)
    'version_history' => [
        'title' => 'Historial de versiones',
        'no_versions' => 'No hay versiones disponibles',
        'versions' => 'Versiones',
        'version_details' => 'Detalles de la versión',
        'version_number' => 'Versión',
        'created' => 'Creada',
        'steps_count' => 'Pasos',
        'restored_at' => 'Restaurada el',
        'restored' => 'Restaurada',
        'steps' => 'pasos',
        'by' => 'por',
        'select_version' => 'Seleccione una versión para ver los detalles',
        'compare' => 'Comparar',
        'restore' => 'Restaurar versión',
        'comparison' => 'Comparación de versiones',
        'steps_added' => 'Pasos agregados/eliminados',
        'steps_modified' => 'Pasos modificados',
        'config_changed' => 'Configuración modificada',
        'confirm_rollback' => 'Confirmar restauración',
        'rollback_warning' => '¿Está seguro de que desea restaurar esta versión? Esto reemplazará la configuración y los pasos actuales del flujo de trabajo.',
        'note' => 'Nota',
        'backup_created' => 'Se creará automáticamente una copia de seguridad del estado actual antes de restaurar.',
        'confirm_restore' => 'Restaurar versión',
    ],

    'yes' => 'Sí',
    'no' => 'No',
    'cancel' => 'Cancelar',
];

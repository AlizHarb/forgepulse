<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ForgePulse Language Lines (Spanish)
    |--------------------------------------------------------------------------
    */

    'workflow' => [
        'title' => 'Flujos de Trabajo',
        'create' => 'Crear Flujo de Trabajo',
        'edit' => 'Editar Flujo de Trabajo',
        'delete' => 'Eliminar Flujo de Trabajo',
        'name' => 'Nombre del Flujo',
        'description' => 'Descripción',
        'status' => 'Estado',
        'created_at' => 'Creado el',
        'updated_at' => 'Actualizado el',
        'execute' => 'Ejecutar Flujo',
        'save' => 'Guardar Flujo',
        'cancel' => 'Cancelar',
    ],

    'status' => [
        'draft' => 'Borrador',
        'active' => 'Activo',
        'inactive' => 'Inactivo',
        'archived' => 'Archivado',
        'pending' => 'Pendiente',
        'running' => 'Ejecutando',
        'completed' => 'Completado',
        'failed' => 'Fallido',
        'cancelled' => 'Cancelado',
        'skipped' => 'Omitido',
    ],

    'step' => [
        'title' => 'Pasos',
        'add' => 'Agregar Paso',
        'edit' => 'Editar Paso',
        'delete' => 'Eliminar Paso',
        'name' => 'Nombre del Paso',
        'type' => 'Tipo de Paso',
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
        'logs' => 'Registros de Ejecución',
        'context' => 'Contexto',
        'output' => 'Salida',
    ],

    'template' => [
        'title' => 'Plantillas',
        'save_as' => 'Guardar como Plantilla',
        'load' => 'Cargar Plantilla',
        'export' => 'Exportar Plantilla',
        'import' => 'Importar Plantilla',
        'name' => 'Nombre de Plantilla',
    ],

    'builder' => [
        'title' => 'Constructor de Flujos',
        'zoom_in' => 'Acercar',
        'zoom_out' => 'Alejar',
        'reset_zoom' => 'Restablecer Zoom',
        'grid_snap' => 'Ajustar a Cuadrícula',
        'save' => 'Guardar',
        'toolbar' => 'Barra de Herramientas',
    ],

    'messages' => [
        'success' => [
            'created' => 'Flujo creado exitosamente',
            'updated' => 'Flujo actualizado exitosamente',
            'deleted' => 'Flujo eliminado exitosamente',
            'executed' => 'Flujo ejecutado exitosamente',
            'saved' => 'Cambios guardados exitosamente',
        ],
        'error' => [
            'not_found' => 'Flujo no encontrado',
            'execution_failed' => 'Ejecución del flujo fallida',
            'validation_failed' => 'Validación fallida',
            'unauthorized' => 'Acción no autorizada',
        ],
    ],

    'validation' => [
        'required' => 'El campo :attribute es obligatorio',
        'string' => 'El :attribute debe ser una cadena',
        'max' => 'El :attribute no puede ser mayor que :max caracteres',
        'array' => 'El :attribute debe ser un arreglo',
    ],
];

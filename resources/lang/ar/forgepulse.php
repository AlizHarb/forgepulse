<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ForgePulse Language Lines (Arabic)
    |--------------------------------------------------------------------------
    */

    'workflow' => [
        'title' => 'سير العمل',
        'create' => 'إنشاء سير عمل',
        'edit' => 'تحرير سير العمل',
        'delete' => 'حذف سير العمل',
        'name' => 'اسم سير العمل',
        'description' => 'الوصف',
        'status' => 'الحالة',
        'created_at' => 'تم الإنشاء في',
        'updated_at' => 'تم التحديث في',
        'execute' => 'تنفيذ سير العمل',
        'save' => 'حفظ سير العمل',
        'cancel' => 'إلغاء',
    ],

    'status' => [
        'draft' => 'مسودة',
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'archived' => 'مؤرشف',
        'pending' => 'قيد الانتظار',
        'running' => 'قيد التشغيل',
        'completed' => 'مكتمل',
        'failed' => 'فشل',
        'cancelled' => 'ملغى',
        'skipped' => 'متجاوز',
    ],

    'step' => [
        'title' => 'الخطوات',
        'add' => 'إضافة خطوة',
        'edit' => 'تحرير الخطوة',
        'delete' => 'حذف الخطوة',
        'name' => 'اسم الخطوة',
        'type' => 'نوع الخطوة',
        'configuration' => 'التكوين',
        'conditions' => 'الشروط',
        'enabled' => 'مفعل',
        'disabled' => 'معطل',
    ],

    'step_types' => [
        'action' => 'إجراء',
        'condition' => 'شرط',
        'delay' => 'تأخير',
        'notification' => 'إشعار',
        'webhook' => 'ويب هوك',
        'event' => 'حدث',
        'job' => 'مهمة',
    ],

    'execution' => [
        'title' => 'التنفيذات',
        'started_at' => 'بدأ في',
        'completed_at' => 'اكتمل في',
        'duration' => 'المدة',
        'error' => 'خطأ',
        'retry' => 'إعادة المحاولة',
        'logs' => 'سجلات التنفيذ',
        'context' => 'السياق',
        'output' => 'المخرجات',
    ],

    'template' => [
        'title' => 'القوالب',
        'save_as' => 'حفظ كقالب',
        'load' => 'تحميل القالب',
        'export' => 'تصدير القالب',
        'import' => 'استيراد القالب',
        'name' => 'اسم القالب',
    ],

    'builder' => [
        'title' => 'منشئ سير العمل',
        'zoom_in' => 'تكبير',
        'zoom_out' => 'تصغير',
        'reset_zoom' => 'إعادة تعيين التكبير',
        'grid_snap' => 'محاذاة الشبكة',
        'save' => 'حفظ',
        'toolbar' => 'شريط الأدوات',
    ],

    'messages' => [
        'success' => [
            'created' => 'تم إنشاء سير العمل بنجاح',
            'updated' => 'تم تحديث سير العمل بنجاح',
            'deleted' => 'تم حذف سير العمل بنجاح',
            'executed' => 'تم تنفيذ سير العمل بنجاح',
            'saved' => 'تم حفظ التغييرات بنجاح',
        ],
        'error' => [
            'not_found' => 'سير العمل غير موجود',
            'execution_failed' => 'فشل تنفيذ سير العمل',
            'validation_failed' => 'فشل التحقق',
            'unauthorized' => 'إجراء غير مصرح به',
        ],
    ],

    'validation' => [
        'required' => 'حقل :attribute مطلوب',
        'string' => 'يجب أن يكون حقل :attribute نصاً',
        'max' => 'لا يمكن أن يتجاوز حقل :attribute :max حرفاً',
        'array' => 'يجب أن يكون حقل :attribute مصفوفة',
    ],

    // Version History (v1.2.0)
    'version_history' => [
        'title' => 'سجل الإصدارات',
        'no_versions' => 'لا توجد إصدارات متاحة',
        'versions' => 'الإصدارات',
        'version_details' => 'تفاصيل الإصدار',
        'version_number' => 'الإصدار',
        'created' => 'تم الإنشاء',
        'steps_count' => 'الخطوات',
        'restored_at' => 'تم الاستعادة في',
        'restored' => 'تم الاستعادة',
        'steps' => 'خطوات',
        'by' => 'بواسطة',
        'select_version' => 'حدد إصداراً لعرض التفاصيل',
        'compare' => 'مقارنة',
        'restore' => 'استعادة الإصدار',
        'comparison' => 'مقارنة الإصدارات',
        'steps_added' => 'الخطوات المضافة/المحذوفة',
        'steps_modified' => 'الخطوات المعدلة',
        'config_changed' => 'التكوين المعدل',
        'confirm_rollback' => 'تأكيد الاستعادة',
        'rollback_warning' => 'هل أنت متأكد من أنك تريد استعادة هذا الإصدار؟ سيؤدي هذا إلى استبدال تكوين وخطوات سير العمل الحالية.',
        'note' => 'ملاحظة',
        'backup_created' => 'سيتم إنشاء نسخة احتياطية من الحالة الحالية تلقائياً قبل الاستعادة.',
        'confirm_restore' => 'استعادة الإصدار',
    ],

    'yes' => 'نعم',
    'no' => 'لا',
    'cancel' => 'إلغاء',
];

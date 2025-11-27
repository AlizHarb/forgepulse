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
        'edit' => 'تعديل سير العمل',
        'delete' => 'حذف سير العمل',
        'name' => 'اسم سير العمل',
        'description' => 'الوصف',
        'status' => 'الحالة',
        'created_at' => 'تاريخ الإنشاء',
        'updated_at' => 'تاريخ التحديث',
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
        'skipped' => 'متخطى',
    ],

    'step' => [
        'title' => 'الخطوات',
        'add' => 'إضافة خطوة',
        'edit' => 'تعديل الخطوة',
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
        'title' => 'عمليات التنفيذ',
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
            'validation_failed' => 'فشل التحقق من الصحة',
            'unauthorized' => 'إجراء غير مصرح به',
        ],
    ],

    'validation' => [
        'required' => 'حقل :attribute مطلوب',
        'string' => 'يجب أن يكون :attribute نصاً',
        'max' => 'يجب ألا يتجاوز :attribute :max حرفاً',
        'array' => 'يجب أن يكون :attribute مصفوفة',
    ],
];

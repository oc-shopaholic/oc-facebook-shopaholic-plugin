<?php return [
    'plugin'     => [
        'name'        => 'Экспорт товаров в Facebook',
        'description' => 'Генерация XML файла для интеграции с Facebook',
    ],
    'menu'       => [
        'settings'             => 'Экспорт в Facebook',
        'settings_description' => 'Настройка экспорта каталога в Facebook',
    ],
    'field'      => [
        'code_model_for_images'                      => 'Получать изображения из:',
        'section_management_additional_fields_offer' => 'Дополнительные поля',
        'property_color'                             => 'Цвет',
        'property_material'                          => 'Материал',
        'property_size'                              => 'Размер',
        'field_offer_properties'                     => 'Свойства товарного рпедложения',
        'facebook_property'                          => 'Свойство (Facebook)',
        'shopaholic_property'                        => 'Свойство (Shopaholic)',
        'section_facebook'                           => 'Facebook',
    ],
    'button'     => [
        'export_catalog_to_xml' => 'Запуск экспорта',
        'download'              => 'Скачать',
    ],
    'widget'     => [
        'export_catalog_to_xml_for_facebook' => 'Экспорт каталога в Facebook',
    ],
    'permission' => [
        'facebooksettings' => 'Управление настройками экспорта для Facebook',
    ],
    'message'    => [
        'export_is_complete'            => 'Экспорт завершен',
        'update_catalog_to_xml_confirm' => 'Запустить экспорт каталога в XML файл?',
    ],
];

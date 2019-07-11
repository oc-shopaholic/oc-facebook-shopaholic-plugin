<?php return [
    'plugin'     => [
        'name'        => 'Экспорт товаров для Facebook',
        'description' => 'Интеграция через ATOM формата',
    ],
    'menu'       => [
        'facebooksettings' => 'Экспорт в Facebook',
    ],
    'component'  => [],
    'tab'        => [
        'store' => 'Магазин',
    ],
    'field'      => [
        'code_model_for_images'                      => 'Откуда брать изображения?',
        'path_to_export_the_file'                    => 'Путь для экспорта файла (по умолчанию storage/app/media/facebook_atom.xml)',
        'section_management_additional_fields_offer' => 'Управление дополнительными полями',
        'property_color'                             => 'Цвет',
        'property_material'                          => 'Материал',
        'property_size'                              => 'Размер',
        'field_offer_properties'                     => 'Свойства товарного рпедложения',
        'facebook_property'                          => 'Свойство (Facebook)',
        'shopaholic_property'                        => 'Свойство (Shopaholic)',
        'section_facebook'                           => 'Facebook',
    ],
    'button'     => [
        'export_catalog_to_xml' => 'Обновить каталог в XML файл',
        'download'              => 'Скачать',
    ],
    'widget'     => [
        'export_catalog_to_xml_for_facebook' => 'Экспорт каталога в XML файл для Facebook',
    ],
    'permission' => [
        'facebooksettings' => 'Управление экспортом для Facebook',
    ],
    'message'    => [
        'export_is_complete'            => 'Экспорт завершен',
        'update_catalog_to_xml_confirm' => 'Обновить каталог в XML файл?',
    ],
];

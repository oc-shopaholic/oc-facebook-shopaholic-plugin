<?php return [
    'plugin'     => [
        'name'        => 'Экспорт каталога для Facebook',
        'description' => 'Генерация XML файла для Facebook в формате ATOM',
    ],
    'menu'       => [
        'facebooksettings' => 'Экспорт каталога для Facebook',
    ],
    'component'  => [],
    'tab'        => [
        'store' => 'Магазин',
    ],
    'field'      => [
        'code_model_for_images'                      => 'Откуда брать изображения?',
        'path_to_export_the_file'                    => 'Путь для экспорта файла (по умолчанию app/facebook_atom.xml)',
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
        'export_catalog_to_xml' => 'Экспорт каталога в XML файл',
    ],
    'widget'     => [
        'export_catalog_to_xml_for_facebook' => 'Экспорт каталога в XML файл для Facebook',
    ],
    'permission' => [
        'facebooksettings' => 'Управление экспортом каталога для Facebook',
    ],
    'message'    => [
        'export_is_complete' => 'Экспорт завершен',
    ],
];

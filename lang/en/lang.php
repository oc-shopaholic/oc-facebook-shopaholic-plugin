<?php return [
    'plugin'     => [
        'name'        => 'Export catalog in Facebook',
        'description' => 'Generation XML file for integration with Facebook',
    ],
    'menu'       => [
        'settings'             => 'Export to Facebook',
        'settings_description' => 'Configure export catalog to Facebook',
    ],
    'field'      => [
        'code_model_for_images'                      => 'Get images from:',
        'section_management_additional_fields_offer' => 'Additional fields',
        'property_color'                             => 'Color',
        'property_material'                          => 'Material',
        'property_size'                              => 'Size',
        'field_offer_properties'                     => 'Offer properties',
        'facebook_property'                          => 'Property (Facebook)',
        'shopaholic_property'                        => 'Property (Shopaholic)',
        'section_facebook'                           => 'Facebook',
    ],
    'button'     => [
        'export_catalog_to_xml' => 'Run export',
        'download'              => 'Download',
    ],
    'widget'     => [
        'export_catalog_to_xml_for_facebook' => 'Export catalog to Facebook',
    ],
    'permission' => [
        'facebooksettings' => 'Manager settings of catalog export to Facebook',
    ],
    'message'    => [
        'export_is_completed'           => 'Export is completed',
        'update_catalog_to_xml_confirm' => 'Run export catalog to XML file?',
    ],
];

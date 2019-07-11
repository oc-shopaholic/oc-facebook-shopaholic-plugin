<?php return [
    'plugin'     => [
        'name'        => 'Export products for Facebook',
        'description' => 'Integration through ATOM format',
    ],
    'menu'       => [
        'facebooksettings' => 'Export to Facebook',
    ],
    'component'  => [],
    'tab'        => [
        'store' => 'Store',
    ],
    'field'      => [
        'code_model_for_images'                      => 'Where to get the images?',
        'path_to_export_the_file'                    => 'Path to export the file (default storage/app/media/facebook_atom.xml)',
        'section_management_additional_fields_offer' => 'Management additional fields',
        'property_color'                             => 'Color',
        'property_material'                          => 'Material',
        'property_size'                              => 'Size',
        'field_offer_properties'                     => 'Offer properties',
        'facebook_property'                          => 'Property (Facebook)',
        'shopaholic_property'                        => 'Property (Shopaholic)',
        'section_facebook'                           => 'Facebook',
    ],
    'button'     => [
        'export_catalog_to_xml' => 'Update catalog to XML file',
        'download'              => 'Download',
    ],
    'widget'     => [
        'export_catalog_to_xml_for_facebook' => 'Export catalog to XML for Facebook',
    ],
    'permission' => [
        'facebooksettings' => 'Manager export for Facebook',
    ],
    'message'    => [
        'export_is_complete'            => 'Export is complete',
        'update_catalog_to_xml_confirm' => 'Update catalog to XML file?',
    ],
];

<?php return [
    'plugin'     => [
        'name'        => 'Export catalog for Facebook',
        'description' => 'Generating XML file for Facebook in ATOM format',
    ],
    'menu'       => [
        'facebooksettings' => 'Export catalog to Facebook',
    ],
    'component'  => [],
    'tab'        => [
        'store' => 'Store',
    ],
    'field'      => [
        'code_model_for_images'                      => 'Where to get the images?',
        'path_to_export_the_file'                    => 'Path to export the file (default app/facebook_atom.xml)',
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
        'export_catalog_to_xml' => 'Export catalog to XML file',
    ],
    'widget'     => [
        'export_catalog_to_xml_for_facebook' => 'Export catalog to XML for Facebook',
    ],
    'permission' => [
        'facebooksettings' => 'Manager export catalog for Facebook',
    ],
    'message'    => [
        'export_is_complete' => 'Export is complete',
    ],
];

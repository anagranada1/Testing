<?php

$returnBase = rtrim((string) env('PUBLIC_BASE_URL', env('APP_URL', 'http://localhost')), '/');

// PAR es PatPass Comercio: no vive en el host de Webpay sino en el suyo
// (integración pagoautomaticocontarjetasint.transbank.cl, producción
// www.pagoautomaticocontarjetas.cl) y autentica con Commercecode/Authorization.
$parBaseUrl = rtrim((string) env('PAR_BASE_URL', 'https://pagoautomaticocontarjetasint.transbank.cl'), '/');

return [
    'kind' => 'pipeline',
    'group' => 'Pipelines',
    'label' => 'PAR',
    'multi_step' => true,
    'base_url' => $parBaseUrl,
    'base_path' => '/restpatpass',
    'versions' => [
        'v1' => [
            'steps' => [
                'crear' => [
                    'label' => 'Iniciar Inscripción',
                    'method' => 'POST',
                    'path' => '/services/patInscription',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Commercecode' => '28299257',
                                'Authorization' => 'cxxXQgGD9vrVe4M41FIt',
                            ],
                            'body' => '{
    "url": "'.$returnBase.'/forms/par/return",
    "commerceCode": "28299257",
    "finalUrl": "'.$returnBase.'/forms/par/return",
    "serviceId": "324123",
    "nombre": "Diego",
    "pApellido": "Sanchez",
    "sApellido": "Valdovinos",
    "rut": "11111111-1",
    "montoMaximo": "",
    "telefonoFijo": "57508624",
    "telefonoCelular": "57508624",
    "nombrePatPass": "Help - 8050014",
    "correoPersona": "persona@test.cl",
    "correoComercio": "comercio@test.cl",
    "direccion": "Merced 156, Santiago, Chile",
    "ciudad": "Santiago"
}',
                        ],
                        'chargeInstructionAlreadyEntered' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Commercecode' => '28299257',
                                'Authorization' => 'cxxXQgGD9vrVe4M41FIt',
                            ],
                            'body' => '{
    "url": "'.$returnBase.'/forms/par/return",
    "commerceCode": "28299257",
    "finalUrl": "'.$returnBase.'/forms/par/return",
    "serviceId": "323123",
    "nombre": "Diego",
    "pApellido": "Sanchez",
    "sApellido": "Valdovinos",
    "rut": "11111111-1",
    "montoMaximo": "",
    "telefonoFijo": "57508624",
    "telefonoCelular": "57508624",
    "nombrePatPass": "Help - 8050014",
    "correoPersona": "persona@test.cl",
    "correoComercio": "comercio@test.cl",
    "direccion": "Merced 156, Santiago, Chile",
    "ciudad": "Santiago"
}',
                        ],
                    ],
                ],
                'mostrar' => [
                    'label' => 'Mostrar formulario',
                    'method' => 'POST',
                    'path' => '',
                    'presets' => [],
                    'browser_redirect' => [
                        'url' => '{{steps.crear.url}}',
                        'params' => [
                            'tokenComercio' => '{{steps.crear.token}}',
                        ],
                    ],
                ],
                'status' => [
                    'label' => 'Estado de una Inscripción',
                    'method' => 'POST',
                    'path' => '/services/status',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Commercecode' => '28299257',
                                'Authorization' => 'cxxXQgGD9vrVe4M41FIt',
                            ],
                            'body' => '{
    "token": "{{steps.crear.token}}"
}',
                        ],
                        'unexisting_token' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Commercecode' => '28299257',
                                'Authorization' => 'cxxXQgGD9vrVe4M41FIt',
                            ],
                            'body' => '{
    "token": "othertoken"
}',
                        ],
                    ],
                ],
                'voucher' => [
                    'label' => 'Ver voucher',
                    'method' => 'POST',
                    'path' => '',
                    'presets' => [],
                    'browser_redirect' => [
                        'url' => '{{steps.status.voucherUrl}}',
                        'params' => [
                            'tokenComercio' => '{{steps.crear.token}}',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

<?php

return [
    'kind' => 'pipeline',
    'group' => 'Pipelines',
    'label' => 'Webpay Mall',
    'multi_step' => true,
    'base_path' => '/rswebpaytransaction/api/webpay',
    'versions' => [
        'v1.0' => [
            'steps' => [
                'crear' => [
                    'label' => 'Crear transacción',
                    'method' => 'POST',
                    'path' => '/transactions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555535',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "return_url": "http://www.comercio.cl/webpay/retorno",
    "details": [
        {
            "amount": 10000,
            "commerce_code": "597055555536",
            "buy_order": "ordenCompraTienda1"
        },
        {
            "amount": 5000,
            "commerce_code": "597055555537",
            "buy_order": "ordenCompraTienda2"
        }
    ]
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "return_url": "http://www.comercio.cl/webpay/retorno",
    "details": [
        {
            "amount": 10000,
            "commerce_code": "597055555536",
            "buy_order": "ordenCompraTienda1"
        }
    ]
}',
                        ],
                        'validation_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555535',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "",
    "session_id": "sesion12345",
    "return_url": "",
    "details": [
        {
            "amount": -1,
            "commerce_code": "597055555536",
            "buy_order": ""
        }
    ]
}',
                        ],
                    ],
                ],

                'confirmar' => [
                    'label' => 'Confirmar transacción',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555535',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            // El PDF muestra `token` en el body pero la API real
                            // responde 400 unrecognized field. El token va sólo en la URL.
                            'body' => '{}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{}',
                        ],
                    ],
                ],

                'estado' => [
                    'label' => 'Estado de la transacción',
                    'method' => 'GET',
                    'path' => '/transactions/{{steps.crear.token}}',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555535',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '',
                        ],
                    ],
                ],

                'reembolso' => [
                    'label' => 'Reembolso de la transacción',
                    'method' => 'POST',
                    'path' => '/transactions/{{steps.crear.token}}/refunds',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555535',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompraTienda1",
    "commerce_code": "597055555536",
    "amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555535',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompraTienda1",
    "commerce_code": "597055555536",
    "amount": 999999999
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "buy_order": "ordenCompraTienda1",
    "commerce_code": "597055555536",
    "amount": 1000
}',
                        ],
                    ],
                ],

                'captura' => [
                    'label' => 'Captura diferida',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}/capture',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555535',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555536",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "123456",
    "capture_amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555535',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555536",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "000000",
    "capture_amount": 1000
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "commerce_code": "597055555536",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "123456",
    "capture_amount": 1000
}',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

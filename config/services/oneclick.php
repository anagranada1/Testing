<?php

return [
    'kind' => 'pipeline',
    'group' => 'Pipelines',
    'label' => 'Oneclick',
    'multi_step' => true,
    'base_path' => '/rswebpaytransaction/api/oneclick',
    'versions' => [
        'v1.0' => [
            'steps' => [
                'crear' => [
                    'label' => 'Crear inscripción',
                    'method' => 'POST',
                    'path' => '/inscriptions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "email": "juan.perez@gmail.com",
    "response_url": "http://www.comercio.cl/return_inscription"
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

                'mostrar' => [
                    'label' => 'Mostrar formulario',
                    'method' => 'POST',
                    'path' => '',
                    'presets' => [],
                    'browser_redirect' => [
                        'url' => '{{steps.crear.url_webpay}}',
                        'params' => [
                            'TBK_TOKEN' => '{{steps.crear.token}}',
                        ],
                    ],
                ],

                'confirmar' => [
                    'label' => 'Confirmar inscripción (Finalizar)',
                    'method' => 'PUT',
                    'path' => '/inscriptions/{{steps.crear.token}}',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
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

                'eliminar' => [
                    'label' => 'Eliminar inscripción',
                    'method' => 'DELETE',
                    'path' => '/inscriptions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            // El PDF muestra `token` en el body pero la API real
                            // responde 400 unrecognized field. El token va sólo en la URL.
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}"
}',
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

                'authorize' => [
                    'label' => 'Autorizar pago',
                    'method' => 'POST',
                    'path' => '/transactions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "amount": 1000,
    "buy_order": "ordenCompraOneClick",
}',
                        ],
                        'multicódigo' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "buy_order": "ordenCompraOneClick",
    "details": [
        {
            "commerce_code": 597055555536,
            "buy_order": "ordenCompraTienda1",
            "amount": 1000,
            "installments_number": 2
        },
        {
            "commerce_code": 597055555536,
            "buy_order": "ordenCompraTienda2",
            "amount": 2000,
            "installments_number": 2
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
                            'body' => '{}',
                        ],
                    ],
                ],

                'status' => [
                    'label' => 'Estado de la transacción',
                    'method' => 'GET',
                    'path' => '/transactions/{{steps.authorize.buy_order}}',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "amount": 1000,
    "buy_order": "ordenCompraOneClick",
}',
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

                'refund' => [
                    'label' => 'Reembolso transacción',
                    'method' => 'GET',
                    'path' => '/transactions/{{steps.authorize.buy_order}}/refunds',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 20,
}',
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
            ],
        ],
    ],
];

<?php

$publicBase = rtrim((string) env('PUBLIC_BASE_URL', env('APP_URL', 'http://localhost')), '/');

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
    "return_url": "'.$publicBase.'/forms/webpay-mall/return",
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
                        // Mall de captura diferida (597055555581, tiendas 582/583): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "return_url": "'.$publicBase.'/forms/webpay-mall/return",
    "details": [
        {
            "amount": 10000,
            "commerce_code": "597055555582",
            "buy_order": "ordenCompraTienda1"
        },
        {
            "amount": 5000,
            "commerce_code": "597055555583",
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
    "return_url": "'.$publicBase.'/forms/webpay-mall/return",
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
                    'label' => 'Visitar proceso de pago',
                    'method' => 'POST',
                    'path' => '',
                    'presets' => [],
                    'browser_redirect' => [
                        'url' => '{{steps.crear.url}}',
                        'params' => [
                            'token_ws' => '{{steps.crear.token}}',
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
                        // Mall de captura diferida (597055555581, tiendas 582/583): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
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
                        // Mall de captura diferida (597055555581, tiendas 582/583): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
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
                        // Mall de captura diferida (597055555581, tiendas 582/583): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompraTienda1",
    "commerce_code": "597055555582",
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

                // Sólo mall de captura diferida (597055555581, tiendas 582/583); con el normal responde error 16.
                // Crea y confirma con el preset `diferido` para que la transacción sea de ese mall.
                // El anexo marca la captura Mall como no soportada en 1.0: puede ser rechazada.
                'captura' => [
                    'label' => 'Captura diferida',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}/capture',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
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
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                    ],
                ],
            ],
        ],
        'v1.2' => [
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
    "return_url": "'.$publicBase.'/forms/webpay-mall/return",
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
                        // Mall de captura diferida (597055555581, tiendas 582/583): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "return_url": "'.$publicBase.'/forms/webpay-mall/return",
    "details": [
        {
            "amount": 10000,
            "commerce_code": "597055555582",
            "buy_order": "ordenCompraTienda1"
        },
        {
            "amount": 5000,
            "commerce_code": "597055555583",
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
    "return_url": "'.$publicBase.'/forms/webpay-mall/return",
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
                    'label' => 'Visitar proceso de pago',
                    'method' => 'POST',
                    'path' => '',
                    'presets' => [],
                    'browser_redirect' => [
                        'url' => '{{steps.crear.url}}',
                        'params' => [
                            'token_ws' => '{{steps.crear.token}}',
                        ],
                    ],
                ],

                // El PDF muestra `token` en el body pero la API real
                // responde 400 unrecognized field. El token va sólo en la URL.
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
                            'body' => '{

}',
                        ],
                        // Mall de captura diferida (597055555581, tiendas 582/583): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{

}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{

}',
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
                        // Mall de captura diferida (597055555581, tiendas 582/583): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
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
                        // Mall de captura diferida (597055555581, tiendas 582/583): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompraTienda1",
    "commerce_code": "597055555582",
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

                // En Mall el authorization_code viene por tienda dentro de details[]
                // de la confirmación.
                // Sólo mall de captura diferida (597055555581, tiendas 582/583); con el normal responde error 16.
                // Crea y confirma con el preset `diferido` para que la transacción sea de ese mall.
                'captura' => [
                    'label' => 'Captura diferida',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}/capture',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
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
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                    ],
                ],
            ],
        ],
        'v1.3' => [
            'steps' => [
                'crear' => [
                    'label' => 'Crear transacción',
                    'method' => 'POST',
                    'path' => '/transactions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "return_url": "'.$publicBase.'/forms/webpay-mall/return",
    "details": [
        {
            "amount": 10000,
            "commerce_code": "597055555582",
            "buy_order": "ordenCompraTienda1"
        },
        {
            "amount": 5000,
            "commerce_code": "597055555583",
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
    "return_url": "'.$publicBase.'/forms/webpay-mall/return",
    "details": [
        {
            "amount": 10000,
            "commerce_code": "597055555582",
            "buy_order": "ordenCompraTienda1"
        }
    ]
}',
                        ],
                        'validation_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "",
    "session_id": "sesion12345",
    "return_url": "",
    "details": [
        {
            "amount": -1,
            "commerce_code": "597055555582",
            "buy_order": ""
        }
    ]
}',
                        ],
                    ],
                ],

                'mostrar' => [
                    'label' => 'Visitar proceso de pago',
                    'method' => 'POST',
                    'path' => '',
                    'presets' => [],
                    'browser_redirect' => [
                        'url' => '{{steps.crear.url}}',
                        'params' => [
                            'token_ws' => '{{steps.crear.token}}',
                        ],
                    ],
                ],

                // El PDF muestra `token` en el body pero la API real
                // responde 400 unrecognized field. El token va sólo en la URL.
                'confirmar' => [
                    'label' => 'Confirmar transacción',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{

}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{

}',
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
                                'Tbk-Api-Key-Id' => '597055555581',
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
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompraTienda1",
    "commerce_code": "597055555582",
    "amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompraTienda1",
    "commerce_code": "597055555582",
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
    "commerce_code": "597055555582",
    "amount": 1000
}',
                        ],
                    ],
                ],

                // En Mall el authorization_code viene por tienda dentro de details[]
                // de la confirmación.
                'captura' => [
                    'label' => 'Captura diferida',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}/capture',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
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
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                    ],
                ],

                'incremento_monto' => [
                    'label' => 'Incremento de monto pre-autorizado',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}/amount',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}",
    "amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "000000",
    "amount": 1000
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}",
    "amount": 1000
}',
                        ],
                    ],
                ],

                'incremento_plazo' => [
                    'label' => 'Incremento de plazo',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}/authorization_date',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}"
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "000000"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}"
}',
                        ],
                    ],
                ],

                'reversa_monto' => [
                    'label' => 'Reversa de monto pre-autorizado',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}/reverse/amount',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}",
    "amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "000000",
    "amount": 1000
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1",
    "authorization_code": "{{steps.confirmar.details.0.authorization_code}}",
    "amount": 1000
}',
                        ],
                    ],
                ],

                'historial' => [
                    'label' => 'Historial de captura diferida',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}/details',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555581',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "commerce_code": "597055555582",
    "buy_order": "ordenCompraTienda1"
}',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

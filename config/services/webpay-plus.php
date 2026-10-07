<?php

$publicBase = rtrim((string) env('PUBLIC_BASE_URL', env('APP_URL', 'http://localhost')), '/');

return [
    'kind' => 'pipeline',
    'group' => 'Pipelines',
    'label' => 'Webpay Plus',
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
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "return_url": "'.$publicBase.'/forms/webpay-plus/return"
}',
                        ],
                        // Comercio de captura diferida (597055555540): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "return_url": "'.$publicBase.'/forms/webpay-plus/return"
}',
                        ],
                        'mensual' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "return_url": "'.$publicBase.'/forms/webpay-plus/return",
    "wpm_detail": {
        "service_id": "123345567",
        "card_holder_id": "12345",
        "card_holder_name": "Juan",
        "card_holder_last_name1": "Perez",
        "card_holder_last_name2": "Gonzalez",
        "card_holder_mail": "juan.perez@gmail.com",
        "cellphone_number": "9912345678",
        "expiration_date": "2026-12-31T23:59:59Z",
        "commerce_mail": "contacto@comercio.cl",
        "uf_flag": false
    }
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
    "amount": 10000,
    "return_url": "'.$publicBase.'/forms/webpay-plus/return"
}',
                        ],
                        'validation_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "",
    "session_id": "sesion12345",
    "amount": -1,
    "return_url": ""
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
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            // El PDF muestra `token` en el body pero la API real
                            // responde 400 unrecognized field. El token va sólo en la URL.
                            'body' => '{}',
                        ],
                        // Comercio de captura diferida (597055555540): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
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
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '',
                        ],
                        // Comercio de captura diferida (597055555540): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
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
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 1000
}',
                        ],
                        // Comercio de captura diferida (597055555540): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
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
    "amount": 1000
}',
                        ],
                    ],
                ],

                // Sólo comercios de captura diferida (597055555540); con el normal Transbank responde error 16.
                // Crea y confirma con el preset `diferido` para que la transacción sea de ese comercio.
                'captura' => [
                    'label' => 'Captura diferida',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}/capture',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
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
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
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
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "return_url": "'.$publicBase.'/forms/webpay-plus/return"
}',
                        ],
                        // Comercio de captura diferida (597055555540): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "return_url": "'.$publicBase.'/forms/webpay-plus/return"
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
    "amount": 10000,
    "return_url": "'.$publicBase.'/forms/webpay-plus/return"
}',
                        ],
                        'validation_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "",
    "session_id": "sesion12345",
    "amount": -1,
    "return_url": ""
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
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{

}',
                        ],
                        // Comercio de captura diferida (597055555540): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
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
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '',
                        ],
                        // Comercio de captura diferida (597055555540): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
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
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 1000
}',
                        ],
                        // Comercio de captura diferida (597055555540): úsalo en todos los pasos si vas a probar la captura.
                        'diferido' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555532',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
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
    "amount": 1000
}',
                        ],
                    ],
                ],

                // Sólo comercios de captura diferida (597055555540); con el normal Transbank responde error 16.
                // Crea y confirma con el preset `diferido` para que la transacción sea de ese comercio.
                'captura' => [
                    'label' => 'Captura diferida',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}/capture',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
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
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
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
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "return_url": "'.$publicBase.'/forms/webpay-plus/return"
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
    "amount": 10000,
    "return_url": "'.$publicBase.'/forms/webpay-plus/return"
}',
                        ],
                        'validation_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "",
    "session_id": "sesion12345",
    "amount": -1,
    "return_url": ""
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
                                'Tbk-Api-Key-Id' => '597055555540',
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
                                'Tbk-Api-Key-Id' => '597055555540',
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
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
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
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
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
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                    ],
                ],

                // El anexo marca commerce_code como NA para 1.3 no-Mall y R para 1.3 Mall.
                'incremento_monto' => [
                    'label' => 'Incremento de monto pre-autorizado',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.crear.token}}/amount',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "ordenCompra12345",
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
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
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
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555540",
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}"
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555540",
    "buy_order": "ordenCompra12345",
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
    "commerce_code": "597055555540",
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}"
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
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555540",
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555540",
    "buy_order": "ordenCompra12345",
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
    "commerce_code": "597055555540",
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
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
                                'Tbk-Api-Key-Id' => '597055555540',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "597055555540",
    "buy_order": "ordenCompra12345"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "commerce_code": "597055555540",
    "buy_order": "ordenCompra12345"
}',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

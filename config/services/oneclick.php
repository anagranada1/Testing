<?php

return [
    'kind' => 'pipeline',
    'group' => 'Pipelines',
    'label' => 'Oneclick Mall',
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
                                'Tbk-Api-Key-Id' => '597055555541',
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
    "username": "juanperez",
    "email": "juan.perez@gmail.com",
    "response_url": "http://www.comercio.cl/return_inscription"
}',
                        ],
                        'validation_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "",
    "email": "correo-invalido",
    "response_url": ""
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

                // El PDF muestra `token` en el body pero la API real responde
                // 400 unrecognized field. El token va sólo en la URL.
                'confirmar' => [
                    'label' => 'Confirmar inscripción',
                    'method' => 'PUT',
                    'path' => '/inscriptions/{{steps.crear.token}}',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
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

                'eliminar' => [
                    'label' => 'Eliminar inscripción',
                    'method' => 'DELETE',
                    'path' => '/inscriptions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}"
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "tbk-user-inexistente"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}"
}',
                        ],
                    ],
                ],

                // El anexo marca `amount` como requerido y `details` como
                // condicional (sólo multicódigo) para 1.0/1.2/1.3.
                'authorize' => [
                    'label' => 'Autorizar pago',
                    'method' => 'POST',
                    'path' => '/transactions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "buy_order": "ordenCompraOneClick",
    "amount": 1000
}',
                        ],
                        'multicodigo' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "buy_order": "ordenCompraOneClick",
    "details": [
        {
            "commerce_code": "597055555542",
            "buy_order": "ordenCompraTienda1",
            "amount": 1000,
            "installments_number": 2
        },
        {
            "commerce_code": "597055555543",
            "buy_order": "ordenCompraTienda2",
            "amount": 2000,
            "installments_number": 2
        }
    ]
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "tbk-user-inexistente",
    "buy_order": "ordenCompraOneClick",
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
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "buy_order": "ordenCompraOneClick",
    "amount": 1000
}',
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
                                'Tbk-Api-Key-Id' => '597055555541',
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

                'refund' => [
                    'label' => 'Reembolso transacción',
                    'method' => 'POST',
                    'path' => '/transactions/{{steps.authorize.buy_order}}/refunds',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 1000,
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "detail_buy_order": "{{steps.authorize.details.0.buy_order}}"
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 999999999,
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "detail_buy_order": "{{steps.authorize.details.0.buy_order}}"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "amount": 1000,
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "detail_buy_order": "{{steps.authorize.details.0.buy_order}}"
}',
                        ],
                    ],
                ],
            ],
        ],
        'v1.2' => [
            'steps' => [
                'crear' => [
                    'label' => 'Crear inscripción',
                    'method' => 'POST',
                    'path' => '/inscriptions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
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
    "username": "juanperez",
    "email": "juan.perez@gmail.com",
    "response_url": "http://www.comercio.cl/return_inscription"
}',
                        ],
                        'validation_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "",
    "email": "correo-invalido",
    "response_url": ""
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

                // El PDF muestra `token` en el body pero la API real responde
                // 400 unrecognized field. El token va sólo en la URL.
                'confirmar' => [
                    'label' => 'Confirmar inscripción',
                    'method' => 'PUT',
                    'path' => '/inscriptions/{{steps.crear.token}}',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
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

                'eliminar' => [
                    'label' => 'Eliminar inscripción',
                    'method' => 'DELETE',
                    'path' => '/inscriptions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}"
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "tbk-user-inexistente"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}"
}',
                        ],
                    ],
                ],

                // El anexo marca `amount` como requerido y `details` como
                // condicional (sólo multicódigo) para 1.0/1.2/1.3.
                'authorize' => [
                    'label' => 'Autorizar pago',
                    'method' => 'POST',
                    'path' => '/transactions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "buy_order": "ordenCompraOneClick",
    "amount": 1000
}',
                        ],
                        'multicodigo' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "buy_order": "ordenCompraOneClick",
    "details": [
        {
            "commerce_code": "597055555542",
            "buy_order": "ordenCompraTienda1",
            "amount": 1000,
            "installments_number": 2
        },
        {
            "commerce_code": "597055555543",
            "buy_order": "ordenCompraTienda2",
            "amount": 2000,
            "installments_number": 2
        }
    ]
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "tbk-user-inexistente",
    "buy_order": "ordenCompraOneClick",
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
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "buy_order": "ordenCompraOneClick",
    "amount": 1000
}',
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
                                'Tbk-Api-Key-Id' => '597055555541',
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

                'refund' => [
                    'label' => 'Reembolso transacción',
                    'method' => 'POST',
                    'path' => '/transactions/{{steps.authorize.buy_order}}/refunds',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 1000,
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "detail_buy_order": "{{steps.authorize.details.0.buy_order}}"
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 999999999,
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "detail_buy_order": "{{steps.authorize.details.0.buy_order}}"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "amount": 1000,
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "detail_buy_order": "{{steps.authorize.details.0.buy_order}}"
}',
                        ],
                    ],
                ],
            ],
        ],
        'v1.3' => [
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
    "username": "juanperez",
    "email": "juan.perez@gmail.com",
    "response_url": "http://www.comercio.cl/return_inscription"
}',
                        ],
                        'validation_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "",
    "email": "correo-invalido",
    "response_url": ""
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

                // El PDF muestra `token` en el body pero la API real responde
                // 400 unrecognized field. El token va sólo en la URL.
                'confirmar' => [
                    'label' => 'Confirmar inscripción',
                    'method' => 'PUT',
                    'path' => '/inscriptions/{{steps.crear.token}}',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
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
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}"
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "tbk-user-inexistente"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}"
}',
                        ],
                    ],
                ],

                // El anexo marca `amount` como requerido y `details` como
                // condicional (sólo multicódigo) para 1.0/1.2/1.3.
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
    "buy_order": "ordenCompraOneClick",
    "amount": 1000
}',
                        ],
                        'multicodigo' => [
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
            "commerce_code": "597055555548",
            "buy_order": "ordenCompraTienda1",
            "amount": 1000,
            "installments_number": 2
        },
        {
            "commerce_code": "597055555549",
            "buy_order": "ordenCompraTienda2",
            "amount": 2000,
            "installments_number": 2
        }
    ]
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "tbk-user-inexistente",
    "buy_order": "ordenCompraOneClick",
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
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "buy_order": "ordenCompraOneClick",
    "amount": 1000
}',
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

                'refund' => [
                    'label' => 'Reembolso transacción',
                    'method' => 'POST',
                    'path' => '/transactions/{{steps.authorize.buy_order}}/refunds',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 1000,
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "detail_buy_order": "{{steps.authorize.details.0.buy_order}}"
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 999999999,
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "detail_buy_order": "{{steps.authorize.details.0.buy_order}}"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "amount": 1000,
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "detail_buy_order": "{{steps.authorize.details.0.buy_order}}"
}',
                        ],
                    ],
                ],

                'captura' => [
                    'label' => 'Captura diferida',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.authorize.buy_order}}/capture',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "authorization_code": "{{steps.authorize.details.0.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
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
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "authorization_code": "{{steps.authorize.details.0.authorization_code}}",
    "capture_amount": 1000
}',
                        ],
                    ],
                ],

                // El anexo de Oneclick documenta esta operación bajo /api/webpay y no
                // bajo /api/oneclick, y lo marca como inconsistencia sin resolver.
                // Se deja bajo oneclick; ajustar el path en la UI si responde 404.
                'incremento_monto' => [
                    'label' => 'Incremento de monto pre-autorizado',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.authorize.buy_order}}/amount',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
    "authorization_code": "{{steps.authorize.details.0.authorization_code}}",
    "amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
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
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
    "authorization_code": "{{steps.authorize.details.0.authorization_code}}",
    "amount": 1000
}',
                        ],
                    ],
                ],

                // El anexo de Oneclick documenta esta operación bajo /api/webpay y no
                // bajo /api/oneclick, y lo marca como inconsistencia sin resolver.
                // Se deja bajo oneclick; ajustar el path en la UI si responde 404.
                'incremento_plazo' => [
                    'label' => 'Incremento de plazo',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.authorize.buy_order}}/authorization_date',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
    "authorization_code": "{{steps.authorize.details.0.authorization_code}}"
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
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
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
    "authorization_code": "{{steps.authorize.details.0.authorization_code}}"
}',
                        ],
                    ],
                ],

                // El anexo de Oneclick documenta esta operación bajo /api/webpay y no
                // bajo /api/oneclick, y lo marca como inconsistencia sin resolver.
                // Se deja bajo oneclick; ajustar el path en la UI si responde 404.
                'reversa_monto' => [
                    'label' => 'Reversa de monto pre-autorizado',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.authorize.buy_order}}/reverse/amount',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
    "authorization_code": "{{steps.authorize.details.0.authorization_code}}",
    "amount": 1000
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
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
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "buy_order": "{{steps.authorize.details.0.buy_order}}",
    "authorization_code": "{{steps.authorize.details.0.authorization_code}}",
    "amount": 1000
}',
                        ],
                    ],
                ],

                // El anexo de Oneclick documenta esta operación bajo /api/webpay y no
                // bajo /api/oneclick, y lo marca como inconsistencia sin resolver.
                // Se deja bajo oneclick; ajustar el path en la UI si responde 404.
                'historial' => [
                    'label' => 'Historial de captura diferida',
                    'method' => 'PUT',
                    'path' => '/transactions/{{steps.authorize.buy_order}}/details',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555547',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "buy_order": "{{steps.authorize.details.0.buy_order}}"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "commerce_code": "{{steps.authorize.details.0.commerce_code}}",
    "buy_order": "{{steps.authorize.details.0.buy_order}}"
}',
                        ],
                    ],
                ],
            ],
        ],
        // Oneclick 1.4 es Estándar de Marca y opera sólo en modalidad Mall.
        // Transbank no publica un código de comercio de prueba habilitado para
        // esta versión: con 597055555541 y 597055555547 la API responde
        // 405 "Transacción no permitida" (verificado contra integración). El
        // endpoint existe; falta que Transbank habilite un comercio.
        'v1.4' => [
            'steps' => [
                'crear' => [
                    'label' => 'Crear inscripción',
                    'method' => 'POST',
                    'path' => '/inscriptions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
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
    "username": "juanperez",
    "email": "juan.perez@gmail.com",
    "response_url": "http://www.comercio.cl/return_inscription"
}',
                        ],
                        'validation_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "",
    "email": "correo-invalido",
    "response_url": ""
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

                // El PDF muestra `token` en el body pero la API real responde
                // 400 unrecognized field. El token va sólo en la URL.
                'confirmar' => [
                    'label' => 'Confirmar inscripción',
                    'method' => 'PUT',
                    'path' => '/inscriptions/{{steps.crear.token}}',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
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

                'eliminar' => [
                    'label' => 'Eliminar inscripción',
                    'method' => 'DELETE',
                    'path' => '/inscriptions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}"
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "tbk-user-inexistente"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}"
}',
                        ],
                    ],
                ],

                // 1.4 no acepta `amount` plano: siempre va details[].
                // Con request_3ds_authentication "SI" sólo se admite UNA tienda hija.
                'authorize' => [
                    'label' => 'Autorizar pago',
                    'method' => 'POST',
                    'path' => '/transactions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "buy_order": "ordenCompraOneClick",
    "pos_entry_mode": "010",
    "request_3ds_authentication": "NO",
    "details": [
        {
            "commerce_code": "597055555542",
            "buy_order": "ordenCompraTienda1",
            "amount": 1000,
            "installments_number": 0
        },
        {
            "commerce_code": "597055555543",
            "buy_order": "ordenCompraTienda2",
            "amount": 2000,
            "installments_number": 0
        }
    ]
}',
                        ],
                        'con_3ds' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "buy_order": "ordenCompraOneClick",
    "pos_entry_mode": "810",
    "request_3ds_authentication": "SI",
    "details": [
        {
            "commerce_code": "597055555542",
            "buy_order": "ordenCompraTienda1",
            "amount": 1000,
            "installments_number": 0,
            "pmnt_ind": "C"
        }
    ]
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "username": "juanperez",
    "tbk_user": "tbk-user-inexistente",
    "buy_order": "ordenCompraOneClick",
    "pos_entry_mode": "010",
    "request_3ds_authentication": "NO",
    "details": [
        {
            "commerce_code": "597055555542",
            "buy_order": "ordenCompraTienda1",
            "amount": 1000,
            "installments_number": 0
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
    "username": "juanperez",
    "tbk_user": "{{steps.confirmar.tbk_user}}",
    "buy_order": "ordenCompraOneClick",
    "pos_entry_mode": "010",
    "request_3ds_authentication": "NO",
    "details": [
        {
            "commerce_code": "597055555542",
            "buy_order": "ordenCompraTienda1",
            "amount": 1000,
            "installments_number": 0
        },
        {
            "commerce_code": "597055555543",
            "buy_order": "ordenCompraTienda2",
            "amount": 2000,
            "installments_number": 0
        }
    ]
}',
                        ],
                    ],
                ],

                'desafio_3ds' => [
                    'label' => 'Resolver desafío 3DS',
                    'method' => 'POST',
                    'path' => '',
                    'presets' => [],
                    'browser_redirect' => [
                        'url' => '{{steps.authorize.challenge_data.base_url}}',
                        'params' => [
                            'browserChallengeToken' => '{{steps.authorize.challenge_data.parameters.browserChallengeToken}}',
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
                                'Tbk-Api-Key-Id' => '597055555541',
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

                // En 1.4 commerce_code y detail_buy_order son obligatorios.
                'refund' => [
                    'label' => 'Reversar o anular transacción',
                    'method' => 'POST',
                    'path' => '/transactions/{{steps.authorize.buy_order}}/refunds',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 1000,
    "commerce_code": "597055555542",
    "detail_buy_order": "ordenCompraTienda1"
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => '597055555541',
                                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                            ],
                            'body' => '{
    "amount": 999999999,
    "commerce_code": "597055555542",
    "detail_buy_order": "ordenCompraTienda1"
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'Tbk-Api-Key-Id' => 'credencial-invalida',
                                'Tbk-Api-Key-Secret' => 'secret-invalido',
                            ],
                            'body' => '{
    "amount": 1000,
    "commerce_code": "597055555542",
    "detail_buy_order": "ordenCompraTienda1"
}',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

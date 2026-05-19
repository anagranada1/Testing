<?php

return [
    'forms' => [
        'base_url' => env('FORM_SERVICES_BASE_URL', 'https://webpay3gint.transbank.cl'),
        'services' => [
            'complete-transaction' => [
                'kind' => 'pipeline',
                'group' => 'Pipelines',
                'label' => 'Transacción completa',
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
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => '{
                                            "buy_order": "ordenCompra12345678",
                                            "session_id": "sesion1234564",
                                            "amount": 10000,
                                            "cvv": 123,
                                            "card_number": "4051885600446623",
                                            "card_expiration_date": "22/10"
                                        }',
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => '{
                                            "buy_order": "ordenCompra12345678",
                                            "session_id": "sesion1234564",
                                            "amount": 10000,
                                            "cvv": 123,
                                            "card_number": "5186059559590568",
                                            "card_expiration_date": "22/10"
                                        }',
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => '{
                                            "buy_order": "ordenCompra12345678",
                                            "session_id": "sesion1234564",
                                            "amount": 10000,
                                            "cvv": 123,
                                            "card_number": "4051885600446623",
                                            "card_expiration_date": "22/10"
                                        }',
                                    ],
                                    'validation_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => '{
                                            "buy_order": "",
                                            "session_id": "sesion1234564",
                                            "amount": -1,
                                            "cvv": 123,
                                            "card_number": "4051885600446623",
                                            "card_expiration_date": "01/01"
                                        }',
                                    ],
                                ],
                            ],

                            'cuotas' => [
                                'label' => 'Consultar cuotas',
                                'method' => 'POST',
                                'path' => '/transactions/{{steps.crear.token}}/installments',
                                'presets' => [
                                    'success' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => '{
                                            "installments_number": 10
                                        }',
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => '{
                                            "installments_number": 99
                                        }',
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => '{
                                            "installments_number": 10
                                        }',
                                    ],
//                                    'skip' => [
//                                        'headers' => [
//                                            'Content-Type' => 'application/json',
//                                            'Tbk-Api-Key-Id' => '597055555530',
//                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
//                                        ],
//                                        'body' => <<<'JSON'
//{
//    "installments_number": 0
//}
//JSON,
//                                    ],
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
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => '{
                                            "id_query_installments": {{steps.cuotas.id_query_installments}},
                                            "deferred_period_index": 1,
                                            "grace_period": false
                                        }',
                                    ],
                                    'sin_cuotas' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => '{}',
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => '{
                                            "id_query_installments": 0,
                                            "deferred_period_index": 0,
                                            "grace_period": false
                                        }',
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => '{
                                            "id_query_installments": {{steps.cuotas.id_query_installments}},
                                            "deferred_period_index": 1,
                                            "grace_period": false
                                        }',
                                    ],
                                ],
                            ],
//
//                            'estado' => [
//                                'label' => 'Estado de la transacción',
//                                'method' => 'GET',
//                                'path' => '/transactions/{{steps.crear.token}}',
//                                'presets' => [
//                                    'success' => [
//                                        'headers' => [
//                                            'Content-Type' => 'application/json',
//                                            'Tbk-Api-Key-Id' => '597055555530',
//                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
//                                        ],
//                                        'body' => '',
//                                    ],
//                                    'auth_error' => [
//                                        'headers' => [
//                                            'Content-Type' => 'application/json',
//                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
//                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
//                                        ],
//                                        'body' => '',
//                                    ],
//                                ],
//                            ],
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
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "cvv": 123,
    "card_number": "4051885600446623",
    "card_expiration_date": "29/12"
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "cvv": 123,
    "card_number": "5186059559590568",
    "card_expiration_date": "29/12"
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "cvv": 123,
    "card_number": "4051885600446623",
    "card_expiration_date": "29/12"
}
JSON,
                                    ],
                                    'validation_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "",
    "session_id": "sesion12345",
    "amount": -1,
    "cvv": 123,
    "card_number": "4051885600446623",
    "card_expiration_date": "01/01"
}
JSON,
                                    ],
                                ],
                            ],

                            'cuotas' => [
                                'label' => 'Consultar cuotas',
                                'method' => 'POST',
                                'path' => '/transactions/{{steps.crear.token}}/installments',
                                'presets' => [
                                    'success' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "installments_number": 3
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "installments_number": 99
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "installments_number": 3
}
JSON,
                                    ],
//                                    'skip' => [
//                                        'headers' => [
//                                            'Content-Type' => 'application/json',
//                                            'Tbk-Api-Key-Id' => '597055555530',
//                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
//                                        ],
//                                        'body' => <<<'JSON'
//{
//    "installments_number": 0
//}
//JSON,
//                                    ],
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
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "id_query_installments": {{steps.cuotas.id_query_installments}},
    "deferred_period_index": 0,
    "grace_period": false
}
JSON,
                                    ],
                                    'sin_cuotas' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => '{}',
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "id_query_installments": 0,
    "deferred_period_index": 0,
    "grace_period": false
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "id_query_installments": {{steps.cuotas.id_query_installments}},
    "deferred_period_index": 0,
    "grace_period": false
}
JSON,
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
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "cvv": 123,
    "card_number": "4051885600446623",
    "card_expiration_date": "29/12"
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "cvv": 123,
    "card_number": "5186059559590568",
    "card_expiration_date": "29/12"
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "amount": 10000,
    "cvv": 123,
    "card_number": "4051885600446623",
    "card_expiration_date": "29/12"
}
JSON,
                                    ],
                                    'validation_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "",
    "session_id": "sesion12345",
    "amount": -1,
    "cvv": 123,
    "card_number": "4051885600446623",
    "card_expiration_date": "01/01"
}
JSON,
                                    ],
                                ],
                            ],

                            'cuotas' => [
                                'label' => 'Consultar cuotas',
                                'method' => 'POST',
                                'path' => '/transactions/{{steps.crear.token}}/installments',
                                'presets' => [
                                    'success' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "installments_number": 3
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "installments_number": 99
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "installments_number": 3
}
JSON,
                                    ],
//                                    'skip' => [
//                                        'headers' => [
//                                            'Content-Type' => 'application/json',
//                                            'Tbk-Api-Key-Id' => '597055555530',
//                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
//                                        ],
//                                        'body' => <<<'JSON'
//{
//    "installments_number": 0
//}
//JSON,
//                                    ],
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
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "id_query_installments": {{steps.cuotas.id_query_installments}},
    "deferred_period_index": 0,
    "grace_period": false
}
JSON,
                                    ],
                                    'sin_cuotas' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => '{}',
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "id_query_installments": 0,
    "deferred_period_index": 0,
    "grace_period": false
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "id_query_installments": {{steps.cuotas.id_query_installments}},
    "deferred_period_index": 0,
    "grace_period": false
}
JSON,
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
                                            'Tbk-Api-Key-Id' => '597055555530',
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
                                'label' => 'Reembolso',
                                'method' => 'POST',
                                'path' => '/transactions/{{steps.crear.token}}/refunds',
                                'presets' => [
                                    'success' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "amount": 1000
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "amount": 999999999
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "amount": 1000
}
JSON,
                                    ],
                                ],
                            ],

                            'captura' => [
                                'label' => 'Captura de la transacción',
                                'method' => 'PUT',
                                'path' => '/transactions/{{steps.crear.token}}/capture',
                                'presets' => [
                                    'success' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "capture_amount": 1000
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "authorization_code": "000000",
    "capture_amount": 1000
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "capture_amount": 1000
}
JSON,
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
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597055555530",
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "amount": 1000
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597055555530",
    "buy_order": "ordenCompra12345",
    "authorization_code": "000000",
    "amount": 1000
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597055555530",
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "amount": 1000
}
JSON,
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
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597055555530",
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}"
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597055555530",
    "buy_order": "ordenCompra12345",
    "authorization_code": "000000"
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597055555530",
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}"
}
JSON,
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
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597055555530",
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "amount": 1000
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597055555530",
    "buy_order": "ordenCompra12345",
    "authorization_code": "000000",
    "amount": 1000
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597055555530",
    "buy_order": "ordenCompra12345",
    "authorization_code": "{{steps.confirmar.authorization_code}}",
    "amount": 1000
}
JSON,
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
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597055555530",
    "buy_order": "ordenCompra12345"
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597055555530",
    "buy_order": "ordenCompra12345"
}
JSON,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'v1.4' => [
                        'steps' => [
                            'crear' => [
                                'label' => 'Crear transacción',
                                'method' => 'POST',
                                'path' => '/transactions',
                                'presets' => [
                                    'success' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "card_number": "4051885600446623",
    "card_expiration_date": "29/12",
    "cvv": 123,
    "details": [
        {
            "amount": 4500,
            "commerce_code": 597038367138,
            "buy_order": "ordenCompra12345",
            "post_entry_mod": "010",
            "pmnt_ind": null,
            "eci": "05",
            "authentication_value": "kAkBBEkGVAAAAAH0FSJWdQAAAAA=",
            "message_version": "2.2.0",
            "trans_status": "Y",
            "ds_trans_id": "de8ad526-8835-47fc-b8b9-2e38ccdf0220",
            "authentication_type": "C",
            "identify_initiated_trx": 0
        }
    ]
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "card_number": "5186059559590568",
    "card_expiration_date": "29/12",
    "cvv": 123,
    "details": [
        {
            "amount": 4500,
            "commerce_code": 597038367138,
            "buy_order": "ordenCompra12345",
            "post_entry_mod": "810",
            "pmnt_ind": null,
            "eci": "02",
            "authentication_value": "kAkBBEkGVAAAAAH0FSJWdQAAAAA=",
            "message_version": "2.2.0",
            "trans_status": "Y",
            "ds_trans_id": "de8ad526-8835-47fc-b8b9-2e38ccdf0220",
            "authentication_type": "C",
            "identify_initiated_trx": 0
        }
    ]
}
JSON,
                                    ],
                                    'sin_3ds' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "card_number": "4051885600446623",
    "card_expiration_date": "29/12",
    "cvv": 123,
    "details": [
        {
            "amount": 4500,
            "commerce_code": 597038367138,
            "buy_order": "ordenCompra12345",
            "post_entry_mod": "010",
            "pmnt_ind": null,
            "eci": null,
            "authentication_value": null,
            "message_version": null,
            "trans_status": null,
            "ds_trans_id": null,
            "authentication_type": null,
            "identify_initiated_trx": 0
        }
    ]
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "session_id": "sesion12345",
    "card_number": "4051885600446623",
    "card_expiration_date": "29/12",
    "cvv": 123,
    "details": [
        {
            "amount": 4500,
            "commerce_code": 597038367138,
            "buy_order": "ordenCompra12345",
            "post_entry_mod": "010",
            "eci": "05",
            "authentication_value": "kAkBBEkGVAAAAAH0FSJWdQAAAAA=",
            "message_version": "2.2.0",
            "trans_status": "Y",
            "ds_trans_id": "de8ad526-8835-47fc-b8b9-2e38ccdf0220",
            "authentication_type": "C",
            "identify_initiated_trx": 0
        }
    ]
}
JSON,
                                    ],
                                    'validation_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "",
    "session_id": "sesion12345",
    "card_number": "4051885600446623",
    "card_expiration_date": "01/01",
    "cvv": 123,
    "details": [
        {
            "amount": -1,
            "commerce_code": 597038367138,
            "buy_order": "",
            "post_entry_mod": "010",
            "identify_initiated_trx": 0
        }
    ]
}
JSON,
                                    ],
                                ],
                            ],

                            'cuotas' => [
                                'label' => 'Consultar cuotas',
                                'method' => 'POST',
                                'path' => '/transactions/{{steps.crear.token}}/installments',
                                'presets' => [
                                    'success' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "commerce_code": 597038367138,
    "installments_number": 3
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "commerce_code": 597038367138,
    "installments_number": 99
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345",
    "commerce_code": 597038367138,
    "installments_number": 3
}
JSON,
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
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "details": [
        {
            "commerce_code": 597038367138,
            "buy_order": "ordenCompra12345",
            "id_query_installments": {{steps.cuotas.id_query_installments}}
        }
    ]
}
JSON,
                                    ],
                                    'sin_cuotas' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "details": [
        {
            "commerce_code": 597038367138,
            "buy_order": "ordenCompra12345"
        }
    ]
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "details": [
        {
            "commerce_code": 597038367138,
            "buy_order": "ordenCompra12345",
            "id_query_installments": 0
        }
    ]
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "details": [
        {
            "commerce_code": 597038367138,
            "buy_order": "ordenCompra12345",
            "id_query_installments": {{steps.cuotas.id_query_installments}}
        }
    ]
}
JSON,
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
                                            'Tbk-Api-Key-Id' => '597055555530',
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
                                'label' => 'Reversar o anular transacción',
                                'method' => 'POST',
                                'path' => '/transactions/{{steps.crear.token}}/refunds',
                                'presets' => [
                                    'success' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597038367138",
    "buy_order": "ordenCompra12345",
    "amount": 1000
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597038367138",
    "buy_order": "ordenCompra12345",
    "amount": 999999999
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "commerce_code": "597038367138",
    "buy_order": "ordenCompra12345",
    "amount": 1000
}
JSON,
                                    ],
                                ],
                            ],

                            'verificar_cuenta' => [
                                'label' => 'Verificación de cuenta',
                                'method' => 'POST',
                                'path' => '/account-verify',
                                'presets' => [
                                    'success' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "card_detail": {
        "card_number": "4051885600446623",
        "card_expiration_date": "29/12",
        "cvv": "123"
    },
    "eci": "05",
    "authentication_value": "kAkBBEkGVAAAAAH0FSJWdQAAAAA=",
    "trans_status": "Y",
    "message_version": "2.2.0",
    "ds_trans_id": "de8ad526-8835-47fc-b8b9-2e38ccdf0220",
    "commerce_code": 597038367138
}
JSON,
                                    ],
                                    'failed' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => '597055555530',
                                            'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                                        ],
                                        'body' => <<<'JSON'
{
    "card_detail": {
        "card_number": "5186059559590568",
        "card_expiration_date": "29/12",
        "cvv": "123"
    },
    "eci": "02",
    "authentication_value": "kAkBBEkGVAAAAAH0FSJWdQAAAAA=",
    "trans_status": "Y",
    "message_version": "2.2.0",
    "ds_trans_id": "de8ad526-8835-47fc-b8b9-2e38ccdf0220",
    "commerce_code": 597038367138
}
JSON,
                                    ],
                                    'auth_error' => [
                                        'headers' => [
                                            'Content-Type' => 'application/json',
                                            'Tbk-Api-Key-Id' => 'credencial-invalida',
                                            'Tbk-Api-Key-Secret' => 'secret-invalido',
                                        ],
                                        'body' => <<<'JSON'
{
    "card_detail": {
        "card_number": "4051885600446623",
        "card_expiration_date": "29/12",
        "cvv": "123"
    },
    "eci": "05",
    "authentication_value": "kAkBBEkGVAAAAAH0FSJWdQAAAAA=",
    "trans_status": "Y",
    "message_version": "2.2.0",
    "ds_trans_id": "de8ad526-8835-47fc-b8b9-2e38ccdf0220",
    "commerce_code": 597038367138
}
JSON,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

];

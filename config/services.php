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
                                        'body' => <<<'JSON'
{
    "deferred_period_index": 0,
    "grace_period": false
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
    "id_query_installments": 0,
    "deferred_period_index": 0,
    "grace_period": false,
    "installments_number": 0,
    "installments_amount": 0
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
                                        'body' => <<<'JSON'
{
    "buy_order": "ordenCompra12345678",
    "session_id": "sesion1234564",
    "amount": 10000,
    "cvv": 123,
    "card_number": "4051885600446623",
    "card_expiration_date": "22/10"
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
    "buy_order": "ordenCompra12345678",
    "session_id": "sesion1234564",
    "amount": 10000,
    "cvv": 123,
    "card_number": "5186059559590568",
    "card_expiration_date": "22/10"
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
    "buy_order": "ordenCompra12345678",
    "session_id": "sesion1234564",
    "amount": 10000,
    "cvv": 123,
    "card_number": "4051885600446623",
    "card_expiration_date": "22/10"
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
    "session_id": "sesion1234564",
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
    "installments_number": 10
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
    "installments_number": 10
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
    "deferred_period_index": 1,
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
                                        'body' => <<<'JSON'
{
    "deferred_period_index": 0,
    "grace_period": false
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
    "deferred_period_index": 1,
    "grace_period": false
}
JSON,
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
                ],
            ],
        ],
    ],

];

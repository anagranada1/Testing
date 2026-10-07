<?php

// Webpay Tokenizado (TEM) no vive en el host de Webpay ni comparte su
// autenticación: usa X-Client-Id / X-Secret-Key / CommerceCode en vez de
// Tbk-Api-Key-*.
//
// ATENCIÓN: el anexo sólo documenta el host de PRODUCCIÓN
// (https://api.transbank.cl/transbank/clientes/api/v2/tem). No hay ambiente
// de integración ni credenciales de prueba publicadas. Las credenciales de
// abajo son marcadores: cualquier ejecución responderá 401 hasta cambiarlas.
$temBaseUrl = rtrim((string) env('TEM_BASE_URL', 'https://api.transbank.cl'), '/');

return [
    'kind' => 'pipeline',
    'group' => 'Pipelines',
    'label' => 'Webpay Tokenizado',
    'multi_step' => true,
    'base_url' => $temBaseUrl,
    'base_path' => '/transbank/clientes/api',
    'versions' => [
        'v2' => [
            'steps' => [
                'crear' => [
                    'label' => 'Crear transacción',
                    'method' => 'POST',
                    'path' => '/tem/transactions',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "meta": {
        "_rqDateTime": "2026-09-06T12:00:00.0Z",
        "_ipAddress": "190.100.10.1"
    },
    "data": {
        "buyOrder": "ordenCompra12345",
        "amount": 10000,
        "cardToken": "5100000000000000",
        "tokenExpirationDate": "2912",
        "posEntryMode": "010",
        "eci": "5",
        "pmntInd": "C",
        "tid": "123456789012345"
    }
}',
                        ],
                        // Transacción de una serie recurrente: posEntryMode 100,
                        // pmntInd R y recurPmnt F (monto fijo).
                        'recurrente' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "meta": {
        "_rqDateTime": "2026-09-06T12:00:00.0Z",
        "_ipAddress": "190.100.10.1"
    },
    "data": {
        "buyOrder": "ordenCompra12346",
        "amount": 10000,
        "cardToken": "5100000000000000",
        "tokenExpirationDate": "2912",
        "posEntryMode": "100",
        "eci": "7",
        "pmntInd": "R",
        "recurPmnt": "F",
        "tid": "123456789012345",
        "tlid": "1234567890123456789012"
    }
}',
                        ],
                        // Sin 3DS: el anexo indica ECI de no autenticado (7 o 2).
                        'sin_3ds' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "meta": {
        "_rqDateTime": "2026-09-06T12:00:00.0Z",
        "_ipAddress": "190.100.10.1"
    },
    "data": {
        "buyOrder": "ordenCompra12347",
        "amount": 10000,
        "cardToken": "5100000000000000",
        "tokenExpirationDate": "2912",
        "posEntryMode": "010",
        "eci": "7"
    }
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'client-id-invalido',
                                'X-Secret-Key' => 'secret-key-invalido',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "meta": {
        "_rqDateTime": "2026-09-06T12:00:00.0Z",
        "_ipAddress": "190.100.10.1"
    },
    "data": {
        "buyOrder": "ordenCompra12345",
        "amount": 10000,
        "cardToken": "5100000000000000",
        "tokenExpirationDate": "2912",
        "posEntryMode": "010",
        "eci": "5"
    }
}',
                        ],
                        'validation_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "meta": {
        "_rqDateTime": "",
        "_ipAddress": ""
    },
    "data": {
        "buyOrder": "",
        "amount": -1,
        "cardToken": "",
        "tokenExpirationDate": "9999",
        "posEntryMode": "",
        "eci": ""
    }
}',
                        ],
                    ],
                ],

                // El número de cuotas va en el path, no en el body. El anexo lo
                // documenta desde 2 en adelante.
                'cuotas' => [
                    'label' => 'Consultar cuotas',
                    'method' => 'GET',
                    'path' => '/tem/transactions/{{steps.crear.data.token}}/installments/3',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'client-id-invalido',
                                'X-Secret-Key' => 'secret-key-invalido',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '',
                        ],
                    ],
                ],

                'confirmar' => [
                    'label' => 'Confirmar transacción',
                    'method' => 'PUT',
                    'path' => '/tem/transactions/{{steps.crear.data.token}}',
                    'presets' => [
                        // El anexo dice explícitamente: "Se debe enviar data
                        // vacío en caso de no tener ninguno de estos campos".
                        'sin_cuotas' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "data": {}
}',
                        ],
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "data": {
        "idQueryInstallments": "{{steps.cuotas.data.idQueryInstallments}}"
    }
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "data": {
        "idQueryInstallments": "0"
    }
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'client-id-invalido',
                                'X-Secret-Key' => 'secret-key-invalido',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "data": {}
}',
                        ],
                    ],
                ],

                'estado' => [
                    'label' => 'Consultar estado',
                    'method' => 'GET',
                    'path' => '/tem/transactions/{{steps.crear.data.token}}',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'client-id-invalido',
                                'X-Secret-Key' => 'secret-key-invalido',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '',
                        ],
                    ],
                ],

                'anular' => [
                    'label' => 'Anular transacción',
                    'method' => 'POST',
                    'path' => '/tem/transactions/{{steps.crear.data.token}}/refunds',
                    'presets' => [
                        'success' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "data": {
        "amount": 1000,
        "tid": "123456789012345"
    }
}',
                        ],
                        // Anulación total: el anexo dice que se envía el monto
                        // completo, nunca se omite el campo.
                        'total' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "data": {
        "amount": 10000,
        "tid": "123456789012345"
    }
}',
                        ],
                        'failed' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'REEMPLAZAR-CLIENT-ID',
                                'X-Secret-Key' => 'REEMPLAZAR-SECRET-KEY',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "data": {
        "amount": 999999999,
        "tid": "123456789012345"
    }
}',
                        ],
                        'auth_error' => [
                            'headers' => [
                                'Content-Type' => 'application/json',
                                'X-Client-Id' => 'client-id-invalido',
                                'X-Secret-Key' => 'secret-key-invalido',
                                'CommerceCode' => 'REEMPLAZAR-COMMERCE-CODE',
                            ],
                            'body' => '{
    "data": {
        "amount": 1000,
        "tid": "123456789012345"
    }
}',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

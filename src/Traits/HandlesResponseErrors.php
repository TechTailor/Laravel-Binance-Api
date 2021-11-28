<?php

namespace TechTailor\BinanceApi\Traits;

trait HandlesResponseErrors
{
    private function handleError($response)
    {
        // Set a default error.
        $error = [
            'code'    => '1000',
            'error'   => 'Invalid',
            'message' => 'Unable to identify the type of error.',
        ];

        // Return server related errors (500 range).
        if ($response->serverError()) {
            // TBA
        }
        // Return client related errors.
        elseif ($response->clientError()) {
            // If client error has a response code.
            if (isset($response['code'])) {
                // Switch between known Binance error codes.
                switch ($response['code']) {
                    case '-1000':
                            $error = [
                                'code'    => '-1000',
                                'error'   => 'UNKNOWN',
                                'message' => 'An unknown error occurred while processing the request. ',
                            ];
                            break;
                    case '-1001':
                            $error = [
                                'code'    => '-1001',
                                'error'   => 'DISCONNECTED',
                                'message' => 'Internal error; unable to process your request. Please try again.',
                            ];
                            break;
                    case '-1002':
                            $error = [
                                'code'    => '-1002',
                                'error'   => 'UNAUTHORIZED',
                                'message' => 'You are not authorized to execute this request.',
                            ];
                            break;
                    case '-1003':
                            $error = [
                                'code'    => '-1003',
                                'error'   => 'TOO_MANY_REQUESTS',
                                'message' => 'Too many requests queued.',
                            ];
                            break;
                    case '-1004':
                            $error = [
                                'code'    => '-1004',
                                'error'   => 'SERVER_BUSY',
                                'message' => 'Server is busy, please wait and try again',
                            ];
                            break;
                    case '-1006':
                            $error = [
                                'code'    => '-1006',
                                'error'   => 'UNEXPECTED_RESP',
                                'message' => 'An unexpected response was received from the message bus. Execution status unknown.',
                            ];
                            break;
                    case '-1007':
                            $error = [
                                'code'    => '-1007',
                                'error'   => 'TIMEOUT',
                                'message' => 'Timeout waiting for response from backend server. Send status unknown; execution status unknown.',
                            ];
                            break;
                    case '-1014':
                            $error = [
                                'code'    => '-1014',
                                'error'   => 'UNKNOWN_ORDER_COMPOSITION',
                                'message' => 'Unsupported order combination.',
                            ];
                            break;
                    case '-1015':
                            $error = [
                                'code'    => '-1015',
                                'error'   => 'TOO_MANY_ORDERS',
                                'message' => 'Too many new orders.',
                            ];
                            break;
                    case '-1016':
                            $error = [
                                'code'    => '-1016',
                                'error'   => 'SERVICE_SHUTTING_DOWN',
                                'message' => 'This service is no longer available.',
                            ];
                            break;
                    case '-1020':
                            $error = [
                                'code'    => '-1020',
                                'error'   => 'UNSUPPORTED_OPERATION',
                                'message' => 'This operation is not supported.',
                            ];
                            break;
                    case '-1021':
                        $error = [
                            'code'    => '-1021',
                            'error'   => 'INVALID_TIMESTAMP',
                            'message' => 'Timestamp for this request is outside of the recvWindow.',
                        ];
                        break;
                    case '-1022':
                        $error = [
                            'code'    => '-1022',
                            'error'   => 'INVALID_SIGNATURE',
                            'message' => 'Signature for this request is not valid.',
                        ];
                        break;
                    case '-1099':
                        $error = [
                            'code'    => '-1099',
                            'error'   => 'NOT_FOUND',
                            'message' => 'Not found, authenticated, or authorized.',
                        ];
                        break;
                    case '-1100':
                        $error = [
                            'code'    => '-1100',
                            'error'   => 'INVALID_CHARACTERS',
                            'message' => 'Illegal characters found in parameter \'orderId\'; legal range is \'^[0-9]{1,20}$\'.',
                        ];
                        break;
                    case '-1101':
                        $error = [
                            'code'    => '-1101',
                            'error'   => 'INVALID_REQUEST',
                            'message' => 'Too many parameters; expected 1 and received 3.',
                        ];
                        break;
                    case '-1102':
                        $error = [
                            'code'    => '-1102',
                            'error'   => 'INVALID_SYMBOL',
                            'message' => 'Mandatory parameter symbol was not sent, was empty/null, or malformed.',
                        ];
                        break;
                    case '-1104':
                        $error = [
                            'code'    => '-1104',
                            'error'   => 'INVALID_REQUEST',
                            'message' => 'Not all sent parameters were read; read 1 parameter(s) but was sent 2.',
                        ];
                        break;
                    case '-1121':
                        $error = [
                            'code'    => '-1121',
                            'error'   => 'INVALID_SYMBOL',
                            'message' => 'Invalid symbol.',
                        ];
                        break;
                    case '-2014':
                        $error = [
                            'code'    => '-2014',
                            'error'   => 'INVALID_API',
                            'message' => 'API-key format invalid.',
                        ];
                        break;
                }
            } else {
                // If client error a response status.
                if ($response->status() === 403) {
                    $error = [
                        'code'    => '403',
                        'error'   => 'Forbidden',
                        'message' => "You don't have permission to access this resouce.",
                    ];
                }
            }

            return $error;
        }
    }
}

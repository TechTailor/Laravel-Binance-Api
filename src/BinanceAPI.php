<?php

namespace TechTailor\BinanceApi;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use TechTailor\BinanceApi\Traits\HandlesResponseErrors;

class BinanceAPI
{
    use HandlesResponseErrors;

    protected $api_key;             // API key
    protected $api_secret;          // API secret
    protected $api_url;             // API base URL
    protected $recvWindow;          // API receiving window
    protected $synced = false;
    protected $response = null;
    protected $no_time_needed = [
        'v1/system/status',
        'v3/time',
        'v3/exchangeInfo',
        'v3/trades',
        'v3/avgPrice',
        'v3/ticker/24hr',
    ];

    /**
     * Constructor for BinanceAPI.
     *
     * @param string $key     API key
     * @param string $secret  API secret
     * @param string $api_url API base URL (see config for example)
     * @param int    $timing  Binance API timing setting (default 10000)
     */
    public function __construct($api_key = null, $api_secret = null, $api_url = null, $timing = 10000)
    {
        $this->api_key = (!empty($api_key)) ? $api_key : config('binance-api.auth.key');
        $this->api_secret = (!empty($api_secret)) ? $api_secret : config('binance-api.auth.secret');
        $this->api_url = (!empty($api_url)) ? $api_url : config('binance-api.urls.api');
        $this->recvWindow = (!empty($timing)) ? $timing : config('binance-api.settings.timing');
    }

    /**
     * API Key and Secret Key setter function.
     * It's required for USER_DATA endpoints.
     * https://binance-docs.github.io/apidocs/spot/en/#endpoint-security-type.
     *
     * @param string $key    API Key
     * @param string $secret API Secret
     */
    public function setAPI($api_key, $api_secret)
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
    }

    //------ PUBLIC API CALLS --------
    //---- Security Type : NONE ------
    /*
    * getSystemStatus
    * getTime
    * getExchangeInfo
    * getOrderBook
    * getAvgPrice
    * getTicker
    */

    /**
     * Get Binance System Status.
     * Uses Sapi Endpoint.
     *
     * @return mixed
     */
    public function getSystemStatus()
    {
        $this->api_url = config('binance-api.urls.sapi');

        return $this->publicRequest('v1/system/status');
    }

    /**
     * Get Binance Server Time.
     *
     * @return mixed
     */
    public function getTime()
    {
        return $this->publicRequest('v3/time');
    }

    /**
     * Get Binance Exchange Info.
     *
     * @param string $symbol Exchange Pair Symbol
     *
     * @return mixed
     */
    public function getExchangeInfo($symbol = null)
    {
        $data = [
            'symbol' => $symbol ? strtoupper($symbol) : null,
        ];

        return $this->publicRequest('v3/exchangeInfo', $data);
    }

    /**
     * Get Binance Order Book for a given symbol.
     *
     * @param string $symbol Exchange Pair Symbol
     *
     * @return mixed
     */
    public function getOrderBook($symbol)
    {
        $data = [
            'symbol' => $symbol ? strtoupper($symbol) : null,
        ];

        return $this->publicRequest('v3/trades', $data);
    }

    /**
     * Get Average Price for a given symbol.
     *
     * @param string $symbol Exchange Pair Symbol
     *
     * @return mixed
     */
    public function getAvgPrice($symbol = null)
    {
        $data = [
            'symbol' => $symbol ? strtoupper($symbol) : null,
        ];

        return $this->publicRequest('v3/avgPrice', $data);
    }

    /**
     * Get 24hr Ticker Price Change Statistics.
     * If the symbol is not sent, tickers for all symbols will
     * be returned in an array.
     *
     * @param string $symbol Exchange Pair Symbol
     *
     * @return mixed
     */
    public function getTicker($symbol = null)
    {
        $data = [
            'symbol' => $symbol ? strtoupper($symbol) : null,
        ];

        return $this->publicRequest('v3/ticker/24hr', $data);
    }

    //------ PRIVATE API CALLS ----------
    //--- Security Type : USER_DATA -----
    /*
    * getAccountInfo
    * getAllOrders
    * getOpenOrders
    * getTrades
    * getOrderStatus
    * getUserCoinsInfo
    * getDepositHistory
    * getWithdrawHistory
    */

    /**
     * Get current account information.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function getAccountInfo()
    {
        $response = $this->privateRequest('v3/account');

        return $response;
    }

    /**
     * Get all current account orders; active, canceled, or filled.
     *
     * @param string $symbol Exchange Pair Symbol
     *
     * @return mixed
     */
    public function getAllOrders($symbol = null)
    {
        $data = [
            'symbol' => $symbol ? strtoupper($symbol) : null,
        ];

        return $this->privateRequest('v3/allOrders', $data);
    }

    /**
     * Get all current account open orders on a symbol.
     * Careful when accessing this with no symbol.
     *
     * @param string $symbol Exchange Pair Symbol
     *
     * @return mixed
     */
    public function getOpenOrders($symbol = null)
    {
        $data = [
            'symbol' => $symbol ? strtoupper($symbol) : null,
        ];

        return $this->privateRequest('v3/openOrders', $data);
    }

    /**
     * Get the trade history for a particular symbol.
     *
     * @param string $symbol Exchange Pair Symbol
     *
     * @return mixed
     */
    public function getTrades($symbol = null)
    {
        $data = [
            'symbol' => $symbol ? strtoupper($symbol) : null,
        ];

        return $this->privateRequest('v3/myTrades', $data);
    }

    /**
     * Get an order's status.
     *
     * @param string $symbol  Exchange Pair Symbol
     * @param string $orderId Exchange Order Id
     *
     * @return mixed
     */
    public function getOrderStatus($symbol = null, $orderId = null)
    {
        $data = [
            'symbol'  => $symbol ? strtoupper($symbol) : null,
            'orderId' => $orderId,
        ];

        return $this->privateRequest('v3/order', $data);
    }

    /**
     * Get information of coins (available for deposit and withdraw) for the user.
     * Uses Sapi Endpoint.
     *
     * @return mixed
     */
    public function getUserCoinsInfo()
    {
        $this->api_url = config('binance-api.urls.sapi');

        return $this->privateRequest('v1/capital/config/getall');
    }

    /**
     * Get deposit history of the user account.
     * Uses Sapi Endpoint.
     *
     * @return mixed
     */
    public function getDepositHistory()
    {
        $this->api_url = config('binance-api.urls.sapi');

        return $this->privateRequest('v1/capital/deposit/hisrec');
    }

    /**
     * Get withdraw history of the user account.
     * Uses Sapi Endpoint.
     *
     * @return mixed
     */
    public function getWithdrawHistory()
    {
        $this->api_url = config('binance-api.urls.sapi');

        return $this->privateRequest('v1/capital/withdraw/history');
    }

    /**
     * Make public requests (Security Type: NONE).
     *
     * @param string $url    URL Endpoint
     * @param array  $params Required and optional parameters
     * @param string $method GET, POST, PUT, DELETE
     *
     * @throws \Exception
     *
     * @return mixed
     */
    private function publicRequest($url, $params = [], $method = 'GET')
    {
        // Build the POST data string
        if (!in_array($url, $this->no_time_needed)) {
            $params['timestamp'] = $this->milliseconds();
            $params['recvWindow'] = $this->recvWindow;
        }

        $url = $this->api_url.$url;

        // Adding parameters to the url.
        $url = $url.'?'.http_build_query($params);

        return $this->sendApiRequest($url, $method);
    }

    /**
     * Make public requests (Security Type: USER_DATA).
     *
     * @param string $url    URL Endpoint
     * @param array  $params Required and optional parameters.
     */
    private function privateRequest($url, $params = [], $method = 'GET')
    {
        // Build the POST data string
        if (!in_array($url, $this->no_time_needed)) {
            $params['recvWindow'] = $this->recvWindow;
            $params['timestamp'] = $this->milliseconds();
        }

        // Build the query to pass through.
        $query = http_build_query($params, '', '&');

        // Set API key and sign the message
        $signature = hash_hmac('sha256', $query, $this->api_secret);

        $url = $this->api_url.$url.'?'.$query.'&signature='.$signature;

        return $this->sendApiRequest($url, $method);
    }

    /**
     * Send request to Wazirx API for Public or Private Requests.
     *
     * @param string $url    URL Endpoint with Query & Signature
     * @param string $method GET, POST, PUT, DELETE
     *
     * @throws \Exception
     *
     * @return mixed
     */
    private function sendApiRequest($url, $method)
    {
        try {
            if ($method == 'POST') {
                $response = Http::withHeaders([
                    'X-MBX-APIKEY' => $this->api_key,
                ])->post($url);
            } elseif ($method == 'GET') {
                $response = Http::withHeaders([
                    'X-MBX-APIKEY' => $this->api_key,
                ])->get($url);
            }
        } catch (ConnectionException $e) {
            return $error = [
                'code'    => $e->getCode(),
                'error'   => 'Host Not Found',
                'message' => 'Could not resolve host: '.$this->api_url,
            ];
        } catch (Exception $e) {
            return $error = [
                'code'    => $e->getCode(),
                'error'   => 'cUrl Error',
                'message' => $e->getMessage(),
            ];
        }

        // If response if Ok. Return collection.
        if ($response->ok()) {
            return $response->collect();
        } else {
            return $this->handleError($response);
        }
    }

    /**
     * Get the milliseconds from the system clock.
     *
     * @return int
     */
    private function milliseconds()
    {
        list($msec, $sec) = explode(' ', microtime());

        return $sec.substr($msec, 2, 3);
    }
}

<?php

namespace App;

class BitcoinTrade {

    protected $apiKey = null;
    protected $urlBase = "https://api.bitcointrade.com.br/v2";

    public function __construct($apiKey = '') {
        $this->apiKey = $apiKey;
    }

    // API Documentation: https://apidocs.bitcointrade.com.br/?version=latest#e3302798-a406-4150-8061-e774b2e5eed5
    public function ticker($pair = 'BRLBTC') {
        $apiURL = "/public/{$pair}/ticker";
        return $this->initCurl($apiURL);
    }

    // API Documentation: https://apidocs.bitcointrade.com.br/?version=latest#5418f5db-6469-46d4-90aa-80a3d291d400
    public function orders($pair = 'BRLBTC') {
        $apiURL = "/public/{$pair}/orders";
        return $this->initCurl($apiURL);
    }

    // API Documentation: https://apidocs.bitcointrade.com.br/?version=latest#07292106-974d-4dd5-a30c-a8ac01dc9ac7
    public function trades(
    $pair = 'BRLBTC', $hours = 1, $page_size = 100, $current_page = 1
    ) {
        $timeZone = new \DateTimeZone('Brazil/East');
        $start_time = new \DateTime('now');
        $start_time->format(\DateTime::ATOM);
        $start_time->setTimezone($timeZone);
        $start_time->modify('-' . $hours . ' hour');
        $start_time = date_format($start_time, \DateTime::ATOM);
        $end_time = new \DateTime('now');
        $end_time->format(\DateTime::ATOM);
        $end_time->setTimezone($timeZone);
        $end_time = date_format($end_time, \DateTime::ATOM);
        $apiURL = "/public/{$pair}/trades?start_time={$start_time}&end_time={$end_time}&page_size={$page_size}&current_page={$current_page}";

        return $this->initCurl($apiURL);
    }

    // API Documentation: https://apidocs.bitcointrade.com.br/?version=latest#b0397f80-9f5b-404d-9e2d-f5caa8b91f0c
    public function balance() {
        $apiURL = '/wallets/balance';
        $apiKeyRequired = true;
        return $this->initCurl($apiURL, $apiKeyRequired);
    }

    // API Documentation: https://apidocs.bitcointrade.com.br/?version=latest#2fce81d0-ba0e-40aa-a782-c2d1760269f8
    public function summary($pair = 'BRLBTC') {
        $apiURL = "/market/summary?pair={$pair}";
        $apiKeyRequired = true;
        return $this->initCurl($apiURL, $apiKeyRequired);
    }

    // API Documentation: https://apidocs.bitcointrade.com.br/?version=latest#1f353481-1d4d-44f5-83df-54fb775a4246
    public function estimatedPrice($pair = "BRLBTC", $amount = 0, $type = "buy") {
        $apiURL = "/market/estimated_price?amount={$amount}&pair={$pair}&type={$type}";
        $apiKeyRequired = true;
        return $this->initCurl($apiURL, $apiKeyRequired);
    }

    // API Documentation: https://apidocs.bitcointrade.com.br/?version=latest#5458868b-c4e5-4998-9038-be99892f1a23
    public function bookOrders($pair = 'BRLBTC') {
        $apiURL = "/market?pair={$pair}";
        $apiKeyRequired = true;
        return $this->initCurl($apiURL, $apiKeyRequired);
    }

    // API Documentation: https://apidocs.bitcointrade.com.br/?version=latest#04b407c7-57be-452c-84e9-55ea40a000d6
    public function userOrders(
    $pair = "BRLBTC", $status = "executed_completely", $hours = 24, $type = "", $page_size = 100, $current_page = 1
    ) {

        $timeZone = new \DateTimeZone('Brazil/East');
        $start_time = new \DateTime('now');
        $start_time->format(\DateTime::ATOM);
        $start_time->setTimezone($timeZone);
        $start_time->modify('-' . $hours . ' hour');
        $start_time = date_format($start_time, \DateTime::ATOM);
        $end_time = new \DateTime('now');
        $end_time->format(\DateTime::ATOM);
        $end_time->setTimezone($timeZone);
        $end_time = date_format($end_time, \DateTime::ATOM);
        $apiURL = "/market/user_orders/list?status={$status}&start_date={$start_time}&end_date={$end_time}&pair={$pair}&type={$type}&page_size={$page_size}&current_page={$current_page}";
        $apiKeyRequired = true;
        return $this->initCurl($apiURL, $apiKeyRequired);
    }

    // API Documentation: https://apidocs.bitcointrade.com.br/?version=latest#8342c7da-e1c9-4a1f-8ea1-da129d4f6ff0
    public function createOrder(
    $pair = "BRLBTC", $type = "buy", $subtype = "limited", $amount = 0, $unit_price = 0
    ) {
        $request_price = $amount * $unit_price;

        $fields = compact('pair', 'type', 'subtype', 'unit_price', 'amount', 'request_price');

        $apiURL = '/market/create_order';
        $apiKeyRequired = true;
        return $this->initCurl($apiURL, $apiKeyRequired, $fields, 'POST');
    }

    // API Documentation: https://apidocs.bitcointrade.com.br/?version=latest#f225da35-c5e7-495e-9c9e-c02fd65bbdc1
    public function cancelOrder($id = '') {
        $fields = compact('id');
        $apiURL = "/market/user_orders/";
        $apiKeyRequired = true;
        return $this->initCurl($apiURL, $apiKeyRequired, $fields, 'DELETE');
    }

    // https://apidocs.bitcointrade.com.br/?version=latest#40001027-72c3-4bab-a156-6efe2cc685bf
    public function withdrawFeeEstimate($coin = 'bitcoin') {

        $apiURL = "/{$coin}/withdraw/fee";
        $apiKeyRequired = true;

        return $this->initCurl($apiURL, $apiKeyRequired);
    }

    // https://apidocs.bitcointrade.com.br/?version=latest#570698dd-1d7d-4a6c-8997-5b15933b17ee
    public function depositList(
    $status = "confirmed", $hours = 24, $page_size = 100, $current_page = 1, $coin = 'bitcoin'
    ) {

        $timeZone = new \DateTimeZone('Brazil/East');
        $start_time = new \DateTime('now');
        $start_time->format(\DateTime::ATOM);
        $start_time->setTimezone($timeZone);
        $start_time->modify('-' . $hours . ' hour');
        $start_time = date_format($start_time, \DateTime::ATOM);
        $end_time = new \DateTime('now');
        $end_time->format(\DateTime::ATOM);
        $end_time->setTimezone($timeZone);
        $end_time = date_format($end_time, \DateTime::ATOM);
        $apiURL = "/{$coin}/deposits?status={$status}&start_date={$start_time}&end_date={$end_time}&page_size={$page_size}&current_page={$current_page}";
        $apiKeyRequired = true;
        return $this->initCurl($apiURL, $apiKeyRequired);
    }

    // https://apidocs.bitcointrade.com.br/?version=latest#6d07995b-2120-46f8-8fa0-4cceb5f4ac0d
    public function withdrawList(
    $status = "pending", $hours = 24, $page_size = 100, $current_page = 1, $coin = 'bitcoin'
    ) {

        $timeZone = new \DateTimeZone('Brazil/East');
        $start_time = new \DateTime('now');
        $start_time->format(\DateTime::ATOM);
        $start_time->setTimezone($timeZone);
        $start_time->modify('-' . $hours . ' hour');
        $start_time = date_format($start_time, \DateTime::ATOM);
        $end_time = new \DateTime('now');
        $end_time->format(\DateTime::ATOM);
        $end_time->setTimezone($timeZone);
        $end_time = date_format($end_time, \DateTime::ATOM);
        $apiURL = "/{$coin}/withdraw?status={$status}&start_date={$start_time}&end_date={$end_time}&page_size={$page_size}&current_page={$current_page}";
        $apiKeyRequired = true;
        return $this->initCurl($apiURL, $apiKeyRequired);
    }

    // https://apidocs.bitcointrade.com.br/?version=latest#e866a939-5032-4e55-a037-08292fbf02ed
    public function createWithdraw(
    $destination = "", $amount = 0, $fee_type = "regular", $fee = "", $coin = 'bitcoin'
    ) {

        $fields = compact('destination', 'amount', 'fee_type', 'fee');

        $apiURL = "/{$coin}/withdraw";
        $apiKeyRequired = true;

        return $this->initCurl($apiURL, $apiKeyRequired, $fields, 'POST');
    }

    // https://apidocs.bitcointrade.com.br/?version=latest#a4245b60-bb86-4320-875d-7f14f8faabef
    public function syncTransaction(
    $hash = "", $coin = 'bitcoin'
    ) {

        $fields = compact('hash');

        $apiURL = "/{$coin}/sync_transaction";
        $apiKeyRequired = true;
        return $this->initCurl($apiURL, $apiKeyRequired, $fields, 'POST');
    }

    private function initCurl($url = '', $apiKeyRequired = false, $fields = [], $method = 'GET') {

        $curl = curl_init();

        $header = [
            'Content-Type: application/json'
        ];

        if ($apiKeyRequired) {
            array_unshift($header, "Authorization: ApiToken {$this->apiKey}");
        }

        $jsonFields = (empty($fields) ? [] : json_encode($fields));

        $options = [
            CURLOPT_URL => $this->urlBase . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $jsonFields,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $header
        ];

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        $err = curl_error($curl);
        curl_close($curl);

        ob_start();
        print_r(date('Y-m-d H:i:s') . ' = ' . $response);
        $sTXT = ob_get_contents();
        $hArq = fopen('logs/logs.txt', 'a+');
        fwrite($hArq, $sTXT . "\n\n");
        fclose($hArq);
        ob_end_clean();

        return $err ? "cURL Error #: {$err}" : json_decode($response);
    }

}

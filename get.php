<?php

require("load_func.php");

/*
https://www.php.net/manual/en/function.dns-get-record.php
$dnsr = dns_get_record('php.net', DNS_A + DNS_NS);
$dnsr = dns_get_record('php.net', DNS_ALL - DNS_PTR);
*/

header('Content-Type: application/json');

# Webs service with JSON to show/write list of nameservice many domains in: domain_list.json
try {

    load_func([
        'https://php.letjson.com/let_json.php',
        'https://php.defjson.com/def_json.php',
        'https://php.eachfunc.com/each_func.php',

    ], function () {

        $domain = $_GET["domain"];

        if (empty($domain)) {
            throw new Exception("domain value is empty");
        }

        $records = dns_get_record($domain, DNS_NS);

        $nameserver_list = each_func($records, function ($record) {

            if (empty($record)) return null;

            if ($record["type"] !== 'NS') return null;

            return $record["target"];
        });

        if (empty($nameserver_list)) return null;

        echo def_json('', [
            'domain' => $domain,
            'nameserver' => $nameserver_list
        ]);

    });

} catch (Exception $e) {
    echo def_json('', ['error' => $e->getMessage()]);
}
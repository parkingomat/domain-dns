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

        $meta = let_json("meta.json");
        $domain_list = let_json($meta->in->file);

        if (empty($domain_list)) {
            throw new Exception("JSON file is empty or has error inside");
        }

        $domain_nameserver_list = each_func($domain_list->domain_list, function ($domain) {

            $records = dns_get_record($domain, DNS_NS);

            $nameserver_list[$domain] = each_func($records, function ($record) {

                if (empty($record)) return null;

                if ($record["type"] !== 'NS') return null;

                return $record["target"];
            });

            if (empty($nameserver_list)) return null;

            return $nameserver_list;
        });

        echo def_json($meta->out->file, ['domain_list' => $domain_nameserver_list]);

    });

} catch (Exception $e) {
    echo def_json('', ['error' => $e->getMessage()]);
}
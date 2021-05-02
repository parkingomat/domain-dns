<?php

require("load_func.php");

header('Content-Type: application/json');

# Webs service with JSON
try {

    load_func([
        'https://php.letjson.com/let_json.php',
        'https://php.defjson.com/def_json.php',
        'https://php.eachfunc.com/each_func.php',

    ], function () {

        $meta = let_json("meta.json");

        $domain_list = let_json($meta->in->file);

        $domain_nameserver_list = each_func($domain_list->domain_list, function ($domain) {

//                https://www.php.net/manual/en/function.dns-get-record.php
//                DNS_ALL - DNS_PTR);
            //        $dnsr = dns_get_record('php.net', DNS_A + DNS_NS);
            $records = dns_get_record($domain);
            $nameserver_list[$domain] = each_func($records, function ($record) {
                if (empty($record)) return null;

                if ($record["type"] !== 'NS') return null;

                return $record["target"];
            });

            if (empty($nameserver_list)) return null;

            return $nameserver_list;
        });

        echo def_json($meta->in->file, ['domain_list.json' => $domain_nameserver_list]);

    });

} catch (Exception $e) {
    echo def_json('', ['error' => $e->getMessage()]);
}
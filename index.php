<?php

require("load_func.php");

try {

    load_func(['https://php.letjson.com/let_json.php', 'https://php.defjson.com/def_json.php'], function () {

        $domain_list = let_json("domain_list.json");

        $domains_per_host = [];
        foreach ($domain_list as $obj) {
            $list = $obj;
            foreach ($list as $item) {
                $result = dns_get_record($item);
                $domains_per_host['nameserver_list'][$item] = $result;
            }
        }

        header('Content-Type: application/json');
        def_json('nameserver_list.json', $domains_per_host, function ($data) {
            echo $data;
            exit();
        });

    });

} catch (exception $e) {
    // Set HTTP response status code to: 500 - Internal Server Error
    http_response_code(500);
}
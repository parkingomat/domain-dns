<?php

require("load_func.php");

try {

    load_func([
        'https://php.letjson.com/let_json.php',
        'https://php.defjson.com/def_json.php',
        'https://php.each_func.com/each_func.php',

    ], function () {

        $meta = let_json("meta.json");

        $domain_list = let_json($meta->in->file);

        $nameserver_list = [];
        foreach ($domain_list as $obj) {
            $list = $obj;
            foreach ($list as $item) {

//                https://www.php.net/manual/en/function.dns-get-record.php
//                DNS_ALL - DNS_PTR);
                //        $dnsr = dns_get_record('php.net', DNS_A + DNS_NS);

                $result = dns_get_record($item);

                $nameserver_list = each_func((array)$result, function ($item) {

                    $data_filtered2 = each_func($item, function ($record) {
//                var_dump($record);
                        if (empty($record)) return null;

                        if ($record->type !== 'NS') return null;

                        return $record->target;
//                return [
//                    [$record->host => $record->target]
//                    'domain' => $record->host,
//                    'class' => $obj->class,
//                    'target' => $obj->target
//                    'nameserver' => $record->target
//                ];
                    });
//            var_dump("data_filtered2",$data_filtered2);

                    if (empty($data_filtered2)) return null;

                    return $data_filtered2;
                });

//                $nameserver_list['domain_nameserver_list'][$item] = $result;
            }
        }

        header('Content-Type: application/json');
        echo def_json($meta->out->file, $nameserver_list);

    });

} catch (exception $e) {
    // Set HTTP response status code to: 500 - Internal Server Error
    http_response_code(500);
}
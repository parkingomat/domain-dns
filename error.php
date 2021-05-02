<?php
function handleError($errno, $errstr, $errfile, $errline)
{
    def_json('',
        [
            'error' => $errno,
            'errstr' => $errstr,
//             'errfile'=> $errfile,
//             'errline'=> $errline
        ],
        function ($json) {
            // show header with json data
//            echo $json;
        }
    );
}

set_error_handler("handleError");
<?php

use Fuel\Core\Format;
use Fuel\Core\Response;

class Printer
{

    public static function printResult($data, $format = 'json', $status = 200)
    {
        if ($format == 'xml') {
            self::toXMLResponse($data, $status);
        } else if ($format == 'array') {
            self::toArrayResponse($data, $status);
        } else {
            self::toJSONResponse($data, $status);
        }
    }

    private static function toJSONResponse($data, $status = 200)
    {
        $responsebody = Format::forge($data)->to_json();

        $content_type = 'application/json';

        $response = new Response();
        $response->set_status($status);
        $response->body($responsebody);
        $response->set_header('Content-Type', $content_type);
        $response->set_header('Pragma', 'no-cache');
        $response->send_headers();
        $response->send();
        die();
    }


    private static function toXMLResponse($data, $status = 200)
    {
        $responsebody = Format::forge($data)->to_xml();

        $content_type = 'application/xml';

        $response = new Response();
        $response->set_status($status);
        $response->body($responsebody);
        $response->set_header('Content-Type', $content_type);
        $response->set_header('Pragma', 'no-cache');
        $response->send_headers();
        $response->send();
        die();
    }


    public static function toArrayResponse($data)
    {
        echo "<pre>";
        echo print_r($data, true);
        echo "</pre>";

        die();
    }

    public static function error($type, $message, $code, $status = 200)
    {
        $response['error_type'] = $type;
        $response['error_message'] = $message;
        $response['error_code'] = $code;

        self::printResult($response, 'json', $status);
    }

    public static function output($body, $content_type, $status = 200)
    {

        $response = new Response();
        $response->set_status($status);
        $response->body($body);
        $response->set_header('Content-Type', $content_type);
        $response->set_header('Pragma', 'no-cache');
        $response->send_headers();
        $response->send();
        die();
    }
}

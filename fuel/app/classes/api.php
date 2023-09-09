<?php
class Api
{


    public static function getBody($json_decode = true)
    {
        $rawData = file_get_contents('php://input');
        return $json_decode ? json_decode($rawData) : $rawData;
    }


    public static function push($url, $params)
    {
        $post_string = $params;
        $parts = parse_url($url);
        $isHttps = $parts['scheme'] == 'https';
        $port = $isHttps ? 443 : 80;
        $hostname = $isHttps ? 'ssl://' . $parts['host'] : $parts['host'];

        $fp = fsockopen(
            $hostname,
            isset($parts['port']) ? $parts['port'] : $port,
            $errno,
            $errstr,
            30
        );



        $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Content-Type: application/json\r\n";
        $out .= "Content-Length: " . strlen($post_string) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        $out .= $post_string;

        fwrite($fp, $out);
        fclose($fp);
    }


    public static function request(
        $url,
        $args,
        $headers,
        $method,
        $jsonEncodeArgs = false,
        $successCode = 200
    ) {
        $ch = curl_init();

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);

            if (!empty($args)) {
                if ($jsonEncodeArgs === true) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
                }
            }
        } else /* $method === 'GET' */ {
            if (!empty($args)) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($args));
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Make sure we can access the response when we execute the call
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        if ($data === false) {
            return array('error' => 'API call failed with cURL error: ' . curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        // die($data);

        $response = json_decode($data, true);

        $jsonErrorCode = json_last_error();
        if ($jsonErrorCode !== JSON_ERROR_NONE) {
            return array(
                'error' => 'API response not well-formed (json error code: '
                    . $jsonErrorCode . ')'
            );
        }

        if ($httpCode !== $successCode) {
            $errorMessage = '';

            if (!empty($response['error_type'])) {
                $errorMessage = $response['error_message'] . ' [' . $response['error_code'] . ']';
            }

            return array('error' => $errorMessage);
        }

        return $response;
    }
}

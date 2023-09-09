<?php

use Fuel\Core\Config;
use Fuel\Core\Inflector;
use Fuel\Core\Str;
use Fuel\Core\Upload;
use Fuel\Core\Uri;

class Utils
{

    public static function IsNullOrEmptyString($question)
    {
        return (!isset($question) || trim($question) === '');
    }

    public static function monthName($index)
    {
        $month = 'Janvier';
        switch ($index) {
            case 1:
                $month = 'Janvier';
                break;
            case 2:
                $month = 'Février';
                break;
            case 3:
                $month = 'Mars';
                break;
            case 4:
                $month = 'Avril';
                break;
            case 5:
                $month = 'Mai';
                break;
            case 6:
                $month = 'Juin';
                break;
            case 7:
                $month = 'Juillet';
                break;
            case 8:
                $month = 'Aout';
                break;
            case 9:
                $month = 'Septembre';
                break;
            case 10:
                $month = 'Octobre';
                break;
            case 11:
                $month = 'Novembre';
                break;
            case 12:
                $month = 'Decembre';
                break;
            default:
                $month = 'Janvier';
                break;
        }

        return $month;
    }


    public static function asyncRequest($url, $params, $type = 'POST')
    {
        foreach ($params as $key => &$val) {
            if (is_array($val)) $val = implode(',', $val);
            $post_params[] = $key . '=' . urlencode($val);
        }
        $post_string = implode('&', $post_params);

        $parts = parse_url($url);

        $fp = fsockopen(
            $parts['host'],
            isset($parts['port']) ? $parts['port'] : 80,
            $errno,
            $errstr,
            30
        );

        // Data goes in the path for a GET request
        if ('GET' == $type) $parts['path'] .= '?' . $post_string;
        $out = "$type " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: " . strlen($post_string) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        // Data goes in the request body for a POST request
        if ('POST' == $type && isset($post_string)) $out .= $post_string;

        fwrite($fp, $out);
        fclose($fp);
    }

    public static function floatValue($val)
    {
        $val = str_replace(",", ".", $val);
        $val = preg_replace('/\.(?=.*\.)/', '', $val);
        return floatval($val);
    }

    public static function isEmptyString($str)
    {
        return !(isset($str) && (strlen(trim($str)) > 0));
    }

    public static function formatPhoneNumber($phoneNumber)
    {
        $out = '';

        if (strlen($phoneNumber) == 10) {
            if (substr($phoneNumber, 0, 1) === '0') {
                $phoneNumber = substr_replace($phoneNumber, '243', 0, 1);
                $out = $phoneNumber;
            }
        } else if (strlen($phoneNumber) == 12) {
            $out = $phoneNumber;
        }

        return $phoneNumber;
    }

    public static function isPhone($phoneNumber)
    {
        // Allow +, - and . in phone number
        $filtered_phone_number = filter_var($phoneNumber, FILTER_SANITIZE_NUMBER_INT);
        // Remove "-" from number
        $phone_to_check = str_replace("-", "", $filtered_phone_number);
        // Check the lenght of number
        // This can be customized if you want phone number from a specific country
        if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
            return false;
        } else {
            return true;
        }
    }

    public static function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function stringHasPrefix($string, $prefix)
    {
        return strpos($string, $prefix) === 0;
    }

    public static function objArraySearch($array, $index, $value)
    {
        foreach ($array as $arrayInf) {
            if ($arrayInf[$index] == $value) {
                return $arrayInf;
            }
        }
        return null;
    }

    public static function existsInArray($array, $index, $value)
    {
        foreach ($array as $arrayInf) {
            if ($arrayInf[$index] == $value) {
                return true;
            }
        }
        return false;
    }

    public static function indexOf($object, array $elementData)
    {
        $elementCount = count($elementData);
        for ($i = 0; $i < $elementCount; $i++) {
            if ($object == $elementData[$i]) {
                return $i;
            }
        }
        return -1;
    }

    public static function uploadFile($folder = 'temps/files')
    {
        $fileToReturn = null;

        $config = array(
            'path'                  => $folder,
            'randomize'             => false,
            'mime_whitelist'        => array(
                'application/CDFV2',
                'image/vnd.dwg',
                "application/pdf",
                "application/vnd.ms-excel",
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                "application/vnd.openxmlformats-officedocument.wordprocessing",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "application/msword",
                "application/zip",
                'image/pjpeg',
                'image/jpeg',
                'image/png',
                'application/octet-stream'
            ),
        );

        Upload::process($config);
        if (Upload::is_valid()) {
            Upload::save(0);

            $file = Upload::get_files(0);

            $fileName = $file['saved_as'];
            $directory = $file['saved_to'];
            $extension = $file['extension'];
            if (self::IsNullOrEmptyString($extension)) {
                $extension = self::fileExtension($file['mimetype']);
            }

            $newFileName = Str::random('uuid');
            $oldFile = $directory . $fileName;
            $newFile = $directory . $newFileName . '.' . $extension;

            $mimetype = $file['mimetype'];
            if ($mimetype == 'image/vnd.dwg') $mimetype = 'dwg';
            if ($mimetype == 'application/CDFV2') $mimetype = 'rvt';

            Fuel\Core\File::rename($oldFile, $newFile);
            $fileToReturn = new stdClass;
            $fileToReturn->original_name = $file['name'];
            $fileToReturn->file_name = $newFileName . '.' . $extension;
            $fileToReturn->folder = $folder;
            $fileToReturn->kind = $mimetype;
            $fileToReturn->extension = $extension;
            $fileToReturn->size = $file['size'];
            $fileToReturn->path = $folder . '/' . $newFileName . '.' . $extension;
        } else {
            $errors = Upload::get_errors(0);
            Printer::printResult([
                'errors' => $errors,
            ]);
        }

        return $fileToReturn;
    }

    private static function fileExtension($key)
    {
        $extensions['application/pdf'] = '.pdf';
        $extensions['image/jpeg'] = '.jpg';
        $extensions['image/pjpeg'] = '.jpg';
        $extensions['image/png'] = '.png';

        return $extensions[$key];
    }

    public static function str_ends($string, $end)
    {
        return (substr($string, -strlen($end), strlen($end)) === $end);
    }

    public static function str_begins($string, $start)
    {
        return (substr($string, 0, strlen($start)) === $start);
    }

    public static function removeFromEnd($string, $stringToRemove)
    {
        $stringToRemoveLen = strlen($stringToRemove);
        $stringLen = strlen($string);

        $pos = $stringLen - $stringToRemoveLen;

        $out = substr($string, 0, $pos);

        return $out;
    }

    public static function removeFromStart($string, $stringToRemove)
    {
        if (0 === strpos($string, $stringToRemove))
            $string = substr($string, strlen($stringToRemove)) . '';
        return $string;
    }

    public static function right($string, $chars)
    {
        $vright = substr($string, strlen($string) - $chars, $chars);
        return $vright;
    }

    public static function handleCors()
    {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 7200');
        } else {
            //No HTTP_ORIGIN set, so we allow any. You can disallow if needed here
            header("Access-Control-Allow-Origin: *");
        }


        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }
    }

    public static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public static function roundDown($decimal, $precision)
    {
        return floor(round($decimal * pow(10, $precision), $precision)) / pow(10, $precision);
    }


    public static function getRequestBody($json_decode = true)
    {
        $rawData = file_get_contents('php://input');
        return $json_decode ? json_decode($rawData) : $rawData;
    }

    public static function getTimestamp($string, $timezone = 'UTC')
    {
        $dateTime = new DateTime($string, new DateTimeZone($timezone));
        $timestamp = $dateTime->getTimestamp();

        return $timestamp;
    }

    public static function isoDateTime($string, $timezone = 'UTC')
    {
        $dateTime = new DateTime($string, new DateTimeZone($timezone));
        $isoDate = $dateTime->format('c');

        return $isoDate;
    }

    public static function dateTime($string, $timezone = 'UTC')
    {
        $dateTime = new DateTime($string, new DateTimeZone($timezone));

        return $dateTime;
    }

    public static function mongoDate($string, $timezone = 'UTC')
    {
        $dateTime = new DateTime($string, new DateTimeZone($timezone));
        $isoDate = $dateTime->format('c');

        $date = new \MongoDB\BSON\UTCDateTime(strtotime($isoDate) * 1000);
        return $date;
    }

    public static function xmlToArray($string)
    {
        $xml   = simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA);
        $array = json_decode(json_encode($xml), TRUE);
        return $array;
    }

    public static function objectToArray($object)
    {
        $array = json_decode(json_encode($object), TRUE);
        return $array;
    }

    public static function arrayToObject($array)
    {
        $object = json_decode(json_encode($array));
        return $object;
    }

    public static function array_unshift_assoc(&$arr, $key, $val)
    {
        $arr = array_reverse($arr, true);
        $arr[$key] = $val;
        $arr = array_reverse($arr, true);
        return $arr;
    }

    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public static function convertTimezone($time = "", $toTz = '', $fromTz = '')
    {
        // timezone by php friendly values
        $date = new DateTime($time, new DateTimeZone($fromTz));
        $date->setTimezone(new DateTimeZone($toTz));
        $time = $date->format('Y-m-d H:i:s');
        return $time;
    }

    public static function moneyFomat($num)
    {
        return number_format($num, 2, '.', ',');
    }

    public static function currencySymbol($currency)
    {
        $currencies['CDF'] = 'F.';
        $currencies['USD'] = '$';

        return isset($currencies[$currency]) ? $currencies[$currency] : 'CDF';
    }

    public static function toBool($var)
    {
        if (!is_string($var)) return (bool) $var;
        switch (strtolower($var)) {
            case '1':
            case 'true':
            case 'on':
            case 'yes':
            case 'y':
                return true;
            default:
                return false;
        }
    }

    public static function count_pdf_pages($path)
    {
        $pdf = file_get_contents($path);
        $number = preg_match_all("/\/Page\W/", $pdf, $dummy);
        return $number;
    }

    public static function queryFields($filter)
    {
        $fields = [];
        foreach (explode(',', $filter) as $item) {
            $array = [];
            parse_str($item, $array);

            // $row[$array[0]] = Utils::toBool($array[1]) ? Utils::toBool($array[1]) : $array;
            $fields = array_merge($array, $fields);
        }

        return $fields;
    }

    public static function zipData($source, $destination)
    {
        if (extension_loaded('zip') && file_exists($source) && count(glob($source . DS . '*')) !== 0) {

            $zip = new \ZipArchive();
            if ($zip->open($destination, \ZIPARCHIVE::CREATE)) {
                $source = realpath($source);
                if (is_dir($source)) {
                    $iterator = new \RecursiveDirectoryIterator($source);
                    // skip dot files while iterating
                    $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
                    $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);

                    foreach ($files as $file) {
                        $file = realpath($file);
                        if (is_dir($file)) {
                            $zip->addEmptyDir(str_replace($source . '', '', $file . ''));
                        } else if (is_file($file)) {
                            $zip->addFromString(str_replace($source . '', '', $file), file_get_contents($file));
                        }
                    }
                } else if (is_file($source)) {
                    $zip->addFromString(basename($source), file_get_contents($source));
                }
            }
            return $zip->close();
        }
        return false;
    }

    public static function removeFolder($dir)
    {
        if (is_dir($dir)) {

            $objects = scandir($dir, 1);
            foreach ($objects as $object) {

                if ($object != "." && $object != "..") {

                    if (filetype($dir . "/" . $object) == "dir") self::removeFolder($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }
}

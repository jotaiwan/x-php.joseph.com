<?php
/**
 * Created by IntelliJ IDEA.
 * User: jochen
 * Date: 1/7/18
 * Time: 9:17 AM
 */

/**
 * String format for X-Unique-ID (Using Hex):
 * eg. 7F000001:AE58_7F000016:01BB_59B63240_27D75A:19A1
 * 7F000001: client ip address
 * AE58: client ip port
 * 7F000016: frontend ip address
 * 01BB: frontend ip port
 * 59B63240: System timestamp
 * 27D75A: Request counter
 * 19A1: VM ID
 * */
/*
public static String createUniqueId() {
    String ipHex = ipToHex(IP_ADDRESS);
    String clientPortHex = digitNumberToHex();
    String frontendPortHex = digitNumberToHex();

    String timeHex = systemTimeToHex();
    String requestCounter = requestCounterToHex();
    String vmId = digitNumberToHex();

    return (ipHex + ":" + clientPortHex + UNDER_SCORE_DELIMITER + ipHex + COLON_DELIMITER + frontendPortHex +
        UNDER_SCORE_DELIMITER + timeHex + UNDER_SCORE_DELIMITER + requestCounter + COLON_DELIMITER + vmId);
}
*/

function strToHex($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
    }
    return strToUpper($hex);
}


if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
echo "ip: " . $ip . "<p/>";
echo "port: " . $_SERVER['REMOTE_PORT'] . "<p/>";

$clientIpAddress = gethostbyname($ip);
$clientIP = strtoupper(ip2long($clientIpAddress));
echo "Client ip: " . $clientIP . "<p/>";


$clientIpPortHex = strtoupper(bcmod($_SERVER['REMOTE_PORT'], 256));
echo "Client ip port hex: " . strToHex($clientIpPortHex) . "<p/>";


$frontIpAddress = gethostbyname($_SERVER['SERVER_NAME']);
$frontIp = strtoupper(ip2long($frontIpAddress));
echo "Front ip: " . $frontIp . "<p/>";


$frontIpPortHex = strtoupper(bcmod($_SERVER['SERVER_PORT'], 256));
echo "Front ip port hex: " . strToHex($frontIpPortHex) . "<p/>";


$systemTimeStamp = strtoupper(dechex(time()));
echo "time stamp: " . $systemTimeStamp . "<p/>";

$requestCounter = sprintf("%06X", (rand() * (9999999 - 1000000)) + 1000000);
echo "request counter: " . $requestCounter . "<p/>";

echo ">... " . getRequestCounter() . "<p/>";


echo "*********: " . strtoupper(str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)) . "<p/>";

function getRequestCounter() {
    $requestCounter = sprintf("%06X", (rand() * (9 - 1)) + 1);
    echo "origial: $requestCounter <p/>";
    return substr($requestCounter, 0, 8) . "_" . substr($requestCounter, 8, 6);
}


?>
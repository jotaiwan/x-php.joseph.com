<?php
/**
 * Created by IntelliJ IDEA.
 * User: jochen
 * Date: 6/7/18
 * Time: 11:05 AM
 */

//define("VALUT_LDAP_LOGIN_PATH", "https://vault.common.int.viator.com/v1/auth/ldap_db/login/%s");

define("VALUT_LDAP_LOGIN_PATH_PROD", "https://vault-syd1.prod.viatorsystems.com/v1/auth/ldap_db/login/%s");

define("AUTH", "auth");
define("CLIENT_TOKEN", "client_token");
define("POLICIES", "policies");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $env = "int";
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = getVaultClientToken($env, $username, $password) ;
    $result = json_decode($result, true);

    $auth = getResultByKey($result, AUTH);
    $client_token = getResultByKey($auth, CLIENT_TOKEN);
    $policies = getResultByKey($auth, POLICIES);

    echo "All results: " . json_encode($result) . "<p/>";

    $dbPolicies = getDatabasePolicies($policies);
    $policyNo = 0;
    if (sizeof($dbPolicies) > 1) {
        // TODO:
        // print enter the number... and assign $policyNo to be selected
    } else if (sizeof($dbPolicies) < 1) {
        // TODO:
        // print you dont have database access
    } else {
        $policyNo = 0;
    }


}

function getResultByKey($result, $key) {
    if (array_key_exists($key, $result)) {
        return $result[$key];
    }

//    messageResponse("$key is not found!");
    exit();
}


function getDatabasePolicies($policies) {
    $dbPolicies = array();
    foreach ($policies as $policy) {
        if (substr( $policies, 0, 3 ) === "db-") {
            array_push($dbPolicies, $policies);
        }
    }
    return $dbPolicies;

}

function getDatabaseCredentials($environment, $username, $password) {
    $vaultLdapLoginFullPath = str_replace("%s", $username, VALUT_LDAP_LOGIN_PATH_PROD);
    $data = json_encode(array("password"=>$password));

    echo "vault login full path: $vaultLdapLoginFullPath!! <p/>";
    return getCurlResult($vaultLdapLoginFullPath, $data);
}


/**
 * make curl request and return
 *
 * @param $url: url locator
 * @param $request: http request, eg. POST or GET
 * @param $data: the type can be either json or string. If the type is string type, this will be used for
 *               CURLOPT_HTTPHEADER with header name called "X-Vault-Token". If the type is json, this will be used for
 *               CURLOPT_POSTFIELDS
 * @return
 */
function getCurlResult($url, $data) {
    if (isset($url)) {
        // Initializing curl
        $token = curl_init( $url );

//        $data = json_encode($data);
        $options = array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array("Content-Type: application/json", "Content-Length: " . strlen($data), "Accept: text/plain")
        );

        // Setting curl options
        curl_setopt_array( $token, $options );
        $tokenResult = curl_exec($token);
        curl_close($token);

        return $tokenResult;
    }
    return null;
}


?>


<html>
<head>
</head>

<body>
    <form name="userCredentials" method="POST" action="testVaultLogin.php">
        <input type="text" name="username" />
        <input type="password" name="password" />
        <button type="submit">Submit</button>
    </form>
</body>
</html>




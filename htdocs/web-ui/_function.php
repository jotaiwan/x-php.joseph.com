<?php
/**
 * Created by IntelliJ IDEA.
 * User: jochen
 * Date: 7/07/18
 * Time: 5:00 PM
 */

define("VALUT_ADDRESS", "https://vault-syd1.prod.viatorsystems.com/v1");
define("VAULT_LOGIN_PATH", "/auth/ldap_db/login/%s");
define("VAULT_CREDENTIAL_PATH", "/database/creds/%s-%s");
define("AUTH_END_POINT", "%s.replica.syd1.db.viatorsystems.com");

define("AUTH", "auth");
define("CLIENT_TOKEN", "client_token");
define("POLICIES", "policies");
define("USERNAME", "username");
define("PASSWORD", "password");
define("DATA_TO_LOWER", "data");
define("END_POINT", "end_point");
define("LEASE_DURATION", "lease_duration");
define("VALID_DURATION", "valid_duration");
define("SUCCESS", "success");
define("ERROR", "danger");

function getAuthDetail($username, $password, $databaseNo) {

    $result = getVaultResponseViaLoginRequest($username, $password);
    $dbPolicies = getDatabasePolicies($result[POLICIES]);

    if (sizeof($dbPolicies) > 1) {
        // TODO: select available policies
//        messageResponse(SUCCESS, getAvailablePolicies($dbPolicies));
    } else if (sizeof($dbPolicies) < 1) {
        $message = "You don't have any database access policies. ";
        $message .= "You probably aren't in an LDAP group that has database access";
        messageResponse(ERROR, $message);
    } else {
        $policyNo = 0;
    }

    $selectedPolicy = getSelectedPolicy($dbPolicies, $policyNo);

    $authDetail = getAuthDetailUsingClientToken($result[CLIENT_TOKEN], $selectedPolicy, $databaseNo);
    if (isset($authDetail) && !empty($authDetail)) {
        // convert to json
        $authDetail = json_decode($authDetail, true);

        $authData = getResultByKey(DATA_TO_LOWER, $authDetail);
        $authEndPoint = sprintf(AUTH_END_POINT, getDatabaseMap()[$databaseNo]);
        $authUsername = getResultByKey(USERNAME, $authData);
        $authPassword = getResultByKey(PASSWORD, $authData);
        $authLeaseDuration = (getResultByKey(LEASE_DURATION, $authDetail) / 60 / 60 / 24) . " days";
        $authOutput = array(END_POINT=>$authEndPoint, USERNAME=>$authUsername, PASSWORD=>$authPassword,
            VALID_DURATION=>$authLeaseDuration);

        messageResponse(SUCCESS, getAuthDetailHtmlResults($authOutput));
    }
    exit();
}


function getAuthDetailUsingClientToken($clientToken, $selectedPolicy, $databaseNo) {
    $selectedDatabase = getDatabaseMap()[$databaseNo];

    // path to generate creds is database/creds/{policy}-{db}
    $uri = VALUT_ADDRESS . sprintf(VAULT_CREDENTIAL_PATH, $selectedPolicy, $selectedDatabase);
    $header = array("Content-Type: application/json", "X-Vault-Token: " . $clientToken, "Accept: text/plain");

    return getCurlResultViaGet($uri, $header);
}

/**
 * getSelectedPolicy: get policy but remove "db-"
 * @param $result
 * @param $policyNo
 * @return array()
*/
function getSelectedPolicy($policies, $policyNo) {
    return substr($policies[$policyNo], 3);
}

/**
 * getVaultResponseViaLoginRequest: get vault response (include token) via login request
 * @param $username
 * @param $password
 * @return array
 */
function getVaultResponseViaLoginRequest($username, $password) {
    $result = getVaultClientToken($username, $password) ;
    if (isset($result) && !empty($result)) {
        $result = json_decode($result, true);

        $auth = getResultByKey(AUTH, $result);
        $client_token = getResultByKey(CLIENT_TOKEN, $auth);
        $policies = getResultByKey(POLICIES, $auth);
    } else {
        messageResponse(ERROR, "Failed to get client token");
    }

    return array(AUTH=>$auth, CLIENT_TOKEN=>$client_token, POLICIES=>$policies);
}

/**
 * getResultByKey: Checking if key exist in result, if not, exit
 * @param $result
 * @param $key
 * @return
*/
function getResultByKey($key, $result) {
    if (isset($result) && !empty($result)) {
        if (array_key_exists($key, $result)) {
            return $result[$key];
        }
    } else {
        messageResponse(ERROR, "Unable to find $key due to empty result.");
    }
    messageResponse(ERROR, "$key is not found.");
}

/**
 * getDatabaseCredentials: get database credential detail
 * @param $username
 * @param $password
 * @return
*/
function getVaultClientToken($username, $password) {
    $vaultFullPath = VALUT_ADDRESS . VAULT_LOGIN_PATH;
    $vaultLdapLoginFullPath = str_replace("%s", $username, $vaultFullPath);
    $data = json_encode(array("password"=>$password));
    $header = array("Content-Type: application/json", "Content-Length: " . strlen($data), "Accept: text/plain");

    return getCurlResultViaPost($vaultLdapLoginFullPath, $data, $header);
}

/**
 * getDatabasePolicies: only add string that contains "db-" to array and return
 * @param $policies
 * @return array
*/
function getDatabasePolicies($policies) {
    $dbPolicies = array();
    foreach ($policies as $policy) {
        if (substr( $policy, 0, 3 ) === "db-") {
            array_push($dbPolicies, $policy);
        }
    }
    return $dbPolicies;
}

/**
 * getCurlResultViaGet: make curl get request and return
 *
 * @param $url: url locator
 * @param $header
 *
 * @return
 */
function getCurlResultViaGet($url, $header) {
    $options = array(
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header
    );
    return getCurlResult($url, $options);
}

/**
 * getCurlResultViaPost: make curl post request and return
 *
 * @param $url: url locator
 * @param $data: the type can be either json or string. If the type is string type, this will be used for
 *               CURLOPT_HTTPHEADER with header name called "X-Vault-Token". If the type is json, this will be used for
 *               CURLOPT_POSTFIELDS
 * @param $header
 *
 * @return
 */
function getCurlResultViaPost($url, $data, $header) {
    $options = array(
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header
    );

    return getCurlResult($url, $options);
}

/**
 * make curl request and return
 *
 * @param $url: url locator
 * @param option: either post or get
 *
 * @return
 */
function getCurlResult($url, $options) {
    if (isset($url)) {
        // Initializing curl
        $token = curl_init( $url );

        // Setting curl options
        curl_setopt_array( $token, $options );
        $tokenResult = curl_exec($token);
        curl_close($token);

        return $tokenResult;
    }
    return null;
}

/**
 * getHtmlDatabaseList: get database list with html format string
 * @return string
 */
function getAuthDetailHtmlResults($results) {
    $html = "<ul class=\"list-group\">";
    $html .= "<li class=\"list-group-item list-group-item-success\">Results: </li>";
    foreach ($results as $key => $value) {
        $html .= "<li class=\"list-group-item\">";
        $html .= ("<h5 class=\"list-group-item-heading\">" . $key . ":</h5>");
        $html .= ("<p class=\"list-group-item-text\">" . $value . "</p>");
        $html .= "</li>";
//        $html = $html . "<li class=\"list-group-item\">" . $key . ": " . $value . "</li>";
    }
    $html .= "</ul>";
    return $html;
}



/**
 * getHtmlDatabaseList: get database list with html format string
 * @return string
 */
function getDatabaseHtmlList() {
    $html = "<ul class=\"list-group\">";
    foreach (getDatabaseMap() as $key => $value) {
        $html = $html . "<li class=\"list-group-item\">" . $key . ": " . $value . "</li>";
    }
    $html .= "<li class=\"list-group-item\">Please enter the number: ";
    $html .= "<input type=\"text\" class=\"form-control\" id=\"databaseNo\" name=\"databaseNo\" maxlength=\"1\" size=\"1\"/>";
    $html .= "</ul>";
    return $html;
}

/**
 * getDatabaseMap: policy number matched to database name
 * @return array
*/
function getDatabaseMap() {
    return array("1"=>"saint-usercontent", "2"=>"itinerary", "3"=>"vcs", "4"=>"nsp");
}

/**
 * messageResponse: display message response result and exit
 * @param $status: response status
 * @param $responseText: response text
 */
function messageResponse($status, $responseText) {
    echo getMessageResponse(array("STATUS"=>$status, "RESULT"=>$responseText));
    exit();
}

/**
 * getMessageResponse: convert message response to json format and return
 * @param $responseText: response text
 * @return string
 */
function getMessageResponse($responseText) {
    return json_encode(array("responseText" => $responseText, JSON_FORCE_OBJECT));
}

?>
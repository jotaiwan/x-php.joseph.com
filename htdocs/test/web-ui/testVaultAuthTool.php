<?php
/**
 * Created by IntelliJ IDEA.
 * User: jochen
 * Date: 6/7/18
 * Time: 11:05 AM
 */

//define("VALUT_LDAP_LOGIN_PATH", "https://vault.common.int.viator.com/v1/auth/ldap_db/login/%s");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/web-ui/_function.php");


define("VALUT_LDAP_LOGIN_PATH_PROD", "https://vault-syd1.prod.viatorsystems.com/v1/auth/ldap_db/login/%s");
define("MESSAGE_BEGIN_END", "***** %s ***** <p/>");

// Need to find test account for this
$mockUsername = "";
$mockPassword = "";
$mockDatabaseNo = "2";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mockUsername = $_POST["mockUsername"];
    $mockPassword = $_POST["mockPassword"];
    $mockDatabaseNo = $_POST["mockDatabaseNo"];


    testGetDatabaseList();

    testGetVaultClientToken($mockUsername, $mockPassword);

}

function testGetDatabaseList() {
    $databaseList = getDatabaseHtmlList();
    echo sprintf(MESSAGE_BEGIN_END, "testGetDatabaseList :: START");
    echo "Database List: <p/>";
    echo $databaseList . "<p/>";
    echo sprintf(MESSAGE_BEGIN_END, "testGetDatabaseList :: END");
}


function testGetVaultClientToken($username, $password) {
    echo sprintf(MESSAGE_BEGIN_END, "testGetVaultClientToken :: START");
    echo "Vault client Token: <p/>";
    echo json_encode(getVaultResponseViaLoginRequest($username, $password)) . "<p/>";
    echo sprintf(MESSAGE_BEGIN_END, "testGetVaultClientToken :: END");
}

?>


<html>
<head>
</head>

<body>
<form name="userCredentials" method="POST" action="testVaultAuthTool.php">
    <input type="text" name="mockUsername" />
    <input type="password" name="mockPassword" />
    <input type="text" name="mockDatabaseNo" /> (Please enter 1, 2, 3 or 4 only)
    <button type="submit">Submit</button>
</form>
</body>
</html>

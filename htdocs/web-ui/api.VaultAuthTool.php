<?php
/**
 * Created by IntelliJ IDEA.
 * User: jochen
 * Date: 7/07/18
 * Time: 5:00 PM
 */

//include_once($_SERVER['DOCUMENT_ROOT'] . "/common/ldap/_gdprDataRemovalRole.php");
include_once("_function.php");

if (isset($_POST["username"])) {
    $username = $_POST["username"];
}

if (isset($_POST["password"])) {
    $password = $_POST["password"];
}

if (isset($_POST["databaseNo"])) {
    $databaseNo = $_POST["databaseNo"];
}

if (isset($_GET["database"]) && ($_GET["database"] == "list")) {
    messageResponse(SUCCESS, getDatabaseHtmlList());
    exit();
}

getAuthDetail($username, $password, $databaseNo);

?>
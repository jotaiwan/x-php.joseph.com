<?php

// get all parameters from url
$params = ($_GET);

// search loginZone with start process login authentication.
if (array_key_exists("loginZone", $params)) {
    $params["loginZone"] = empty($params["loginZone"]) ? "default" : $params["loginZone"];
	$loginZone = $params["loginZone"];
}

$loginPageHeader = "Login Test";
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/authentication/_login_authentication.php");

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/assets/lib/bootstrap/3.3.7/css/bootstrap.min.css" />

    <title><?php echo $loginPageHeader ?></title>
</head>
<body>
    <div class="container">
        <?php
            include_once ($_SERVER["DOCUMENT_ROOT"] . "/common/authentication/_loginHeader.php");
        ?>
        
        <div class="alert alert-success text-center">You have logged in successfully.</div>
        <div class="alert alert-warning">
            Request parameters
            <ul>
                <?php
                    foreach ($params as $key => $value) {
                        if (isset($value)) {
	                        echo "<li>" . $key . ": " . $value . "</li>";
                        }
                     }
                ?>
            </ul>
        </div>
        <div class="alert alert-warning">
            Session
            <ul>
	            <?php
					foreach ($_SESSION as $key => $value) {
						if (isset($value)) {
							echo "<li>" . $key . ": " . $value . "</li>";
						}
					}
	            ?>
            </ul>
        </div>
        
    </div>
</body>
</html>


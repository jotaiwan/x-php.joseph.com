<?php
/**
 * Created by IntelliJ IDEA.
 * User: jochen
 * Date: 6/7/18
 * Time: 2:03 PM
 */

?>

<html>
<head>
    <title>TA DB Auth Tool</title>

    <script type="text/javascript" src="/assets/lib/jquery-3.2.1.min.js"></script>

    <link rel="stylesheet" href="/assets/lib/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script type="text/javascript" src="/assets/lib/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="assets/css/dbAuthTool.css" />
</head>

<body>
    <div class="container">
        <img src="assets/img/trip-advisor-experiences-one-line-logo.svg"/>
        <hr>

        <form class="form-inline" name="userCredentials" method="POST" action="testVaultLogin.php">
            <div class="row">
                <div class="form-group">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" />
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="password" name="password" placeholder="Password"/>
                </div>

                <input class="btn btn-info" id="submit" type="submit" value="Submit">
            </div>

        </form>
    </div>
</body>
</html>




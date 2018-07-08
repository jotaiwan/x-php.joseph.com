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
    <title>TA Vault Auth Tool</title>

    <script type="text/javascript" src="/assets/lib/jquery-3.2.1.min.js"></script>

    <link rel="stylesheet" href="/assets/lib/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script type="text/javascript" src="/assets/lib/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="assets/css/vaultAuthTool.css" />

    <script type="text/javascript" src="assets/js/vaultAuthTool.js"></script>

</head>

<body>
    <div class="container">
        <div class="col-xs-12">
            <div class="col-md-8 col-md-offset-2 centered">
                <img src="assets/img/trip-advisor-experiences-one-line-logo.svg"/> <h3 class="text-center">TA Database Auth Tool</h3>
            </div>

        </div>

        <hr>

        <div id="userCredentials" class="col-xs-12">
            <div class="col-md-6 col-md-offset-3">
                <form class="form-inline" id="userCredentialForm" name="userCredentialForm" method="POST" action="api.VaultAuthTool.php" onsubmit="return false;">
                    <div class="row">
                        <ul class="list-group">
                            <li class="list-group-item borderless">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="username" name="username" placeholder="TA Username"/>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="TA Password"/>
                                </div>
                                <input class="btn btn-info" id="databaseList" type="button" value="Search">
                            </li>
                        </ul>
                    </div>

                    <div class="row"><div id="databaseSelection" class="col-xs-12"></div></div>
                </form>

                <div id="authResult"></div>

            </div>
        </div>
    </div>
</body>
</html>




$(function() {

    // load database list and display
    getDatabaseList();

    $("#databaseList").on("click", function() {
        // remove alert message
        prepare();

        if (!validation()) {
            var data = $("#userCredentialForm").serialize();
            var url = $("#userCredentialForm").attr("action");
            getResult(url, data);
        }
    });


    function getResult(url, data) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "api.VaultAuthTool.php",
            data: data,
            success: function(data) {
                debugger;
                var response = data.responseText;
                if (response["STATUS"] == "success") {
                    $("#authResult").html(response["RESULT"]);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if(textStatus === "timeout") {
                    $("#authResult").html("<i class=\"glyphicon glyphicon-remove-circle\"></i>&nbsp;Timeout!");
                    $("#authResult").addClass("alert alert-danger");
                } else {
                    $("#authResult").html("<i class=\"glyphicon glyphicon-remove-circle\"></i>&nbsp;Sorry, unable to create download link. Please re-run it or log a jira if it still failed.");
                    $("#authResult").addClass("alert alert-danger");
                }
            },
            complete: function(data) {
                cleanRealtimeElements();
            }
        });
    }


    function getDatabaseList() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "api.VaultAuthTool.php?database=list",
            success: function(data) {
                var response = data.responseText;
                if (response["STATUS"] == "success") {
                    $("#databaseSelection").html(response["RESULT"]);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if(textStatus === "timeout") {
                    $("#result").html("<i class=\"glyphicon glyphicon-remove-circle\"></i>&nbsp;Timeout!");
                    $("#result").addClass("alert alert-danger");
                } else {
                    $("#result").html("<i class=\"glyphicon glyphicon-remove-circle\"></i>&nbsp;Api error, please try again later.");
                    $("#result").addClass("alert alert-danger");
                }
            }
        });
    }


    function removeAlertMessage() {
        $(".alert").removeClass (function (index, className) {
            return (className.match (/(^|\s)alert-\S+/g) || []).join(' ');
        }).html("");
    };

    function prepare() {
        removeAlertMessage();
        $("#message").removeClass("alert").empty();
        $("#authResult").empty().append(displayLoading());
        changeButtonDisabled("databaseList", true);
    }

    function changeButtonDisabled(buttonName, isDisabled) {
        $("#" + buttonName).prop('disabled', isDisabled);
    }

    function displayLoading() {
        var loading = "<div id='loading' class='col-xs-2 text-right'><span>";
        loading += "<img src='/web-ui/assets/img/loading-dot.gif' id='loading-indicator'/></span></div>";
        return loading;
    }

    function cleanLoading() {
        $("#loading").empty();
    }

    function cleanRealtimeElements() {
        cleanLoading();
        changeButtonDisabled("databaseList", false);
    }

    function validation() {
        var isInvalid = false;
        $("#message").empty();
        if (($.trim($("#username").val()) == "") ||  ($.trim($("#password").val()) == "")) {
            $("#message").html("<i class=\"glyphicon glyphicon-remove-circle\"></i>&nbsp;The username and password cannot be empty!<br/>");
            isInvalid = true;
        }
        if (($.trim($("#databaseNo").val()) == "")) {
            var emptyDatabaseNoText = "<i class=\"glyphicon glyphicon-remove-circle\"></i>&nbsp;The database number can not be empty!<br/>";
            $("#message").append(emptyDatabaseNoText);
            isInvalid = true;
        } else  if ((($("#databaseNo").val() < 1) || ($("#databaseNo").val() > 4))) {
            var invalidDatabaseNoText = "<i class=\"glyphicon glyphicon-remove-circle\"></i>&nbsp;The database number need to be between 1 to 4.";
            $("#message").append(invalidDatabaseNoText);
            isInvalid = true;
        }

        if (isInvalid) {
            $("#message").addClass("alert alert-danger");
            cleanRealtimeElements();
        }

        return isInvalid;
    }

});
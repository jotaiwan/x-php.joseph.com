$(function() {

    // load database list and display
    getDatabaseList();

    $("#searchAuth").on("click", function() {
        // remove alert message
        prepare();
        if (!validation()) {
            $("#authResult").empty().append(displayLoading());
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
                var response = data.responseText;
                var divElement = getDivElement(response["DIV"]);
                divElement.html(response["RESULT"]);
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
                var divElement = getDivElement(response["DIV"]);
                divElement.html(response["RESULT"]);
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
        if (($.trim($("#username").val()) == "") ||  ($.trim($("#password").val()) == "")) {
            addMessage("authResult", "<i class=\"glyphicon glyphicon-remove-circle\"></i>&nbsp;The username and password cannot be empty!<br/>");
            isInvalid = true;
        }

        var databaseNo = $.trim($("#databaseNo").val());
        if ((databaseNo == "")) {
            var emptyDatabaseNoText = "<i class=\"glyphicon glyphicon-remove-circle\"></i>&nbsp;The database number can not be empty!<br/>";
            addMessage("authResult", emptyDatabaseNoText);
            isInvalid = true;
        } else if (!(Math.floor(databaseNo) == databaseNo && $.isNumeric(databaseNo))) {
            var invalidDatabaseNoFormat = "<i class=\"glyphicon glyphicon-remove-circle\"></i>&nbsp;The database number need to be integer.";
            addMessage("authResult", invalidDatabaseNoFormat);
            isInvalid = true;

        } else if (((databaseNo < 1) || (databaseNo > 4))) {
            var invalidDatabaseNoText = "<i class=\"glyphicon glyphicon-remove-circle\"></i>&nbsp;The database number need to be between 1 to 4.";
            addMessage("authResult", invalidDatabaseNoText);
            isInvalid = true;
        }

        if (isInvalid) {
            $("#authResult").addClass("alert alert-danger");
            cleanRealtimeElements();
        }

        return isInvalid;
    }

    function addMessage(div, message) {
        var divElement = getDivElement(div);
        if (divElement.is(":empty")) {
            divElement.html(message);
        } else {
            divElement.append(message);
        }
    }

    function getDivElement(divName) {
        if (divName.indexOf("#") == -1) {
            divName = "#" + divName;
        }
        return $(divName);

    }

});
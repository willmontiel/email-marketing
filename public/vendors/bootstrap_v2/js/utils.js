//
//var Profile = {
//    check: function (id) {
//        if ($.trim($("#" + id)[0].value) == '') {
//            $("#" + id)[0].focus();
//            $("#" + id + "_alert").show();
//
//            return false;
//        };
//
//        return true;
//    },
//    validate: function () {
//        if (SignUp.check("name") == false) {
//            return false;
//        }
//        if (SignUp.check("email") == false) {
//            return false;
//        }
//        $("#profileForm")[0].submit();
//    }
//};

var SignUp = {
    check: function (id) {
        if ($.trim($("#" + id)[0].value) == '') {
            $("#" + id)[0].focus();
            $("#" + id + "_alert").show();

            return false;
        };

        return true;
    },
    validate: function () {
        if (SignUp.check("companyName") == false) {
            return false;
        }
        if (SignUp.check("email") == false) {
            return false;
        }
        if (SignUp.check("firstName") == false) {
            return false;
        }
        if (SignUp.check("lastName") == false) {
            return false;
        }
        if ($("#password")[0].value != $("#repeatPassword")[0].value) {
            $("#repeatPassword")[0].focus();
            $("#repeatPassword_alert").show();

            return false;
        }
        $("#registerAccount")[0].submit();
    }
}

$(document).ready(function () {
    $("#registerAccount .alert").hide();
    $("div.profile .alert").hide();
});

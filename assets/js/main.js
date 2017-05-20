//This file is loaded after jQuery, Bootstrap and Material, just before the end of the body tag. Still, to ensure that the document is ready, code should go inside the document.ready below.

//Initialise Material Design for Boostrap
jQuery.material.init();

//Once the document is finished loading, run what's inside this
jQuery("document").ready(function() {
    //If we need to use another framework that also uses $ as a variable, we would have to free it up. With this, we ensure that we can once again use $ as a stand-in for jQuery within the scope of this selector. For more: https://api.jquery.com/jquery.noconflict/
    var $ = jQuery.noConflict();

    /* ---------- GENERAL ---------- */

    //Vars, intervals, whatever

    /* ---------- EVENTS ---------- */

    //Clicks, submits, etc.

    $(".btn-login").on("click", function(){
        formHash(this.form, this.form.password);
    });

    $(".btn-register").on("click", function(){
        regFormHash(this.form, this.form.username, this.form.email, this.form.password, this.form.confirmpwd);
    });

    $("span[data-msg-id]").on("click", function(){
        deleteHubMessage(this);
    });

    /* ---------- FUNCTIONS ---------- */

    //Whatever functions we need

    function formHash(form, password){
        // Create a new element input. This will contain the hashed password.
        var p = document.createElement("input");

        // Add the new element to the form, setting its value to the hashed password.
        // The password is hashed using sha512.js, see the separate file.
        form.appendChild(p);
        p.name = "p";
        p.type = "hidden";
        p.value = hex_sha512(password.value);

        // Remove the visibly entered password to ensure that it is not sent (as plain text).
        password.value = "";

        // Submit the form.
        form.submit();
    }

    function regFormHash(form, uid, email, password, conf){
        // Check that each field has a value.
        if (uid.value == '' || email.value == '' || password.value == '' || conf.value == '') {
            $(".reg-error").text('You must provide all the requested details. Please try again.');
            return false;
        } else {
            $(".reg-error").empty();
        }

        re = /^\w+$/;
        if(!re.test(form.username.value)) {
            $(".reg-error").text("Username must contain only letters, numbers and underscores. Please try again.");
            form.username.focus();
            return false;
        }

        // Check that the password is sufficiently long (min 6 chars)
        // The check is duplicated below, but this is included to give more
        // specific guidance to the user
        if (password.value.length < 6) {
            $(".reg-error").text('Passwords must be at least 6 characters long.  Please try again.');
            form.password.focus();
            return false;
        }

        // At least one number, one lowercase and one uppercase letter
        // At least six characters

        var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
        if (!re.test(password.value)) {
            $(".reg-error").text('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again.');
            form.password.focus();
            return false;
        }

        // Check password and confirmation are the same
        if (password.value != conf.value) {
            $(".reg-error").text('Your password and confirmation do not match. Please try again.');
            form.password.focus();
            return false;
        }

        // Finally, create a new hidden input element, hash the password and set it as the value here, then remove the password from the visible input element, then submit the form, just as in the formHash function.
        var p = document.createElement("input");

        form.appendChild(p);
        p.name = "p";
        p.type = "hidden";
        p.value = hex_sha512(password.value);

        password.value = "";
        conf.value = "";

        form.submit();
        return true;
    }

    function deleteHubMessage(oElement){
        var iId = $(oElement).attr("data-msg-id");
        $.ajax({
            "url":"server/delete_hub_msg.php",
            "method":"post",
            "data": {"data":iId},
            "cache":false
        }).done(function(){
            location.reload(true);
        });
    }

});


//This file is loaded after jQuery, Bootstrap and Material, just before the end of the body tag. Still, to ensure that the document is ready, code should go inside the document.ready below.

//Initialise Material Design for Boostrap
jQuery.material.init();

//Once the document is finished loading, run what's inside this
jQuery("document").ready(function() {
    //If we need to use another framework that also uses $ as a variable, we would have to free it up. With this, we ensure that we can once again use $ as a stand-in for jQuery within the scope of this selector. For more: https://api.jquery.com/jquery.noconflict/
    var $ = jQuery.noConflict();

    /* ---------- GENERAL ---------- */

    //Vars, intervals, whatever

    //remove all leading and trailing white spaces from all input text fields on the site when they are no longer focused
    $('input[type=text]').blur(function(){
        $(this).val($.trim($(this).val()));
    });



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

    $(".lbl-create-game button").click(function(){
        createGame();
    });

    $('.lbl-join-game form').submit(function(e){
        e.preventDefault();
        joinGame(this);
    });

    $('form[name="questionsubmit"]').submit(function(e){
        e.preventDefault();
        submitQuestion(this);
    });

    $(".close-room").click(function(){
        closeGameRoom();
    });

    $(".admin-approve").click(function(){
        handleQuestionBackend(1, $(this).parent().attr("data-question-id"));
    });

    $(".admin-reject").click(function(){
        handleQuestionBackend(2, $(this).parent().attr("data-question-id"));
    });

    $(".new-game").click(function(){
        newGame();
    });

    $(".quitgame").click(function(){
        $("#wdw-game-modal").fadeOut();
    });



    /* ---------- FUNCTIONS ---------- */

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
        var token = $('input[name="token"]').val();
        var jData = {};
        jData.id = iId;
        jData.token = token;
        $.ajax({
            "url":"server/delete_hub_msg.php",
            "method":"post",
            "data": {"data":jData},
            "cache":false
        }).done(function(){
            location.reload(true);
        });
    }

    function createGame(sId){
        sId = sId || "";
        var token = $('input[name="token"]').val();
        var jData = {};
        jData.id = sId;
        jData.token = token;
        $.ajax({
            "url":"server/create_game.php",
            "method":"post",
            "data": {"data":jData},
            "cache":false
        }).done(function(data){
            window.location.replace(data);
        });
    }

    // No CSRF token, as it is currently possible (intentionally) to join games without being logged in
    function joinGame(oElement){
        var sId = $(oElement).find('input[type="text"]').val();
        if(sId != ""){
            $.ajax({
                "url":"server/join_game.php",
                "method":"post",
                "data": {"data":sId},
                "cache":false
            }).done(function(data){
                jData = JSON.parse(data);
                if(jData.status == true){
                    window.location.replace(jData.url);
                }
            });
        }
    }

    // No CSRF token, as it is currently possible (intentionally) to join games without being logged in
    function populateUserList(){
        var sId = $(".game-info").attr("data-game-id");
        if ($(".game-user-list")[0]){
            $.ajax({
                "url":"server/get_game_users.php",
                "method":"post",
                "data": {"data":sId},
                "cache":false
            }).done(function(data){
                $(".game-user-list").empty().append(data);
            });
        }
    }

    function closeGameRoom(){
        var sId = $(".game-info").attr("data-game-id");
        var token = $('input[name="token"]').val();
        var jData = {};
        jData.id = sId;
        jData.token = token;
        if ($(".game-user-list")[0]){
            $.ajax({
                "url":"server/close_game.php",
                "method":"post",
                "data": {"data":jData},
                "cache":false
            }).done(function(data){
                window.location.replace(data);
            });
        }
    }

    function submitQuestion(oElement){
        var sQuestion = $(oElement).find("#lblQuestion").val();
        var token = $(oElement).find('input[name="token"]').val();
        var sAnswer1 = $(oElement).find("#lblAnswer1").val();
        var sAnswer2 = $(oElement).find("#lblAnswer2").val();
        var sAnswer3 = $(oElement).find("#lblAnswer3").val();
        var sAnswer4 = $(oElement).find("#lblAnswer4").val();
        var sCorrectAnswer = $(oElement).find("#lblCorrectAnswer").val();

        var aAnswers = [];
        aAnswers.push(sAnswer1, sAnswer2, sAnswer3, sAnswer4);

        var iAnswers = 0;
        var iCounter = aAnswers.length;
        for(var i = 0; i < iCounter; i++){
            if(aAnswers[i] != ""){
                var sTest = aAnswers[i];
                sTest = sTest.trim();
                if(sTest != ""){
                    iAnswers++;
                }
            } else {
                aAnswers.splice(i, 1);
                i--
            }
        }

        var jAnswers = {"answers": aAnswers};

        if(iAnswers > 1 && sQuestion != "" && sCorrectAnswer != "" && Number(sCorrectAnswer)){
            var jData = {};
            jData.question = sQuestion;
            jData.answers = jAnswers;
            jData.correct_answer = sCorrectAnswer;
            jData.token = token;

            if ($('form[name="questionsubmit"]')[0]){
                $.ajax({
                    "url":"server/submit_question.php",
                    "method":"post",
                    "data": {"data":jData},
                    "cache":false
                }).done(function(){
                    window.location.reload();
                });
            }
        }
    }

    function handleQuestionBackend(status, sId){
        var jData = {};
        jData.status = status;
        jData.id = sId;
        jData.token = $(oElement).find('input[name="token"]').val();

        if ($('.question-admin-actions')[0] && Number(status)){
            $.ajax({
                "url":"server/question_status.php",
                "method":"post",
                "data": {"data":jData},
                "cache":false
            }).done(function(){
                window.location.reload();
            });
        }
    }

    var timeLeft = "";
    var oElement = "";
    var timerId = setInterval(function(){
        countdown();
    }, 1000);

    function newGame(){
        var jData = {};
        jData.token = $(oElement).find('input[name="token"]').val();
        if ($('.new-game')[0]){
            $.ajax({
                "url":"server/new_game.php",
                "method":"post",
                "data": {"data":jData},
                "cache":false
            }).done(function(data){
                //Get current unix timestamp (seconds)
                var dBegin = Math.floor(Date.now() / 1000);
                //subtract current time from server time to get timer until game begins
                var iDiff = Number(data) - dBegin;
                //insert counter
                var sHtml = "<div class='game-begin-timer'><h3>"+iDiff+"</h3></div>";
                if (!$('.game-begin-timer')[0]){
                    $(".game-info").append(sHtml);
                } else {
                    $('.game-begin-timer').text(iDiff);
                }
                timeLeft = iDiff;
                oElement = $(".game-container").find(".game-begin-timer h3");
                clearTimeout(timerId);
                timerId = setInterval(function(){
                    countdown();
                }, 1000)
            });
        }
    }

    function startGame(){
        $("#wdw-game-modal").fadeIn();
    }

    function countdown(){
        if (timeLeft === 0) {
            clearTimeout(timerId);
            //do something
            $(oElement).text(0);
            setTimeout(function(){
                $(".game-begin-timer").fadeOut(500, function(){
                    $(this).remove();
                });
            }, 2500);
            startGame();
        } else {
            $(oElement).text(timeLeft);
            timeLeft--;
        }
    }

    populateUserList();

    setInterval(function(){
        populateUserList();
    }, 10000);

});


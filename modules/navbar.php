<body>

<nav class="navbar">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->


        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="index.php">HOME</a></li>
                <li><a class="reset-bar"> | </a></li>
                <li><a href="leaderboard.php">LEADERBOARD</a></li>
                <li><a class="reset-bar"> | </a></li>
                <li><a href="about.php">ABOUT</a></li>

            </ul>
            
            

            <!--<ul class="nav navbar-nav navbar-right">
                <li><a href="login.php">LOGIN</a></li>
                <li><a class="reset-bar"> | </a></li>
                <li><a href="register.php">REGISTER</a></li>
            </ul>-->

            <?php

            if (login_check($pdo) == true) {
                ?>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="server/logout.php">LOGOUT (<?php echo htmlentities($_SESSION['username']); ?>)</a></li>
                    <li><a class="reset-bar"> | </a></li>
                    <li><a href="hub.php">HUB</a></li>
                    <li><a class="reset-bar"> | </a></li>
                    <li><a href="chat.php">CHAT</a></li>
                    <li><a class="reset-bar"> | </a></li>
                    <li><a href="profile.php">PROFILE</a></li>
                </ul>
                <?php
            } else {
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="login.php">LOGIN</a></li>
                    <li><a class="reset-bar"> | </a></li>
                    <li><a href="register.php">REGISTER</a></li>
                </ul>
                <?php
            }
            ?>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
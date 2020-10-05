</head>
<?php
require_once ('connectvars.php');
require_once ('CheckForAdmin.php');
?>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
<div class="jumbotron text-center">
    <h1>Essays Bay</h1>
    <a href="index.php"><p>Become a better writer with Us</p></a>

    <form id="search" action="<?php echo 'index.php'; ?>">
        <input id="s_bar" type="text" name="tag_name" placeholder="Search...">
        <input type="submit">
    </form>

</div>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand home" href="index.php">Home</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="about.php">ABOUT</a></li>
                <li><a href="contact.php">CONTACT</a></li>
                <li><a href="categories.php">Categories</a></li>

                <?php
                //if logged
                if (isset($_SESSION['username']))
                {
                    $isAdmin = CheckForAdmin::isAdmin();

                //if admin
                    if ($isAdmin == 1)
                    {

                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                        // Retrieve the post data from MySQL
                        $query = "SELECT COUNT(work_id) AS total FROM works  WHERE approved=0";
                        $data = mysqli_query($dbc, $query);
                        $row = mysqli_fetch_array($data);
                        $total = $row['total'];
                        $dbc->close();

                        ?>
                        <li><a href="approve.php">Approve <span class="app-num">(<?php echo $total; ?>)</span></a></li>
                        <?php
                    }
                    ?>

                    <li><a href="newWork.php">New Work</a>&nbsp;</li>
                    <li ><div class="dropdown">
                            <button class="dropbtn">My Account <span class="glyphicon glyphicon-chevron-down"></span></button>
                            <div class="dropdown-content">
                                <a href="edit_profile.php?edit=0">Edit Profile</a>
                                <a href="index.php?profile=<?php echo $_SESSION['user_id']; ?>">View Profile</a>
                                <a href="logout.php">Log Out</a>

                            </div>
                        </div>
                    </li>
                    <?php
                }
                else
                { ?>
                    <li><a href="signup.php">Sign Up</a></li>
                    <li><a href="login.php">Log In</a></li>
                    <?php
                } ?>

            </ul>
        </div>
    </div>
</nav>
<body>
<div id="main" class="container-fluid text-center bg-grey">

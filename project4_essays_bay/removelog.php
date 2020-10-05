<?php
require_once ('tool_shortcuts/start_session.php');
require_once ('tool_shortcuts/connectvars.php');

if (!isset($_SESSION['user_id']) && !isset($_SESSION['super_user']))
{
    echo '<p class="login">Please <a href="login.php">log in</a> to access this page.</p>';
    exit();
}
else
{

    if (isset($_GET['work_id']))
    {
        // Grab the user's work_id from the GET
        $work_id = $_GET['work_id'];
    }
    else if (isset($_POST['work_id']))
    {
        // Grab the user's id from the POST(this page)
        $work_id = $_POST['work_id'];
    }


    //deleting an eesay
    if (isset($_POST['submit']) && $_POST['confirm'] == 'yes')
    {

        // Connect to the database
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        // Delete the score data from the database
        $query = "DELETE FROM works_tags WHERE work_id = $work_id";
        mysqli_query($dbc, $query) or die("err-query-work_tags_table");

        $query = "DELETE FROM works WHERE work_id = $work_id";
        //echo $work_id;
        mysqli_query($dbc, $query) or die("err-query-works_table");
        mysqli_close($dbc);
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/approve.php?note=1';
        header('Location: ' . $home_url);

    }
    else if (isset($_POST['submit']) && $_POST['confirm'] == 'no')
    {
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/approve.php?note=2';
        header('Location: ' . $home_url);

    }

    //for flexible templates
    require_once ('tool_shortcuts/header.php'); ?>
    <link rel="stylesheet" href="styleSheets/removelog.css">
    <title>Delete</title>
    <?php
    require_once ('tool_shortcuts/nav_sector.php');
    require_once ('tool_shortcuts/appvars.php');

     if (isset($work_id))
    {
        ?>
        <p>Are you sure you want to delete the following essay?</p>
        <form method="post" action="removelog.php">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="defaultGroupExample1" value="yes" name="confirm">
                <label class="custom-control-label text-warning" for="defaultGroupExample1">YES</label>
            </div>

            <!-- Group of default radios - option 2 -->
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="defaultGroupExample2" value="no" name="confirm" checked>
                <label class="custom-control-label text-success" for="defaultGroupExample2">NO</label>
            </div>
            </div>

            </div>
            <input type="submit" value="Submit" name="submit" />
            <input type="hidden" name="work_id" value="<?php echo $work_id ?>" />

        </form>

        <?php
    }
}

?>

</body>
</html>

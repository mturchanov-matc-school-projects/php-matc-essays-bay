<?php
require_once ('CountWorks.php');

class VoidForFooter
{   public static function voidFooter($title)
    {
    if (isset($_GET['profile']))
    {
        return;
    }

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $query = "SELECT COUNT(work_id) AS num_works FROM works WHERE approved=1";
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);
    $num_works = $row['num_works'];
    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);


    if (isset($_GET['work_id']))
    {
        $work_id = $_GET['work_id'];
        // Retrieve the post data from MySQL
        $query = "select * from users WHERE user_id IN (SELECT user_id FROM works WHERE work_id=$work_id)";
        $data = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($data);
        $name = $row['first_name'] . ' ' . $row['last_name'];
        ?>
        <h2><?php echo $name; ?>'S WORK</h2>
        <?php
    }

    else if ($num_works == 0)
    {
        ?>
        <h2><?php echo $title; ?></h2>
        <div class="void-footer"></div>
        <?php
    }
    else if($curPageName == 'categories.php')
    {
        echo '<h2>Categories</h2><br><br>';
    }
    else if($curPageName == 'index.php' && isset($_GET['tag_name']))
    {
        $tag_name = $_GET['tag_name'];
        $works_tag_num = CountWorks::num_works_not_tag($tag_name);
        if($works_tag_num == 0)
        {
            ?>
            <h2>Sorry no works are found with such keywords in the title</h2>
            <?php
        }
    }
    else
    {
        ?>
        <h2>Most new essays</h2>
        <?php
    }
}
}
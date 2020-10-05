<?php
require_once ('tool_shortcuts/start_session.php');
require_once ('tool_shortcuts/header.php'); ?>
<link rel="stylesheet" href="styleSheets/index.css">
<title>Home</title>
<?php
require_once ('tool_shortcuts/nav_sector.php');
require_once ('tool_shortcuts/connectvars.php');
require_once ('tool_shortcuts/appvars.php');
require_once ('tool_shortcuts/EditTags.php');
require_once ('tool_shortcuts/PreviewWorks.php');
require_once ('tool_shortcuts/CheckForAdmin.php');
require_once ('tool_shortcuts/VoidForFooter.php');
require_once ('tool_shortcuts/CountWorks.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
VoidForFooter::voidFooter("No works written, yet");

//displays the one chosen essay
if (isset($_GET['work_id']))
{
$work_id = $_GET['work_id'];

// Retrieve the essay data from db
$query = "SELECT * FROM works WHERE work_id=$work_id";
$data = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($data);
$isAdmin = CheckForAdmin::isAdmin();

?>
    <h2><?php echo $row['title']; ?></h2>

<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-body"><?php echo $row['content']; ?></div>
    </div>
    <hr>
    <br>

    <?php
    //if a user is an admin then provide super-options
    if ($isAdmin == 1)
    {
        ?>
        <a href="removelog.php?work_id=<?php echo $work_id; ?>&operation=delete">
            <button type="button" class="btn btn-warning">Delete</button>
        </a>
        <?
        $query = "SELECT approved FROM works WHERE work_id=$work_id";
        $data = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($data);
        $isApproved = $row['approved'];
        if ($isApproved == 0)
        {
            ?>
            <a href="approve.php?work_id=<?php echo $work_id; ?>&operation=approve">
                <button type="button" class="btn btn-success">Approve</button>
            </a>
            <?php
        }
    }
}
else
{
    //if there is a redirection from searchbar
    // or tag-pick(categories-page) then show filtered options
    if (isset($_GET['tag_name']) || isset($_POST['tag_name']))
    {


        $tag_name = $_GET['tag_name'];
        $query = "SELECT works.work_id,works.user_id, title, content, works.date, approved, first_name, last_name, tags.name AS tag_name FROM works
                  LEFT OUTER JOIN users ON works.user_id = users.user_id 
                  INNER JOIN works_tags ON works.work_id=works_tags.work_id 
                  LEFT OUTER JOIN tags ON works_tags.tag_id=tags.tag_id 
                  WHERE approved=1 AND tags.name LIKE'%$tag_name%' OR works.title LIKE '%$tag_name%' GROUP BY works.work_id";

    }
    //show user's profile and his/her essys
    else if (isset($_GET['profile']))
    {
        //process user's profile info
        $user_id = $_GET['profile'];
        $query = "SELECT * FROM users WHERE user_id=$user_id";

        $data = mysqli_query($dbc, $query) or die(mysqli_error());
        $row = mysqli_fetch_array($data);

        $total_works = CountWorks::num_works($user_id);
        $user_date = $row['date'];
        $user_full_name = $row['first_name'] . ' ' .  $row['last_name'];
        $user_age = $row['age'];
        $user_note = $row['note'];
        $user_picture = $row['picture'];
        $target = MM_UPLOADPATH . $row['picture'];
        if ($total_works == 0)
        {
            $author_works = "Author hasn't an written works, yet";
        } else
        {
            $author_works = $user_full_name . "'s Works:";
        }


        $query = "SELECT works.work_id,works.user_id, title, content, works.date, approved, first_name, last_name, tags.name AS tag_name FROM works 
                  LEFT OUTER JOIN users ON works.user_id = users.user_id 
                  INNER JOIN works_tags ON works.work_id=works_tags.work_id 
                  LEFT OUTER JOIN tags ON works_tags.tag_id=tags.tag_id 
                  WHERE approved=1 ORDER BY work_id DESC ";

        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <h2><?php echo $user_full_name; ?>'s Profile</h2>
                    <h3 class="profile-about">About me</h3>
                    <p class="aboutUser"><?php echo $user_note; ?></p>
                </div>

                <div class="col-sm-4">
                    <span class="picture"><img id="myImg" class="postPic" src="<?php echo $target; ?>" alt="prof-pic"> </span> <br>
                    <div class="prof-info">Age: <?php echo $user_age; ?></div>
                    <div class="prof-info">Registration Date: <?php echo $user_date; ?></div>
                    <div class="prof-info">Total Works Written: <?php echo $total_works; ?></div>
                </div>
            </div>
        </div>
        <br>
        <hr color="black">
        <br>
        <h2><?php echo $author_works; ?></h2>
        <?php
        $query = "SELECT work_id, title,works.user_id, content, works.date, approved, first_name, last_name FROM works 
                  LEFT OUTER JOIN users ON works.user_id = users.user_id 
                  WHERE approved=1 AND works.user_id=$user_id ORDER BY work_id DESC";
    }
    else
    {
        $query = "SELECT work_id, title,works.user_id, content, works.date, approved, first_name, last_name FROM works 
                  LEFT OUTER JOIN users ON works.user_id = users.user_id 
                  WHERE approved=1 ORDER BY work_id DESC";
    }

    //parse all works
    PreviewWorks::showWorks($query);
}
mysqli_close($dbc);

?>
</div>
</div>

<?php
require_once ('tool_shortcuts/footer.php');
?>

</html>

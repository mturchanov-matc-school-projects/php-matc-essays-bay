<?php
require_once('tool_shortcuts/start_session.php');
require_once('tool_shortcuts/header.php');?>
<link rel="stylesheet" href="styleSheets/createPost.css">
<script src='https://cdn.tiny.cloud/1/evhz9ibt5vq7o81qsftwm0qa0wb95w6wvshjlfu14lc0oznm/tinymce/5/tinymce.min.js' referrerpolicy="origin"></script>
<title> New Essay</title>

<?php
require_once('tool_shortcuts/nav_sector.php');
require_once('tool_shortcuts/appvars.php');
require_once('tool_shortcuts/connectvars.php');

// Make sure the user is logged in before going any further.
?>
<h2>Add a new Essay</h2>
<?php
if (!isset($_SESSION['user_id']))
{
    echo '<p class="login">Please <a href="login.php">log in</a> to access this page.</p>';
    exit();
}
else
{
    // Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if (isset($_POST['submit']))
    {

        // Grab the profile data from the POST

        $title = mysqli_real_escape_string($dbc, trim($_POST['title']));
        $content = mysqli_real_escape_string($dbc, trim($_POST['content']));
        $tags = mysqli_real_escape_string($dbc, trim($_POST['tags']));
        $user_id = $_SESSION['user_id'];


        // process input article/essay
        if (!empty($title) && !empty($content) && !empty($tags))
        {
            //store article/essay to DB
            $query = "INSERT INTO works(user_id, title, content) VALUES ('$user_id','$title','$content')";

            mysqli_query($dbc, $query) or die("err que");

            //break tags to separate ones and adequately process them
            $tags_arr = preg_split("/(,|,\s+)/",$tags);

            //store tags to DB
            foreach( $tags_arr as $tag) {
                $query = "SELECT * FROM tags WHERE name = '$tag'";
                $data = mysqli_query($dbc, $query);
                if (mysqli_num_rows($data) == 0)
                {
                    $query = "INSERT INTO tags(name) VALUES('$tag')";
                    mysqli_query($dbc, $query) or die('tag_err_query');

                    //because tags-works has many to many relationship, there's a bridge table
                    $query = "insert into works_tags (user_id, tag_id,work_id)VALUES ('$user_id',(SELECT tag_id FROM tags WHERE name='$tag'),(SELECT work_id FROM works WHERE user_id = '$user_id' AND title='$title'))";
                    mysqli_query($dbc, $query) or die('<p>This account already uploaded such work</p>');
                } else{


                    $query = "insert into works_tags (user_id, tag_id,work_id)VALUES ('$user_id',(SELECT tag_id FROM tags WHERE name='$tag'),(SELECT work_id FROM works WHERE user_id = '$user_id' AND title='$title'))";
                    mysqli_query($dbc, $query) or die('<p>This account already uploaded such work</p>');
                }
            }


            // Confirm success with the user
            echo '<p>Your post has been successfully added. Would you like ' . 'to <a href="index.php">return to the homepage</a>?</p>';
            mysqli_close($dbc);
            exit();
        }
        else
        {
            echo 'You must upload a picture';
        }
    }
}


?>
<form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Title:</label>
        <div class="col-sm-4">
            <input type="text" name="title" class="form-control inputstl" id="name1" placeholder="Enter title of the essay/article"
                   value="<?php if (!empty($title)) echo $title; ?>" />
        </div>
    </div>

    <div class="form-group">
        <label for="tags" class="col-sm-2 control-label">Tags:</label>
        <div class="col-sm-4">
            <input type="text" name="tags" class="form-control inputstl" id="name1" placeholder="List tags of your essay/article separated by space/comma"
                   value="<?php if (!empty($tags)) echo $tags; ?>" />
        </div>
    </div>


    <div class="form-group">
        <label for="content" class="col-sm-2 control-label">Content:</label>
        <div class="col-sm-5">
            <textarea name="content" cols="65" rows="20"><?php if (!empty($content)) echo $content; ?></textarea>
        </div>
    </div>


        <br>
        <input id="sub" type="submit" value="Submit" name="submit" />
</form>
<script src="tool_shortcuts/initWYSIWYG.js"></script>

<?php
//wysiwyg script
require_once ('tool_shortcuts/footer.php');
?>

</body>
</html>

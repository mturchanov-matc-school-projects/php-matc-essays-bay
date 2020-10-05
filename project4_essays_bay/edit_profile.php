<?php
require_once ('tool_shortcuts/start_session.php');
//for flexible templates
require_once ('tool_shortcuts/header.php'); ?>
<title>Edit Profile</title>
<link rel="stylesheet" href="styleSheets/edit_profile.css">

<?php
require_once ('tool_shortcuts/nav_sector.php');
require_once ('tool_shortcuts/appvars.php');
require_once ('tool_shortcuts/connectvars.php');

?>
<h2>Edit profile</h2>

<?php
if ($_GET['edit'] == 0)
{
    ?>
    <a href="edit_profile.php?edit=1"><p class="edit_p">Do you want to change password?</p></a>
    <br>
    <a href="edit_profile.php?edit=2"><p class="edit_p">Do you want to change your personal information?</p></a>
    <br>
    <a href="edit_profile.php?edit=3"><p class="edit_p">Do you want to change your profile picture?</p></a>

    <?php
}

// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//change data based on user's choice
if (isset($_POST['change_profile_info']))
{
    // Grab the profile data from the POST
    $user_id = $_SESSION['user_id'];
    $first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
    $last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    $age = mysqli_real_escape_string($dbc, trim($_POST['age']));
    $age = $age == '' ? 0 : $age;
    $note = mysqli_real_escape_string($dbc, trim($_POST['note']));
    if (!empty($first_name) && !empty($last_name) && !empty($age) && !empty(note))
    {
        $query = "UPDATE users SET first_name = '$first_name' WHERE user_id=$user_id";
        mysqli_query($dbc, $query) or die(mysqli_error());
        $query = "UPDATE users SET last_name = '$last_name' WHERE user_id=$user_id";
        mysqli_query($dbc, $query) or die(mysqli_error());
        $query = "UPDATE users SET age = $age WHERE user_id=$user_id";
        mysqli_query($dbc, $query) or die(mysqli_error());
        $query = "UPDATE users SET note = '$note' WHERE user_id=$user_id";
        mysqli_query($dbc, $query) or die(mysqli_error());

        ?>
        <br>
        <br>
        <hr>
        <h3>You successfully have updated your personal information!</h3>
        <?php
    }

}
else if (isset($_POST['change_pswrd']) && $_POST['password1'] == $_POST['password2'])
{
    $password3 = mysqli_real_escape_string($dbc, trim($_POST['password3']));
    $user_id = $_SESSION['user_id'];

    $query = "UPDATE users SET password = SHA('$password3') WHERE user_id=$user_id";
    mysqli_query($dbc, $query) or die(mysqli_error());

    // Confirm success with the user

    ?>
    <br>
    <br>
    <hr>
    <h3>You successfully have changed your password!</h3>
    <?php
    mysqli_close($dbc);

}
else if (isset($_POST['change_picture']))
{
    $user_id = $_SESSION['user_id'];
    $new_picture = mysqli_real_escape_string($dbc, trim($_FILES['new_picture']['name']));
    $new_picture_type = $_FILES['new_picture']['type'];
    $new_picture_size = $_FILES['new_picture']['size'];
    @list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);

    if ($new_picture_type != 'image/gif' || $new_picture_type != 'image/jpeg' || $new_picture_type != 'image/pjpeg' || $new_picture_type != 'image/png' || $new_picture_size < 0 || $new_picture_size >= MM_MAXFILESIZE || $new_picture_width >= MM_MAXIMGWIDTH || $new_picture_height >= MM_MAXIMGHEIGHT)
    {

        $target = MM_UPLOADPATH . basename($new_picture);
        $user_id = $_SESSION['user_id'];

        move_uploaded_file($_FILES['new_picture']['tmp_name'], $target);

        $query = "UPDATE users SET picture = '$new_picture' WHERE user_id=$user_id";
        mysqli_query($dbc, $query) or die(mysqli_error());

        ?>
        <br>
        <br>
        <hr>
        <h3>You successfully have changed your profile picture!</h3>
        <?php
    }
    else
    {
        echo 'Not valid picture';
    }

}
else if ($_GET['edit'] == 1)
{
    ?>

    <form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

        <div class="form-group">
            <label for="password1" class="col-sm-5 control-label">Password:</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" placeholder="Old password" name="password1" />
            </div>
        </div>

        <div class="form-group">
            <label for="password1" class="col-sm-5 control-label">Password (retype):</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" placeholder="Old password" name="password2" />
            </div>
        </div>
        <br>
        <br>
        <div class="form-group">
            <label for="password2" class="col-sm-5 control-label">New password:</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" placeholder="New password" name="password3" />
            </div>
        </div>
        <input id="sub" type="submit" value="Change Password" name="change_pswrd" />

    </form>


    <?php
}
else if ($_GET['edit'] == 2)
{
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE user_id = $user_id";
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);

    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $age = $row['age'];
    $note = $row['note'];
    $picture = $row['picture'];

    mysqli_close($dbc);
    ?>

    <form class="form-horizontal" enctype="multipart/form-data" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>">

        <div class="form-group">
            <label for="first_name" class="col-sm-2 control-label">First Name:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="first_name"
                       value="<?php if (!empty($first_name)) echo $first_name; ?>"/><br/>
            </div>
        </div>

        <div class="form-group">
            <label for="last_name" class="col-sm-2 control-label">Last Name:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="last_name"
                       value="<?php if (!empty($last_name)) echo $last_name; ?>"/><br/>
            </div>
        </div>

        <div class="form-group">
            <label for="age" class="col-sm-2 control-label">Age:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="age" value="<?php if (!empty($age)) echo $age; ?>"/><br/>
            </div>
        </div>

        <div class="form-group">
            <label for="note" class="col-sm-2 control-label">Description:</label>
            <div class="col-sm-3">
                <textarea name="note" id="note"
                          placeholder="Enter what topics you like, exerience in writing, or anything you want to share"
                          cols="55" rows="6"><?php if (!empty($note)) echo $note; ?></textarea>
                <br/>
            </div>
        </div>



        <br>
        <div class="form-group col-sm-6">
        </div>
        <input id="sub" type="submit" value="ADD POST" name="change_profile_info"/>
    </form>

    <?php
}
else if ($_GET['edit'] == 3)
{
    ?>
    <form class="form-horizontal" enctype="multipart/form-data" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <label for="pic" class="col-sm-2 control-label">Picture:</label>
            <div class="file-field">
                <div class="col-sm-5">
                    <input type="file" id="new_picture" name="new_picture"
                           value="<?php if (!empty($picture)) echo $picture; ?>">
                </div>
            </div>
            <br>
        </div>
        </div>

        <br>
        <div class="form-group col-sm-6">
        </div>
        <input id="sub" type="submit" value="ADD POST" name="change_picture"/>
    </form>

    <?php
}
?>
<div class="void-footer"></div>
<?php
require_once ('tool_shortcuts/footer.php');
?>
</html>

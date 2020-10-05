<?php
require_once ('tool_shortcuts/start_session.php');
//for flexible templates
require_once ('tool_shortcuts/header.php'); ?>
<title> Sign Up</title>
<link rel="stylesheet" href="styleSheets/signup.css">
<?php

require_once ('tool_shortcuts/nav_sector.php');
require_once ('tool_shortcuts/appvars.php');
require_once ('tool_shortcuts/connectvars.php');

// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//process input data
if (isset($_POST['submit']))
{
    // Grab the profile data from the POST
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));
    $first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
    $last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    $age = mysqli_real_escape_string($dbc, trim($_POST['age']));
    $age = $age == '' ? 0 : $age;
    $note = mysqli_real_escape_string($dbc, trim($_POST['note']));
    $new_picture = mysqli_real_escape_string($dbc, trim($_FILES['new_picture']['name']));
    $new_picture = $new_picture == '' ? 'nopic.png' : $new_picture;
    $new_picture_type = $_FILES['new_picture']['type'];
    $new_picture_size = $_FILES['new_picture']['size'];
    @list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);

    if (!empty($username) && !empty($password1)
        && !empty($password2)
        && ($password1 == $password2)
        && ($new_picture_type != 'image/gif')
        || ($new_picture_type != 'image/jpeg')
        || ($new_picture_type != 'image/pjpeg')
        || ($new_picture_type != 'image/png')
        || ($new_picture_size < 0)
        || ($new_picture_size >= MM_MAXFILESIZE)
        || ($new_picture_width >= MM_MAXIMGWIDTH)
        || ($new_picture_height >= MM_MAXIMGHEIGHT))
    {


        // Make sure someone isn't already registered using this username
        $query = "SELECT * FROM users WHERE username = '$username'";
        $data = mysqli_query($dbc, $query);
        if (mysqli_num_rows($data) == 0)
        {

            $target = MM_UPLOADPATH . basename($new_picture);
            move_uploaded_file($_FILES['new_picture']['tmp_name'], $target);
            //INSERT INTO users(username,password,first_name,last_name,age) VALUES('test','12345','m','t',24);
            // The username is unique, so insert the data into the database
            $query = "INSERT INTO users (username, password, first_name, last_name, age, note,picture)"
                . "VALUES ('$username', SHA('$password1'), '$first_name', '$last_name', '$age', '$note','$new_picture')";
            mysqli_query($dbc, $query) or die(mysqli_error());

            // Confirm success with the user
            echo '<p>Your new account has been successfully created. You\'re now'
                . ' ready to <a href="login.php">log in </a>.</p>';

            mysqli_close($dbc);
            ?>
            <div class="void-footer"></div>
            <?php
            require_once ('tool_shortcuts/footer.php');

            exit();
        }
        else
        {
            // An account already exists for this username, so display an error message
            echo '<p class="error">An account already exists for this username. Please use a different address.</p>';
            $username = "";
        }
    }
    else
    {
        echo '<p class="error">You must enter all of the sign-up data, including the desired password twice.</p>';
    }
}
mysqli_close($dbc);
?>

<form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

    <div class="form-group">
        <label for="username" class="col-sm-3 control-label">Username*:</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  required name="username" value="<?php if (!empty($user_username)) echo $user_username; ?>" /><br />
        </div>
    </div>
    <div class="form-group">
        <label for="password1" class="col-sm-3 control-label">Password*:</label>
        <div class="col-sm-4">
            <input type="password"  required  class="form-control" name="password1" />
        </div>
    </div>

    <div class="form-group">
        <label for="password2" class="col-sm-3 control-label">Password*(retype):</label>
        <div class="col-sm-4">
            <input type="password"  required  class="form-control" name="password2" />
        </div>
    </div>

    <div class="form-group">
        <label for="first_name" class="col-sm-3 control-label">First Name*:</label>
        <div class="col-sm-4">
            <input type="text"  required  class="form-control" name="first_name" value="<?php if (!empty($first_name)) echo $first_name; ?>" /><br />
        </div>
    </div>

    <div class="form-group">
        <label for="last_name" class="col-sm-3 control-label">Last Name*:</label>
        <div class="col-sm-4">
            <input type="text"  required  class="form-control" name="last_name" value="<?php if (!empty($last_name)) echo $last_name; ?>" /><br />
        </div>
    </div>

    <div class="form-group">
        <label for="age" class="col-sm-3 control-label">Age*:</label>
        <div class="col-sm-4">
            <input type="text"   required class="form-control" name="age" value="<?php if (!empty($age)) echo $age; ?>" /><br />
        </div>
    </div>

    <div class="form-group">
        <label for="note" class="col-sm-3 control-label">Description*:</label>
        <div class="col-sm-3">
            <textarea name="note" required id="note"
                      placeholder="Enter what topics you like, exerience in writing, or anything you want to share" cols="45" rows="6"><?php if (!empty($note)) echo $note; ?></textarea>
            <br />
        </div>
    </div>

    <div class="form-group">
        <label for="pic" class="col-sm-3 control-label">Picture <small>(optional)</small>:</label>
        <div class="file-field">
            <div class="col-sm-6">
                <input type="file" id="new_picture" name="new_picture" >
            </div>
        </div>
        <br>
    </div>
    <br>
    <input id="sub" type="submit" value="Submit" name="submit" />

    </div>

    <br>
    <div class="form-group col-sm-6">

    </div>
</form>

<?php    require_once('tool_shortcuts/footer.php');
?>
</html>

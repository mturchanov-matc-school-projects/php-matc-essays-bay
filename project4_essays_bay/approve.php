<?php
require_once ('tool_shortcuts/start_session.php');
require_once ('tool_shortcuts/connectvars.php');
require_once ('tool_shortcuts/PreviewWorks.php');
require_once ('tool_shortcuts/CountWorks.php');
require_once ('tool_shortcuts/header.php'); ?>

<link rel="stylesheet" href="styleSheets/approve.css">
<title>Approve - Admin</title>
<?php
require_once ('tool_shortcuts/EditTags.php');
require_once ('tool_shortcuts/nav_sector.php');
require_once ('tool_shortcuts/appvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//set appropriate message
if (isset($_GET['note']))
{
    $successful_delete = 'The record was successfully deleted';
    $no_delete = 'The record was not deleted';
    $note = $_GET['note'] == 1 ? $successful_delete : $no_delete;
    ?>
    <h2><?php echo $note; ?> <br><br><a href="index.php">Do you want to return?</a></h2>
    <div class="void-footer"></div>
    <?php
}
else if (isset($_GET['work_id']) && $_GET['operation'] == 'approve')
{
    $work_id = $_GET['work_id'];
    $query = "UPDATE works SET approved=1 WHERE work_id = $work_id";
    mysqli_query($dbc, $query) or die('approve_err_query');
    ?>
    <h2>You successfully have approved the work. <a href="index.php">Do you want to return?</a></h2>
    <div class="void-footer"></div>
    <?php

}
else
{
    // message if nothing ti approve
    $query = "SELECT * FROM works WHERE approved=0";
    PreviewWorks::showWorks($query);
    $not_approved_num = CountWorks::num_works_not_approved();
    if ($not_approved_num == 0)
    {
        ?>
        <h2>All works are approved</h2>
        <div class="void-footer"></div>
        <?php
    }
}

require_once ('tool_shortcuts/footer.php');
?>
</body>
</html>

<?php
require_once ('tool_shortcuts/start_session.php');
require_once ('tool_shortcuts/connectvars.php');
require_once ('tool_shortcuts/header.php'); ?>
    <link rel="stylesheet" href="styleSheets/categories.css">
    <title>Categories</title>

<?php
require_once ('tool_shortcuts/VoidForFooter.php');
require_once ('tool_shortcuts/nav_sector.php');
require_once ('tool_shortcuts/appvars.php');
require_once ('tool_shortcuts/CountWorks.php');
VoidForFooter::voidFooter("No categories (no works, yet)");


?>

<main>
    <section id="category-list">
        <div class="mb-5">
            <ul class="list-unstyled list-columns">
    <?php
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$query = "SELECT tags.name,works_tags.work_id, COUNT(works_tags.tag_id) AS tag_total FROM works_tags
                    LEFT JOIN tags ON works_tags.tag_id = tags.tag_id 
                    INNER JOIN works ON works.work_id=works_tags.work_id 
                    WHERE approved=1 GROUP BY name";
$data = mysqli_query($dbc, $query) or die(mysqli_error());
$tags_arr = [];

//display tags
while($row = mysqli_fetch_array($data)){
?>
    <li>
        <a href="index.php?tag_name=<?php echo $row['name']; ?>" class="d-flex justify-content-between align-items-center text-dark">
            <?php echo $row['name']; ?><span class="badge badge-secondary"><?php echo $row['tag_total'] ?></span>
        </a>
    </li>
 <?php
}
?>
            </ul>
        </div>
    </section>
</main>
</div>

<?php
$count_approved = CountWorks::num_works_approved();
if($count_approved < 10){
    ?><div class="void-footer"></div><?php
}
require_once ('tool_shortcuts/footer.php');

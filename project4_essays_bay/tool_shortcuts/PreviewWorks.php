<?php


class PreviewWorks
{
    public static function showWorks($query){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        $data = mysqli_query($dbc, $query);

        // Loop through the array of user data, and provide the fresh posts
        while ($row = mysqli_fetch_array($data))
        {
            $work_id = $row['work_id'];
            $work_user_id = $row['user_id'];
            $title = $row['title'];
            $content = $row['content'];
            $date = $row['date'];
            $author_name = $row['last_name'] . ' ' . $row['first_name'];
            $count_words = count(preg_split("/\s+/", $content));
            $editTags = EditTags::edit($work_id);
            ?>
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="index.php?work_id=<?php echo $work_id; ?>">
                            <?php echo "<h3 class='title'>$title</h3> 
                </a> 
                <a href='categories.php'>
                   <small class='tags'>Tags: $editTags</small>"; ?>
                        </a>
                    </div>
                    <div class="panel-body">
                        <a href="index.php?work_id=<?php echo $work_id; ?>">
                            <?php echo mb_substr($content, 0, 1200) . '[...]'; ?>
                        </a>
                    </div>
                    <div class="panel-footer">
                        <span class='date text-left'><?php echo $date ?></span>
                        <a href="index.php?profile=<?php echo $work_user_id; ?>">
                            <span class='authName text-center'><?php echo $author_name; ?></span>
                        </a>
                        <span class='wordsLen text-right'><?php echo $count_words; ?> words</span>
                    </div>

                </div>
            </div>
            <hr class="work_delim">
            <?php
        }
    }
}
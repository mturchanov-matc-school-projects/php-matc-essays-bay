<?php


class EditTags
{

    public static function edit($work_id){

            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            $query = "SELECT * FROM tags WHERE tags.tag_id IN (SELECT works_tags.tag_id FROM works_tags WHERE tags.tag_id = works_tags.tag_id AND works_tags.work_id IN (SELECT works.work_id FROM works WHERE works.work_id=works_tags.work_id AND work_id=$work_id))";
            $data = mysqli_query($dbc, $query) or die(mysqli_error());
            $tags_arr = [];
            while ($row = mysqli_fetch_array($data))
            {
                $tags_arr[] = $row['name'];
            }
            return join(", ", $tags_arr);
        }

}
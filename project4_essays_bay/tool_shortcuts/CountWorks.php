<?php


class CountWorks
{
    public static function num_works($user_id)
    {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        $query = "SELECT COUNT(work_id) AS num FROM works WHERE user_id=$user_id AND approved=1";
        $data = mysqli_query($dbc, $query) or die(mysqli_error());
        $row = mysqli_fetch_array($data);
        return $row['num'];
    }

    public static function num_works_approved()
    {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT COUNT(work_id) AS num_works FROM works WHERE approved=1";
        $data = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($data);
        return $row['num_works'];
    }

    public static function num_works_not_approved()
    {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT COUNT(work_id) AS num_works FROM works WHERE approved=0";
        $data = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($data);
        return $row['num_works'];
    }

    public static function num_works_not_tag($tag_name)
    {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT COUNT(works.work_id) AS num_works from works
                    INNER JOIN works_tags ON works.work_id=works_tags.work_id 
                    INNER JOIN tags ON tags.tag_id=works_tags.tag_id WHERE name='$tag_name' ";
        $data = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($data);
        return $row['num_works'];
    }
}
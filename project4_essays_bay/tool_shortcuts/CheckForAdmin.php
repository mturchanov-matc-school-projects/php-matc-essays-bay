<?php


class CheckForAdmin
{
    public static function isAdmin(){
        $user_id = $_SESSION['user_id'];
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        // Retrieve the score data from MySQL
        $query = "SELECT super_user FROM users WHERE user_id = '$user_id'";
        $data = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($data);
        return $row['super_user'];
    }
}
<?php

include './functions.php.';

abstract class UserDao {

    public static function getUserByEmailPass($email, $pass) {
        $dbConn = databaseConnect('localhost', 'client_side', 'jffhnuPTGBQxyHMG', 'cpanel_users');
        $query = "SELECT * FROM user WHERE user_email = '{$email}' AND user_pass = '{$pass}'";
        $user = null;
        $result = $dbConn->query($query);
        //IF THERE IS A RESULT
        if (mysqli_num_rows($result) == 1):
            $arr = mysqli_fetch_assoc($result);
            $user = new User($arr["user_id"], $arr["user_email"], $arr["user_pass"], $arr["user_type"], $arr["user_active"]);
            if ($user->getActive() == 1):
                $user->setActive(true);
            else:
                $user->setActive(false);
            endif;
            databaseDisconnect($dbConn);
            return $user;
        else:
            databaseDisconnect($dbConn);
            return null;
        endif;
    }

    public static function getUserById($id) {
        $dbConn = databaseConnect('localhost', 'client_side', 'jffhnuPTGBQxyHMG', 'cpanel_users');
        $query = "SELECT * FROM user WHERE user_id = {$id}";
        $user = null;
        $result = $dbConn->query($query);
        //IF THERE IS A RESULT
        if (mysqli_num_rows($result) == 1):
            $arr = mysqli_fetch_assoc($result);
            $user = new User($arr["user_id"], $arr["user_email"], $arr["user_pass"], $arr["user_type"], $arr["user_active"]);
            if ($user->getActive() == 1):
                $user->setActive(true);
            else:
                $user->setActive(false);
            endif;
            databaseDisconnect($dbConn);
            return $user;
        else:
            databaseDisconnect($dbConn);
            return null;
        endif;
    }
    
    public static function getUserByEmail($email) {
        //USED IN FORGOTPASS.PHP
        $dbConn = databaseConnect('localhost', 'client_side', 'jffhnuPTGBQxyHMG', 'cpanel_users');
        $query = "SELECT * FROM user WHERE user_email = '{$email}'";
        $user = null;
        $result = $dbConn->query($query);
        //IF THERE IS A RESULT
        if (mysqli_num_rows($result) == 1):
            $arr = mysqli_fetch_assoc($result);
            $user = new User($arr["user_id"], $arr["user_email"], $arr["user_pass"], $arr["user_type"], $arr["user_active"]);
            if ($user->getActive() == 1):
                $user->setActive(true);
            else:
                $user->setActive(false);
            endif;
            databaseDisconnect($dbConn);
            return $user;
        else:
            databaseDisconnect($dbConn);
            return null;
        endif;
    }

    public static function getUserList() {
        //USED FOR ALL. RETURN A LIST OF USERS.
        $dbConn = databaseConnect('localhost', 'client_side', 'jffhnuPTGBQxyHMG', 'cpanel_users');
        $listUser = [];
        $query = "SELECT * FROM user";
        $result = $dbConn->query($query);

        if (mysqli_num_rows($result) > 0):  //IF THERE IS AT LEAST ONE ROW
            while ($arr = mysqli_fetch_assoc($result)):
                array_push($listUser, new User($arr['user_id'], $arr['user_email'], $arr['user_pass'], $arr['user_type'], $arr['user_active']));
            endwhile;
            //TREAT user_active
            foreach ($listUser as $user):
                if ($user->getActive() == 1):
                    $user->setActive(true);
                else:
                    $user->setActive(false);
                endif;
            endforeach;
            databaseDisconnect($dbConn);
            return $listUser;
        else:
            databaseDisconnect($dbConn);
            return null;
        endif;
    }

    public static function createUser($user) {
        $dbConn = databaseConnect('localhost', 'client_side', 'jffhnuPTGBQxyHMG', 'cpanel_users');
        $query = "INSERT INTO user (user_email, user_pass, user_type, user_active) VALUES ('{$user->getEmail()}', '{$user->getPass()}', {$user->getType()}, 1)";

        //TRY TO UPDATE
        $result = $dbConn->query($query);

        if ($result):
            databaseDisconnect($dbConn);
            return true;
        else:
            databaseDisconnect($dbConn);
            return false;
        endif;
    }

    public static function updateUser($user) {
        //TREAT ACTIVE
        if($user->getActive()):
            $user->setActive(1);
        else:
            $user->setActive(0);
        endif;
        $dbConn = databaseConnect('localhost', 'client_side', 'jffhnuPTGBQxyHMG', 'cpanel_users');
        $query = "UPDATE user SET user_email = '{$user->getEmail()}', user_pass = '{$user->getPass()}', user_type = {$user->getType()} , user_active = {$user->getActive()}  WHERE user_id = {$user->getId()} ";

        //TRY TO UPDATE
        $result = $dbConn->query($query);

        if ($result):
            databaseDisconnect($dbConn);
            return true;
        else:
            databaseDisconnect($dbConn);
            return false;
        endif;
    }

    public static function updateUserPassById($id, $pass) {
        $dbConn = databaseConnect('localhost', 'client_side', 'jffhnuPTGBQxyHMG', 'cpanel_users');
        $query = "UPDATE user SET user_pass = '{$pass}' WHERE user_id = {$id}";

        //TRY TO UPDATE
        $result = $dbConn->query($query);

        if ($result):
            databaseDisconnect($dbConn);
            return true;
        else:
            databaseDisconnect($dbConn);
            return false;
        endif;
    }

    public static function updateUserActiveById($id, $active) {
        //TREAT VAR ACTIVE
        if ($active):
            $active = 1;
        else:
            $active = 0;
        endif;
        $dbConn = databaseConnect('localhost', 'client_side', 'jffhnuPTGBQxyHMG', 'cpanel_users');
        $query = "UPDATE user SET user_active = {$active} WHERE user_id = {$id}";

        //TRY TO UPDATE
        $result = $dbConn->query($query);

        if ($result):
            databaseDisconnect($dbConn);
            return true;
        else:
            databaseDisconnect($dbConn);
            return false;
        endif;
    }

    public static function deleteUserById($idUser) {
        
        $dbConn = databaseConnect('localhost', 'client_side', 'jffhnuPTGBQxyHMG', 'cpanel_users');
        $query = "DELETE FROM user WHERE user_id = {$idUser};";

        //TRY TO UPDATE
        $result = $dbConn->query($query);

        if ($result):
            databaseDisconnect($dbConn);
            return true;
        else:
            databaseDisconnect($dbConn);
            return false;
        endif;
    }

}

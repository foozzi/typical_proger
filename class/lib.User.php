<?php

class Auth {

     private static 

        $ip = null,


        $user_agent = null,


        $user = null,


        $length = 6,


        $code = "",


        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";


    /**
     * Attempts to log a user in with the given credentials
     */

    public static function attempt($username, $password, $ip, $user_agent)
    {

        self::$ip = $ip;

        self::$user_agent = $user_agent;

        $password = md5(md5(trim($password)));
        
        $Get_User = db::normalizeQuery("SELECT * 
            FROM users 
            WHERE name_user = '%s' 
            AND passwd_user = '%s'", 
                $username, 
                $password
            );

        if(!$Get_User)
        {
            echo display_msg('Ошибка', 'alert-error', 'Не верные данные');

            return false;    
        }
        
        if ($user = $Get_User)
        {
            self::$user = $user;

            self::Login();

            return true;
        }

        echo display_msg('Ошибка', 'alert-error', 'Какие-то неполадки');

        return false;

    }

    /**
     * Get real ip users (anti-proxy)
     */

    public static function real_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $ip = ip2long($ip);

        return $ip;
    }

    /**
     * Get user agent
     */

    public static function get_user_agent()
    {
        $u_agen = $_SERVER['HTTP_USER_AGENT'];

        return $u_agen;
    }


    /**
     * login function
     */

    protected static function Login()
    {

        db::set("UPDATE users 
            SET hash_user = '%s', 
                ip_user = '%s', 
                user_agent = '%s' 
            WHERE id_user = '%s'", 
                self::Ger_Hash(), 
                self::$ip, 
                self::$user_agent, 
                self::$user['id_user']
            );

        setcookie("hash", self::Ger_Hash(), time()+60*60*24*30);

        setcookie("ip", self::$ip, time()+60*60*24*30);

        $_SESSION['user_id'] = self::$user['id_user'];

        setcookie("guest", "");

        echo display_msg_redir('Сообщение', 'alert-success', 'Вы авторизованы! Через 2 сек. вы будете перенаправлены', '/main', '2000');

    }


    /**
     * Generation hash!
     */

    protected static function Ger_Hash()
    {

        $clen = strlen(self::$chars) - 1; 

        while (strlen(self::$code) < self::$length) 
        {

            self::$code .= self::$chars[mt_rand(0,$clen)];  
        }

        return self::$code;
    }



    /**
     * Log out the current user
     */

    public static function logout()
    {
        self::$user = null;

        self::destroy_session_cookie();

        header("Location: /main");
        
        exit;
    }

    /**
     * method for destroy session and cookie
     */

    protected static function destroy_session_cookie()
    {
        $_SESSION = array();

        @session_unset($_SESSION['user_id'], $_SESSION['raw']);

        session_destroy();

        setcookie("hash", "", time() - 3600*24*30*12, "/");

        setcookie("ip", "", time() - 3600*24*30*12, "/");       

    }

    /**
     * Checks if the current user is a guest
     */

    protected static function guest()
    {
        setcookie("guest", self::Ger_Hash());
    }

    /**
     * check access method
     */

    public static function Check_Access()
    {
        if(!isset($_SESSION['user_id']) && !isset($_SESSION['raw']))
        {
            show404('Ошибка доступа', 'Авторизуйтесь');
        }
    }

    /**
     * check current session user
     */

    public static function check_session()
    {
        if(!isset($_SESSION['user_id']) && !isset($_SESSION['raw'])) 
        {
            if(!isset($_COOKIE['ip']) && !isset($_COOKIE['hash']))
            {

                self::guest();
            }

        }

        else
        {
            
            $Check_cookie = db::normalizeQuery("SELECT * 
                FROM users 
                WHERE hash_user = '%s' 
                AND ip_user = '%s'", 
                    $_COOKIE['hash'], 
                    $_COOKIE['ip']
                );

            if($Check_cookie && $Check_cookie['user_agent'] == $_SERVER['HTTP_USER_AGENT'] && $Check_cookie['ip_user'] == $_COOKIE['ip'])

            {

                $_SESSION['user_id'] = $Check_cookie['id_user'];
                
                $_SESSION['raw'] = $Check_cookie['name_user'];

            }

        }
    }
}
?>
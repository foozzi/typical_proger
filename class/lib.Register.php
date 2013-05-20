<?php

Class Register
{

	private static 

		$err = array(),


		$display_error = array(),


		$login_user = null,


		$password_user = null,


		$mail_user = null;


	/**
	 * create constructor
	 */

	public static function Check($login_user, $password_user, $mail_user)
	{

		self::$login_user = $login_user;
		self::$password_user = $password_user;
		self::$mail_user = $mail_user;

		/**
		 * check login
		 */

		if(!preg_match("/^[a-zA-Z0-9]+$/", self::$login_user))
		{
        	self::$err[] = display_msg('Сообщение', 'alert', 'Логин может состоять только из букв английского алфавита и цифр');
		}

		/**
		 * check strlen login
		 */

		if(strlen(self::$login_user) < 3 or strlen(self::$login_user) > 30)
		{
			self::$err[] = display_msg('Сообщение', 'alert', 'Логин должен быть не меньше 3-х символов и не больше 30');
		}

		/**
		 * check email
		 */

		if(!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", self::$mail_user))
        {
        	self::$err[] = display_msg('Сообщение', 'alert-error', 'Введите верный E-Mail');
        }

		/**
		 * check user in table
		 */

		$Check_User = db::query("SELECT name_user 
    		FROM users 
    		WHERE name_user = '%s'
    		",
    		self::$login_user
    	);

    	/**
    	 * if user found...
    	 */

		if($Check_User)
		{
			self::$err[] = display_msg('Сообщение', 'alert', 'Пользователь уже существует в базе');

		}

		/**
		 * check password 
		 */

		self::$password_user = self::Check_Passwd();

		/**
		 * echo msg or error
		 */

		self::Error_Count1();

		/**
		 * query insert user to table
		 */

		$Query = 
		db::set("INSERT INTO users (
    		id_user, 
    		name_user, 
    		email_user, 
    		passwd_user) 
    	VALUES (
    		'%s', 
    		'%s', 
    		'%s', 
    		'%s')", 
    	NULL, self::$login_user, self::$mail_user, self::$password_user);

    	/**
    	 * if insert fail
    	 */

		if(!$Query)
		{
			self::$err[] = display_msg('Сообщение', 'alert-error', 'Возникли ошибки при создании юзера');
		}
		else
		{
			self::$err[] = display_msg('Сообщение', 'alert-success', 'Вы зарегистрированы');
		}

		/**
		 * echo msg or error
		 */

		self::Error_Count1();

		/**
		 * exit from script
		 */

	}

	/**
	 * function for check password and crypt to md5
	 */

	private static function Check_Passwd()
	{
		$passwd = md5(md5(trim(self::$password_user)));
		return $passwd;
	}

	/**
	 * function count and return errors and msg
	 */

	private static function Error_Count1()
    {
        if(count(self::$err) != 0)
        {
            foreach(self::$err as self::$display_error)
            {
                echo self::$display_error;
                exit;
            }
        }
    }

}

?>
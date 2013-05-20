<?php

class Profile
{



	private static

		$p_login = null, 

		$p_email = null;


	public static function edit_data($p_login, $p_email)
	{

		self::$p_login = xss(trim($p_login));
		self::$p_email = xss(trim($p_email));

		if(empty(self::$p_login) or empty(self::$p_email))
		{
			echo display_msg('Ошибка', 'alert-error', 'Поля не могут быть пустыми');
			exit;
		}

		$Check_edit = db::normalizeQuery("SELECT id_user 
			FROM users 
			WHERE name_user = '%s' AND
				email_user = '%s'",
					self::$p_login,
					self::$p_email
			);

		if($Check_edit)
		{
			echo display_msg('Сообщение', 'alert', 'Данные не изменены');
			exit;
		}
		else
		{
			db::set("UPDATE users 
				SET name_user = '%s',
					email_user = '%s'",
						self::$p_login,
						self::$p_email
				);
		}

		echo display_msg_redir('Сообщение', 'alert-success', 'Ваш профиль обновлен! Через 2 сек. вы будете перенаправлены', '/profile', '2000');
		exit;
	}

	/**
     * get user info 
     */

    public static function info_user()
    {
        $User_Data = db::normalizeQuery("SELECT * 
            FROM users 
            WHERE id_user = '%s'", 
                $_SESSION['user_id']
            );

        return $User_Data;
    }	

}

?>




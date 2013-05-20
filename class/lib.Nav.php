<?php
// Пока всего 2 условия... класс в разработке
class Page
{
	
	public function gen_page_next($get_page, $p) 
	{
		if( $get_page != $p )
		{
			$get_page = $get_page + 1;
			$next = "<a href='main?p=" . $get_page . "'>Туда</a>";
		}
		else
		{
			$next = null;
		}
		return $next;
	}
	
	public function get_page_prev($get_page)
	{
		if( $get_page == 2 ) 
		{
			$get_page = $get_page - 1;
			$prev = "<a href='main'>Обратно</a>";
		}
		elseif ( $get_page != 1 ) {
			$get_page = $get_page - 1;
			$prev = "<a href='main?p='" . $get_page . "'>Обратно</a>";
		}
		else 
		{
			$prev = null;
		}
		return $prev;
	}
} 


?>

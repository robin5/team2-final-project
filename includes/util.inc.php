<?php

class Util
{
	public static function pre_dump($variable) {
		echo "<pre>";
		var_dump($variable);
		echo "</pre>";
	}

	public static function log( $data )
	{
		if ( is_string( $data ) )
		{
			if ( defined( '__SCRIPT__' ) )
				$data = __SCRIPT__ . " {$data}";

			error_log( $data );
		}
		elseif ( is_object( $data ) && $data instanceof Exception )
			error_log( $data->__toString() );
		else
			error_log( var_export( $data, true ) );
	}
}
<?php
/**
 * WP_Head_Footer_Utils
 *
 * @package     WP_Head_Footer\Includes
 * @author      Francesco Taurino
 * @copyright   Copyright (c) 2017, Francesco Taurino
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 */
//namespace WP_Head_Footer\WP_Head_Footer_Utils;
class WP_Head_Footer_Utils implements iWP_Head_Footer
{

	/**
	 * Verifica se l'utente autenticato ha la capacità richiesta
	 * 
	 * @return bool True se l'utente ha la capacità richiesta, altrimenti false.
	 * @todo per il network
	 */
	public static function is_user_authorized()
	{
		return current_user_can(self::REQUIRED_CAPABILITY);
	}
}

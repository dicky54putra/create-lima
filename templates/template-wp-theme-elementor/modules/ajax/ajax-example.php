<?php
/**
 * The template for Create Custom Ajax used in WP-admin
 *
 * Author: Andi, Muhammad Rizki, Angit Joko Tarup
 *
 * Note :
 *
 * @package HelloElementor
 */
defined( 'ABSPATH' ) || die( "Can't access directly" );

class AjaxExample1 extends SanitizeAndValidate{

	private $_data = array(
		'nonce' => false,
		'data'  => null,
	);

	public function __construct()
	{
		add_action('wp_ajax_ExampleAction1', [$this, 'ajax']);
		add_action('wp_ajax_nopriv_ExampleAction1', [$this, 'ajax']);
	}

	public function ajax()
	{

		$this->_data = $this->main($this->_data,$_POST,'NonceExampleAction1');
		$this->_response();
	}

	private function _response()
	{
		wp_send_json_success($this->_data);
	}
}
new AjaxExample1();



<?php

defined( 'ABSPATH' ) || die( "Can't access directly" );

class SanitizeAndValidate
{

	public function main($data, $post, $nonce)
	{
		$result = $this->_sanitize($data, $post);
		$this->_validate($result, $nonce);
		return $result;
	}

	private function _sanitize($data, $post)
	{

		if (!isset($post) || !isset($post['nonce'])) {
			wp_die();
		}

		foreach ($data as $key => $value) {
			if (isset($post[$key])) {
				$data[$key] = is_array($post[$key]) ? $post[$key] : sanitize_text_field($post[$key]);
			}
		}
		return $data;
	}

	private function _validate($data, $nonce)
	{
		$token_is_corrent = wp_verify_nonce($data['nonce'], $nonce); // Ajax Nonce Name
		if (!$token_is_corrent) {
			wp_send_json_error(__('Non not Verify...', THEME_DOMAIN));
		}
	}
}
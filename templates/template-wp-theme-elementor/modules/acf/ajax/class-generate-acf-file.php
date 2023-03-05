<?php
namespace BD\Ajax;

defined('ABSPATH') or exit;

class GenerateAcfFile
{
	/**
	 * Current module directory
	 *
	 * @var string
	 */
	private $dir;

	/**
	 * Current module url
	 *
	 * @var string
	 */
	private $url;

	// for response
	private $_data = array(
		'nonce'        => false,
		'field_groups' => array(),
	);


	public function __construct()
	{
		$this->dir = INCLUDES_DIR . '/acf';
		$this->url = INCLUDES_URL . '/acf';

		add_action('wp_ajax_generate_acf_file', [$this, 'ajax']);
	}

	public function ajax()
	{
		$this->_sanitize();
		$this->_validate();
		$this->_response();
	}

	private function _sanitize()
	{
		if (!isset($_POST) || !isset($_POST['nonce'])) {
			wp_die();
		}

		foreach ($this->_data as $key => $value) {
			if ( isset($_POST[$key]) ) {
				$this->_data[$key] = is_array($_POST[$key]) ? $_POST[$key] : sanitize_text_field($_POST[$key]);
			}
		}
	}

	private function _validate()
	{
		$token_is_corrent = wp_verify_nonce($this->_data['nonce'], 'GenerateAcfFile');

		if (!$token_is_corrent) {
			wp_send_json_error('Wrong Token');
		}
	}

	private function _response()
	{
		global $wpdb;

		$fields = (array) $this->_data['field_groups'];
		$field_groups = array();

		$str_replace = array(
			"  "			=> "\t",
			"'!!__(!!\'"	=> "__('",
			"!!\', !!\'"	=> "', '",
			"!!\')!!'"		=> "')",
			"array ("		=> "array(",
			"'__(\'"		=> "__('",
			"\', \'". BD_ACF_DOMAIN ."\')'"		=> "', '". BD_ACF_DOMAIN ."')",
		);
		$preg_replace = array(
			'/([\t\r\n]+?)array/'	=> 'array',
			'/[0-9]+ => array/'		=> 'array'
		);

		if ( empty($fields) ) {
			$html = $this->_generate_notice( __("No field groups selected", 'acf'), 'warning');
			wp_send_json_success( $html );
		}

		// disable filters to ensure ACF loads raw data from DB
		acf_disable_filters();

		foreach ($fields as $key) {
			// load field group
			$field_group = acf_get_field_group( $key );

			// validate field group
			if( empty($field_group) ) continue;

			// load fields
			$field_group['fields'] = acf_get_fields( $field_group );

			// prepare for export
			$field_group = acf_prepare_field_group_for_export( $field_group );

			// add to json array
			$field_groups[] = $field_group;
		}

		if ($field_groups) {
			$path = $this->dir . '/fields';
			$file_name = 'bd-acf-fields.php';
			$json_file_name = 'bd-acf-fields.json';

			if ( !file_exists( $path ) ) {
				wp_mkdir_p( $path );
			}

			ob_start();
			echo "<?php" . "\r\n" . "defined('ABSPATH') or die();" . "\r\n" . "\r\n";;

			echo "if( function_exists('acf_add_local_field_group') ):" . "\r\n" . "\r\n";
				foreach ($field_groups as $field_group) {
					// code
					$code = var_export($field_group, true);

					// change double spaces to tabs
					$code = str_replace( array_keys($str_replace), array_values($str_replace), $code );

					// correctly formats "=> array("
					$code = preg_replace( array_keys($preg_replace), array_values($preg_replace), $code );

					// echo
					echo "acf_add_local_field_group({$code});" . "\r\n" . "\r\n";
				}
			echo "endif;";
			$export_code = ob_get_clean();

			@file_put_contents($path . '/' . $file_name, $export_code);
			@file_put_contents($path . '/' . $json_file_name, acf_json_encode( $field_groups ));
		}

		$count = count($field_groups);
		$text = sprintf( _n( 'Exported 1 field group.', 'Exported %s field groups.', $count, 'acf' ), $count );
		$msg = $this->_generate_notice( $text, 'success');
		wp_send_json_success($msg);
	}

	private function _generate_notice($msg, $type) {
		return '<div class="acf-admin-notice notice notice-'. $type .' is-dismissible">
				<p>'. $msg .'</p>
				<button type="button" class="notice-dismiss"></button>
		</div>';
	}
}

new GenerateAcfFile();

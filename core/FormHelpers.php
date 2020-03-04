<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 2/27/20
 */

class FormHelpers
{
	/**
	 * @param string $type
	 * @param string $label
	 * @param string $name
	 * @param string $value
	 * @param array $inputAttributes
	 * @param array $divAttributes
	 * @return string
	 */
	public static function inputBlock($type, $label, $name, $value = '', $inputAttributes = [], $divAttributes = [])
	{
		$divString   = self::stringifyAttributes($divAttributes);
		$inputString = self::stringifyAttributes($inputAttributes);

		$html = '<div ' . $divString . '>';
		$html .= "\t" . '<label for=' . $name . ' >' . $label . '</label>';
		$html .= "\t" . '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" value="' . $value . '" ' . $inputString . ' />';
		$html .= '</div>';
		return $html;
	}

	/**
	 * @param string $buttonText
	 * @param array $inputAttributes
	 * @return string
	 */
	public static function submitTag($buttonText, $inputAttributes = [])
	{

		$inputString = self::stringifyAttributes($inputAttributes);
		$html        = '<input type="submit" value="' . $buttonText . '" ' . $inputString . '" />';
		return $html;

	}

	/**
	 * @param string $buttonText
	 * @param array $inputAttributes
	 * @param array $divAttributes
	 * @return string
	 */
	public static function submitBlock($buttonText, $inputAttributes = [], $divAttributes = [])
	{
		$divString = self::stringifyAttributes($divAttributes);

		$html = '<div ' . $divString . '>';
		$html .= self::submitTag($buttonText, $inputAttributes);
		$html .= '</div>';

		return $html;

	}

	/**
	 * @param array $attributes
	 * @return string
	 */
	public static function stringifyAttributes(array $attributes)
	{
		$string = '';
		if (!empty($attributes)) {
			foreach ($attributes as $name => $value) {
				$string .= ' ' . $name . '="' . $value . '"';
			}
		}
		return $string;
	}

	public static function generateToken(){
		$token = base64_encode(openssl_random_pseudo_bytes(32));
		Session::set('csrf_token', $token);
		return $token;
	}

	public static function checkToken($token){
		return (Session::exists('csrf_token') && Session::get('csrf_token') === $token );
	}

	public static function csrfInput() {
		return '<input type="hidden" name="csrf_token" 
		id="csrf_token" value="' . self::generateToken() . '">';
	}

	/**
	 * @param string $dirtyValue
	 *
	 * @return string
	 */
	public static function sanitize($dirtyValue) {
		return htmlentities($dirtyValue, ENT_QUOTES, 'UTF-8');
	}

	public static function postedValues($post) {
		if(!is_array($post) || empty($post)) {
			return [];
		}
		$cleanArray = [];
		foreach ($post as $key => $value) {
			$cleanArray[$key] = self::sanitize($value);
		}
		return $cleanArray;

	}

	public static function displayErrors($errors) {
		if(empty($errors)){
			return '';
		}

		$html = '<div class="form-errors"><ul class="alert-danger">';
		foreach ($errors as $field => $error) {
			$html .= '<li class="text-danger">' . $error . '</li>';
			$html .= '
					<script>
					jQuery("document").ready(function() {
						jQuery("#' . $field . '")
							.parent()
							.closest("div")
							.addClass("has-error");
					})
					</script>';
		}

		$html .= '</ul></div>';
		return $html;

	}
}
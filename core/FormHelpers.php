<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 2/27/20
 */

namespace Core;

use Core\Session;

class FormHelpers
{
	/**
	 * Returns generated input block wrapped in div with given attributes.
	 * @param string $type - input type text, textarea, radio, .etc
	 * @param string $label - label text
	 * @param string $name - input name
	 * @param string $value - input value
	 * @param array $inputAttributes - additional html attributes for <input>
	 * @param array $divAttributes - additional html attributes for wrapping <div>
	 * @return string $html - generated html code <div><label></label><input/></div>
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
	 * Returns generated input block with type "checkbox" wrapped in div with given attributes.
	 * @param string $label - label text
	 * @param string $name - input name
	 * @param bool $checked - is checkbox checked by default
	 * @param array $inputAttributes - additional html attributes for <input>
	 * @param array $divAttributes - additional html attributes for wrapping <div>
	 * @return string $html - generated html code <div><label><input type = "checkbox"/></label></div>
	 */
	public static function checkboxBlock($label, $name, $checked = false, $inputAttrs = [], $divAttrs = [])
	{
		$divString   = self::stringifyAttributes($divAttrs);
		$inputString = self::stringifyAttributes($inputAttrs);
		$checkString = ($checked) ? ' checked="checked"' : '';

		$html = '<div' . $divString . '>';
		$html .= '<label for="' . $name . '">' . $label . ' <input type="checkbox" id="' . $name . '" name="' . $name
			. '" value="on"' . $checkString . $inputString . '></label>';
		$html .= '</div>';
		return $html;
	}

	/**
	 * Returns generated html input with type "submit".
	 * @param string $buttonText - button text
	 * @param array $inputAttributes - additional html attributes for <input>
	 * @return string $html - generated <input> html
	 */
	public static function submitTag($buttonText, $inputAttributes = [])
	{
		$inputString = self::stringifyAttributes($inputAttributes);
		$html        = '<input type="submit" value="' . $buttonText . '" ' . $inputString . '" />';
		return $html;
	}

	/**
	 * Returns generated <input type="submit"> block wrapped in div with given attributes.
	 * @param string $buttonText - button text
	 * @param array $inputAttributes - additional html attributes for <input>
	 * @param array $divAttributes - additional html attributes for wrapping <div>
	 * @return string $html - generated html <div><input type="submit"/>/div>
	 */
	public static function submitBlock($buttonText, $inputAttributes = [], $divAttributes = [])
	{
		$divString = self::stringifyAttributes($divAttributes);

		$html = '<div ' . $divString . '>';
		$html .= "\t" . self::submitTag($buttonText, $inputAttributes);
		$html .= '</div>';

		return $html;
	}

	/**
	 * Stringifies attribute from given array.
	 * @param array $attributes - additional attribute array [['attr1'=>'value']..['attr_n'=>'value']]
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

	/**
	 * Generates Cross Site Request Forgery token.
	 * Sets generated token to Session.
	 * @return string $token - CSRF token
	 */
	public static function generateToken()
	{
		$token = base64_encode(openssl_random_pseudo_bytes(32));
		Session::set('csrf_token', $token);
		return $token;
	}

	/**
	 * Checks if given token matches to token stores in Session.
	 * @param string $token - given token to check
	 * @return bool - whether token matches or not
	 */
	public static function checkToken($token)
	{
		return (Session::exists('csrf_token') && Session::get('csrf_token') === $token);
	}

	/**
	 * Generates CSRF token input for forms.
	 * If token was given as parameter then use it instead of generating new one. Needed to let user option refresh the
	 * page after POST request.
	 * @param string $token - token to use.
	 * @return string - hidden input with generated CSRF token value.
	 */
	public static function csrfInput($token = '')
	{
		if(empty($token)){
			$token = self::generateToken();
		}

		return '<input type="hidden" name="csrf_token" id="csrf_token" value="' . $token . '">';
	}

	/**
	 * Sanitizes values replacing html entities.
	 * @param string $dirtyValue
	 *
	 * @return string
	 */
	public static function sanitize($dirtyValue)
	{
		return htmlentities($dirtyValue, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Sanitizes each element in given array using sanitize() method.
	 * @uses FormHelpers::sanitize()
	 * @param $post
	 * @return array
	 */
	public static function postedValues($post)
	{
		if (!is_array($post) || empty($post)) {
			return [];
		}
		$cleanArray = [];
		foreach ($post as $key => $value) {
			$cleanArray[$key] = self::sanitize($value);
		}
		return $cleanArray;
	}

	/**
	 * Generates errors block with JQuery function.
	 * @param array $errors - array of errors
	 * @return string - generated html block
	 */
	public static function displayErrors($errors)
	{
		if (empty($errors)) {
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
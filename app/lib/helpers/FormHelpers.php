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

}
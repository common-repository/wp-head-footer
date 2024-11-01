<?php
/**
 * WP_Head_Footer_Sanitize
 *
 * @package     WP_Head_Footer\Includes
 * @author      Francesco Taurino
 * @copyright   Copyright (c) 2017, Francesco Taurino
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * # Nota, in caso di errori con la wp-sitemap.xml verifica eventuali spazi vuoti
 */
class WP_Head_Footer_Sanitize implements iWP_Head_Footer
{

    /**
     * Sanitize Priority
     *
     * @param  int|null    $number   The number to sanitize. Default is 10.
     * @param  string|null $context  The context. Default is null.
     * @return int                  The sanitized priority value.
     */
    public static function sanitize_priority($number = 10, ?string $context = null): int
    {
        return filter_var($number, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => PHP_INT_MIN,
                'max_range' => PHP_INT_MAX,
            ],
        ]) ?: 10;
    }

    /**
     * Sanitize Replace
     *
     * @param  mixed       $value    The value to sanitize.
     * @param  string|null $context  The context. Default is null.
     * @return int                  The sanitized replace value (0 or 1).
     */
    public static function sanitize_replace($value = 0, ?string $context = null): int
    {
        return (int) filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    }

    /**
     * Sanitize Content
     *
     * This function does not sanitize or validate the user input data,
     * but simply returns the input value as is.
     *
     * @param  mixed       $value    The value to sanitize.
     * @param  string|null $context  The context. Default is null.
     * @return string                The sanitized content.
     */
    public static function sanitize_content($value = '', ?string $context = null): string
    {
        return is_string($value) ? $value : '';
    }

    /**
     * Sanitize Option
     *
     * @param  string|null $key         The option key.
     * @param  mixed|null  $raw_value   The raw value to sanitize.
     * @param  string|null $context     The context. Default is null.
     * @return mixed                    The sanitized value.
	 * @note  php8 l'operatore di coalescenza ?? isset()
     */
    public static function sanitize_option(?string $key = null, mixed $raw_value = null, ?string $context = null)
    {
        $default_options = WP_Head_Footer_Options::get_default_options();
        $callback = $default_options[$key]['sanitize_callback'] ?? null;
        $default_value = $default_options[$key]['input_value'] ?? null;

        if ($callback && method_exists(self::class, $callback)) {
            return self::$callback($raw_value, $context);
        }

        return $default_value;
    }

    /**
     * Sanitize Options
     *
     * @param  array $data  The data to sanitize.
     * @return array        The sanitized options.
     */
    public static function sanitize_options(array $data): array
    {
        $sanitized = [];

        foreach (WP_Head_Footer_Options::get_default_options() as $key => $args) {
            if (isset($data[$key])) {
                $sanitized[$key] = self::sanitize_option($key, $data[$key], 'db');
            }
        }

        return $sanitized;
    }
}
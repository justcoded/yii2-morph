<?php

/**
 * Loads env variables from .env file
 *
 * @param string $path
 *
 * @return bool
 */
function load_dotenv($path)
{
    if (!is_file($path)) {
        return false;
    }

    $content = trim(file_get_contents($path));
    $lines = \yii\helpers\StringHelper::explode($content, "\n");

    foreach ($lines as $line) {
        list($name, $value) = \yii\helpers\StringHelper::explode($line, "=");
        if ($value && strlen($value) >= 2 && preg_match('/((^\".*\"$)|(^\'.*\'$))/', $value)) {
            $value = substr($value, 1, strlen($value) - 1);
        }

        register_env_var($name, $value);
    }
}

/**
 * Safe read of env variable with fatal in case of variable is missing
 *
 * @param string $name
 * @param mixed|null $default
 *
 * @return null
 * @throws Exception
 */
function env($name, $default = null)
{
    if (false === getenv($name) && is_null($default)) {
        throw new Exception('ENV variable missing: ' . $name);
    }

    return getenv($name) ?: $default;
}

/**
 * Safe register of env variable
 *
 * @param string $name
 * @param mixed $value
 */
function register_env_var($name, $value)
{
    // If PHP is running as an Apache module and an existing.
    // Apache environment variable exists, overwrite it.
    if (function_exists('apache_getenv') && function_exists('apache_setenv') && apache_getenv($name)) {
        apache_setenv($name, $value);
    }
    if (function_exists('putenv')) {
        putenv("$name=$value");
    }
    $_ENV[$name] = $value;
    $_SERVER[$name] = $value;
}

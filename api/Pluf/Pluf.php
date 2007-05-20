<?php
/**
 * The main class of the framework. From where all start.
 *
 * The __autoload function is automatically set.
 */
class Pluf
{
    /**
     * Start the Plume Framework.
     *
     * Load the configuration files. If not configuration file is given
     * load the dirname(__FILE__).'/conf/pluf.config.php' config file.
     *
     * Set the timezone for PHP >= 5.1
     *
     * @param string Configuration file to use.
     */
    static function start($config='')
    {
        $GLOBALS['_PX_starttime'] = microtime(true);
        if ($config != '') {
            Pluf::loadConfig($config);
        } else {
            // load the default configuration file
            Pluf::loadConfig(dirname(__FILE__).'/conf/pluf.config.php');
        }
        if (version_compare(phpversion(), '5.1.0', '>=')) {
            date_default_timezone_set(Pluf::f('time_zone', 'Europe/Berlin'));
        }
    }

    /**
     * Load the given configuration file.
     *
     * The configuration is saved in the $GLOBALS['_PX_config'] array.
     * The relations between the models are loaded in $GLOBALS['_PX_models'].
     *
     * @param string Configuration file to load.
     */
    static function loadConfig($config_file)
    {
        if (false !== ($file=Pluf::fileExists($config_file))) {
            $GLOBALS['_PX_config'] = require $file;
        } else {
            throw new Exception('Configuration file does not exist: '.$config_file);
        }
    }

    /**
     * Access a configuration variable.
     *
     * @param string Configuration variable
     * @param mixed Possible default value if value is not set ('')
     * @return mixed Configuration variable or default value if not defined.
     */
    static function f($cfg, $default='')
    {
        if (isset($GLOBALS['_PX_config'][$cfg])) {
            return $GLOBALS['_PX_config'][$cfg];
        }
        return $default;
    }


    /**
     * Returns a given object. 
     *
     * Loads automatically the corresponding class file if needed.
     * If impossible to get the class $model, exception is thrown.
     *
     * @param string Model to load.
     * @param mixed Extra parameters for the constructor of the model.
     */
    public static function factory($model, $params=null)
    {
        if ($params !== null) {
            return new $model($params);
        }
        return new $model();
    }

    /**
     * Load a class depending on its name.
     *
     * Throw an exception if not possible to load the class.
     *
     * @param string Class to load.
     */
    public static function loadClass($class)
    {
        if (class_exists($class, false)) {
            return;
        }
        $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        $file = 'api/'.$file;
        if (false !== ($file=Pluf::fileExists($file))) {
            include $file;
        }
        if (!class_exists($class, false)) {
            throw new Exception('Impossible to load the class: '.$class);
        }
    }

    /**
     * Load a function depending on its name.
     *
     * The implementation file of the function
     * MyApp_Youpla_Boum_Stuff() is MyApp/Youpla/Boum.php That way it
     * is possible to group all the related function in one file.
     *
     * Throw an exception if not possible to load the function.
     *
     * @param string Function to load.
     */
    public static function loadFunction($function)
    {
        if (function_exists($function)) {
            return;
        }
        $elts = explode('_', $function);
        array_pop($elts);
        $file = implode(DIRECTORY_SEPARATOR, $elts) . '.php';
        if (false !== ($file=Pluf::fileExists($file))) {
            include $file;
        }
        if (!function_exists($function)) {
            throw new Exception('Impossible to load the function: '.$function);
        }
    }


    /**
     * Hack for [[php file_exists()]] that checks the include_path.
     * 
     * Use this to see if a file exists anywhere in the include_path.
     * 
     * <code type="php">
     * $file = 'path/to/file.php';
     * if (Pluf::fileExists('path/to/file.php')) {
     *     include $file;
     * }
     * </code>
     *
     * @credits Paul M. Jones <pmjones@solarphp.net>
     *  
     * @param string $file Check for this file in the include_path.
     * 
     * @return mixed Full path to the file if the file exists and 
     *         is readable in the include_path, false if not.
     */
    public static function fileExists($file)
    {
        $file = trim($file);
        if (!$file) {
            return false;
        }
        // using an absolute path for the file?
        // dual check for Unix '/' and Windows '\',
        // or Windows drive letter and a ':'.
        $abs = ($file[0] == '/' || $file[0] == '\\' || $file[1] == ':');
        if ($abs && file_exists($file)) {
            return $file;
        }
        // using a relative path on the file
        $path = explode(PATH_SEPARATOR, ini_get('include_path'));
        foreach ($path as $dir) {
            // strip Unix '/' and Windows '\'
            $target = rtrim($dir, '\\/').DIRECTORY_SEPARATOR.$file;
            if (file_exists($target)) {
                return $target;
            }
        }
        // never found it
        return false;
    }
}
?>
<?php defined('ALT_PATH') OR die('No direct access allowed.');

class Alt {
    // environment
    const ENV_DEVELOPMENT           = 1;
    const ENV_TEST                  = 2;
    const ENV_PRODUCTION            = 3;
    public static $environment      = self::ENV_DEVELOPMENT;

    // output type
    const OUTPUT_HTML               = 'html';
    const OUTPUT_JSON               = 'json';
    const OUTPUT_XML                = 'xml';
    public static $outputs          = array(
        self::OUTPUT_JSON           => 'application/',
        self::OUTPUT_XML            => 'application/',
        self::OUTPUT_HTML           => 'text/',
    );
    public static $output           = self::OUTPUT_JSON;

    // response status
    const STATUS_OK                 = '200';
    const STATUS_UNAUTHORIZED       = '401';
    const STATUS_NOTFOUND           = '404';
    const STATUS_ERROR              = '500';
    public static $status           = array(
        self::STATUS_OK             => 'OK',
        self::STATUS_UNAUTHORIZED   => 'UNAUTHORIZED',
        self::STATUS_NOTFOUND       => 'NOTFOUND',
        self::STATUS_ERROR          => 'ERROR',
    );

    // profiler
    public static $timestart        = 0;
    public static $timestop         = 0;
    public static $config           = array();

    /**
     * Start Alt application
     * @param array $options
     */
    public static function start($options = array()){
        // set timestart
        self::$timestart = $_SERVER['REQUEST_TIME_FLOAT'];

        // set environment
        self::$environment = $options['environment'] ?: self::ENV_DEVELOPMENT;

        // set default output
        self::$output = $options['output'] ?: self::OUTPUT_JSON;

        // read config
        self::$config = $options['config'] ?: (include_once ALT_PATH . 'config.php');

        // can be used as a web app or command line
        switch(PHP_SAPI){
            case 'cli':
                $baseurl = '';
                $total = (int)$_SERVER['argc'];
                if($total > 1) for($i=1; $i<$total; $i++){
                    list($key, $value) = explode('=', trim($_SERVER['argv'][$i]));
                    if($key == '--uri'){
                        $_SERVER['REQUEST_URI'] = strtolower($value);
                    }else{
                        $_REQUEST[$key] = $value;
                    }
                }
                $_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?: "";
                break;
            default:
                list($baseurl) = explode('index.php', $_SERVER['PHP_SELF']);
                break;
        }

        $uri = substr($_SERVER['REQUEST_URI'], strlen($baseurl)) ?: "";
        list($route) = explode('?', $uri);
        list($routing, $ext) = explode(".", $route);
        $routing = $routing ?: 'index';
        $routing = str_replace('/', DIRECTORY_SEPARATOR, $routing);

        if(isset(self::$outputs[$ext])) self::$output = $ext;

        if(!$_REQUEST['issurpress']) header(' ', true, $_REQUEST['s']);
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');

        try{
            $controller = ALT_PATH . 'route' . DIRECTORY_SEPARATOR . $routing . '.php';
            if(!is_file($controller)) throw new Alt_Exception("Request not found", self::STATUS_NOTFOUND);

            ob_start();
            $res = (include_once $controller);

            header('Content-type: ' . self::$outputs[self::$output] . self::$output);
            switch(self::$output){
                case self::OUTPUT_HTML:
                    $res = ob_get_contents() ?: $res;
                    ob_end_clean();

                    self::response(array(
                        's' => self::STATUS_OK,
                        'd' => $res,
                    ));
                    break;
                default:
                    ob_end_clean();
                    self::response(array(
                        's' => self::STATUS_OK,
                        'd' => $res,
                    ));
                    break;
            }
        }catch(Alt_Exception $e){
            self::response(array(
                's' => $e->getCode(),
                'm' => $e->getMessage(),
            ));
        }catch(Exception $e){
            self::response(array(
                's' => self::STATUS_ERROR,
                'm' => $e->getCode() . " : " . $e->getMessage(),
            ));
        }
    }

    /**
     * Useful in stop the application and do the debugging while displaying time and memory usage
     * @param null $data
     * @param bool $isdie
     */
    public static function stop($data = null, $isdie = true){
        var_dump(array(
            'd' => $data,
            't' => round(microtime(true) - self::$timestart, 6),
            'u' => memory_get_peak_usage(true) / 1000,
        ));
        if($isdie) die;
    }

    public static function autoload($class){
        // Transform the class name according to PSR-0
        $class     = ltrim($class, '\\');
        $file      = ALT_PATH . 'engine' . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

        if (is_file($file)) {
            require $file;
            return TRUE;
        }
        return FALSE;
    }

    public static function response($output = array(), $options = array()){
        // flag is always surpress http status to 200
        $options['issurpress']  = isset($options['issurpress']) ? $options['issurpress'] : (isset($_REQUEST['issurpress']) ? $_REQUEST['issurpress'] : false);

        // flag is only return data, not with status
        $options['ismini']      = isset($options['ismini']) ? $options['ismini'] : (isset($_REQUEST['ismini']) ? $_REQUEST['ismini'] : self::$environment == self::ENV_PRODUCTION);

        // adding benchmark time and memory
        self::$timestop = microtime(true);
        if(self::$environment == self::ENV_DEVELOPMENT) $output['t'] = round(self::$timestop - self::$timestart, 6);
        if(self::$environment == self::ENV_DEVELOPMENT) $output['u'] = memory_get_peak_usage(true) / 1000;

        // switch by output type
        switch(self::$output){
            case self::OUTPUT_JSON:
            default:
                $output = $options['ismini'] && $output['s'] == self::STATUS_OK ? $output['d'] : $output;
                echo json_encode($output);
                break;
            case self::OUTPUT_XML:
                $output = $options['ismini'] && $output['s'] == self::STATUS_OK ? $output['d'] : $output;
                echo '<?xml version="1.0" encoding="UTF-8"?>';
                echo '<xml>';
                echo self::xml_encode($output);
                echo '</xml>';
                break;
            case self::OUTPUT_HTML:
                echo $output['d'];
                break;
        }
    }

    public static function xml_encode($data){
        $str = '';
        switch(gettype($data)){
            case 'string':
            case 'number':
            case 'double':
                $str .= $data;
                break;
            case 'array':
            case 'object':
                foreach($data as $key => $value){
                    $str .= '<' . $key . '>';
                    $str .= self::xml_encode($value);
                    $str .= '</' . $key . '>';
                }
                break;
        }
        return $str;
    }
}
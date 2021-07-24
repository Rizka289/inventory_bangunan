<?php
defined('BASEPATH') or exit('No direct script access allowed');


if (!method_exists($this, 'sessiondata')) {
    function sessiondata($index = 'login', $kolom = null)
    {
        // if (!is_login())
        //     return;
        /** @var CI_Controller $CI */
        $CI = &get_instance();

        $data = $CI->session->userdata($index);

        return empty($kolom) ? $data : $data[$kolom];
    }
}

if (!method_exists($this, 'response')) {
    function response($message = '', $code = 200, $type = 'success', $format = 'json')
    {
        http_response_code($code);
        $responsse = array();
        if ($code != 200)
            $type = 'Error';

        if (is_object($message)) {
            $responsse = (object) $responsse;

            if (!isset($message->type))
                $responsse->type = $type;
            else
                $responsse->type = $message->type;
        } elseif (is_array($message)) {
            $responsse = $message;
            if (!isset($message['type']))
                $responsse['type'] = $type;
            else
                $responsse['type'] = $message['type'];
        } else {
            $responsse['message'] = $message;
            $responsse['type'] = $type;
        }

        if ($format == 'json') {
            header('Content-Type: application/json');
            echo json_encode($responsse);
        } elseif ($format == 'html') {
            header('Content-Type: text/html');
            echo '<script> var path = "' . base_url() . '"</script>';
            echo $responsse['message'];
        }
        die;
    }
}

if (!method_exists($this, 'random')) {
    function random($length = 5, $type = 'string')
    {
        $characters = $type == 'string' ? '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' : '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $type == 'string' ? $randomString : boolval($randomString);
    }
}

if (!method_exists($this, 'waktu')) {
    function waktu($waktu = null, $format = MYSQL_TIMESTAMP_FORMAT)
    {
        $waktu = empty($waktu) ? time() : $waktu;
        return date($format, $waktu);
    }
}

if (!method_exists($this, 'httpmethod')) {
    function httpmethod($method = 'POST')
    {
        $method = strtoupper($method);
        return $_SERVER['REQUEST_METHOD'] == $method;
    }
}

if (!method_exists($this, 'utils_config_item')) {
    function utils_config_item($file = '', $params = null)
    {

        $index = '';
        if (!empty($params)) {
            if (is_array($params)) {
                foreach ($params as $value)
                    $index = $index . "['" . $value . "']";
            } else
                $index = "['$params']";
        }
        if (file_exists(CONFIG_PATH . $file . '.php')) {
            $file_path =  CONFIG_PATH . $file . '.php';
            require($file_path);
            if (!empty($params)) {
                $str = '$configItem = $config' . $index . ';';
                eval($str);
                return $configItem;
            } else
                return $config;
        } else
            response(['message' => 'file config tidak ditemukan'], 500);
    }
}

if (!method_exists($this, 'config_sidebar')) {
    function config_sidebar($sidebar = null, int $activeMenu = 0, $subMenu = 0)
    {

        if (is_null($sidebar))
            return utils_config_item('component');

        $config = utils_config_item('component', array('component', 'sidebar', 'dore', $sidebar));

        $config['menus'][$activeMenu]['active'] = true;
        $induk = $config['menus'][$activeMenu]['link'];
        if ($induk[0] != '#')
            return $config;
        else {
            $sub = null;
            $induk = str_replace('#', '', $induk);
            foreach ($config['subMenus'] as $key => $value) {
                if ($value['induk'] == $induk) {
                    $sub = $key;
                    continue;
                }
            }
            if (!is_null($sub))
                $config['subMenus'][$sub]['menus'][$subMenu]['active'] = true;

            return $config;
        }
    }
}


if (!method_exists($this, 'add_resource_group')) {
    function add_resource_group($name, $type = null, $pos = null)
    {

        $type = empty($type) ? 'semua' : $type;
        $pos = empty($pos) ? 'head' : $pos;


        $resourceText = '';
        $configitem = config_item('theme');
        $configitem = $configitem['themes'];

        if ($type == 'semua') {
            if (empty($configitem[$name]))
                return null;
            foreach ($configitem[$name] as $k => $v) {
                foreach ($v as $resource) {
                    $resource['src'] = $resource['src'];
                    if ($k == 'js') {
                        if (isset($resource['type']) && $resource['type'] == 'inline')
                            $resourceText .= $resource['pos'] == $pos ? "<script>" . $resource['src'] . "</script>" : null;
                        else
                            $resourceText .= $resource['pos'] == $pos ? "<script src='{$resource['src']}'></script>" : null;
                    } elseif ($k == 'css') {
                        if (isset($resource['type']) && $resource['type'] == 'inline')
                            $resourceText .= $resource['pos'] == $pos ? "<style>" . $resource['src'] . "</style>" : null;
                        else
                            $resourceText .= $resource['pos'] == $pos ? "<link rel='stylesheet' href='{$resource['src']}'></link>" : null;
                    }
                }
            }
        } else {
            if (empty($configitem[$name][$type]))
                return null;
            foreach ($configitem[$name][$type] as $k => $v) {
                $v['src'] = $v['src'];
                if ($type == 'js') {
                    if (isset($resource['type']) && $resource['type'] == 'inline')
                        $resourceText .= $v['pos'] == $pos ? "<script>" . $v['src'] . "</script>" : null;
                    else
                        $resourceText .= $v['pos'] == $pos ? "<script src='{$v['src']}'></script>" : null;
                } elseif ($type == 'css') {
                    if (isset($v['type']) && $v['type'] == 'inline')
                        $resourceText .= $v['pos'] == $pos ? "<style>" . $v['src'] . "</style>" : null;
                    else
                        $resourceText .= $v['pos'] == $pos ? "<link rel='stylesheet' href='{$v['src']}'></link>" : null;
                }
            }
        }
        return $resourceText;
    }
}

if (!method_exists($this, 'include_view')) {
    function include_view($path, $data = null)
    {
        if (is_array($data))
            extract($data);
        // var_dump(APP_PATH . 'views/' . $path . '.php');die;
        include VIEWS_PATH . $path . '.php';
    }
}

if (!method_exists($this, 'rupiah_format')) {
    function rupiah_format($angka)
    {
        $hasil_rupiah = "Rp. " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }
}

if (!method_exists($this, 'search_part')) {
    function search_part($arr, $search, $callback = null)
    {
        $data = [false, -1, ""];

        foreach ($arr as $key => $value) {
            if (is_array($search)) {
                if (in_array($value, $search))
                    $data = [true, $key, $value];
            } else {
                if ($value == $search)
                    $data = [true, $key, $value];
            }
        }

        if (!empty($callback) && is_callable($callback))
            return $callback($data, $arr, $search);
        else
            return $data;
    }
}
if (!method_exists($this, 'search_part_bool')) {
    function search_part_bool($arr, $search, $callback = null)
    {
        $data = -1;

        foreach ($arr as $key => $value) {
            if (is_array($search)) {
                if (in_array($value, $search))
                    $data = $key;
            } else {
                if ($value == $search)
                    $data = $key;
            }
        }

        if (!empty($callback) && is_callable($callback))
            return $callback($data, $arr, $search);
        else
            return $data != -1;
    }
}

if(!method_exists($this, 'get_path')){
    function get_path($type = 'assets', $suffix = null){
        $ci = get_instance();
        $isWindows = $isWindows = substr($ci->myOS(), 0, 7) == "Windows";

        $map = [
            'assets' => ASSETS_PATH,
            'config' => CONFIG_PATH, 
            'views' => VIEWS_PATH,
        ];


        if($isWindows && !empty($suffix))
            $suffix = str_replace($suffix, '/', '\\');

        return $map[$type] . $suffix;

    }
}
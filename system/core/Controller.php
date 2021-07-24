<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller
{

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * CI_Loader
	 *
	 * @var	CI_Loader
	 */
	public $load;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	private $views = [];
	private $params = [];
	public function __construct()
	{
		self::$instance = &$this;
		$myOs = $this->myOS();
		$isWindows = substr($myOs, 0, 7) == "Windows";
		if ($isWindows) {
			if (!defined("ASSETS_PATH"))
				define("ASSETS_PATH", str_replace("application\\", 'public\\assets\\', APPPATH));

			if (!defined("CONFIG_PATH"))
				define("CONFIG_PATH", APPPATH . 'config\\');

			if (!defined("VIEWS_PATH"))
				define("VIEWS_PATH", APPPATH . 'views\\');
		} else {
			if (!defined("ASSETS_PATH"))
				define("ASSETS_PATH", str_replace("application/", 'public/assets/', APPPATH));

			if (!defined("CONFIG_PATH"))
				define("CONFIG_PATH", APPPATH . 'config/');

			if (!defined("VIEWS_PATH"))
				define("VIEWS_PATH", APPPATH . 'views/');
		}
		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class) {
			$this->$var = &load_class($class);
		}

		$this->load = &load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}
	public function addViews($views, $params = null)
	{
		/**
		 * @var CI_Controller
		 */
		if (is_array($views)) {
			foreach ($views as $v)
				$this->views[] = $v;
		} else
			$this->views[] = $views;

		if (!is_array($params))
			$this->CI->setParams($params, 'params');
		else {
			foreach ($params as $k => $v) {
				$this->CI->setParams($v, $k);
			}
		}
	}
	function add_javascript($js)
	{
		if (isset($js['pos'])) {
			$this->CI->setParams($js, 'extra_js', true);
		} else {
			foreach ($js as $j) {
				$this->CI->setParams($j, 'extra_js', true);
			}
		}
	}

	function add_cachedJavascript($js, $type = 'file', $pos = "body:end", $data = array())
	{
		try {
			if ($type == 'file') {
				ob_start();
				if (!empty($data))
					extract($data);

				include_once ASSETS_PATH . 'js/' . $js . '.js';
			}
			$params = array(
				'script' => $type == 'file' ? ob_get_contents() : $js,
				'type' => 'inline',
				'pos' => 'body:end'
			);
			$this->CI->setParams($params, 'extra_js', true);
			if ($type == 'file')
				ob_end_clean();
		} catch (\Throwable $th) {
			print_r($th);
		}
	}
	function add_cachedStylesheet($css, $type = 'file', $pos = 'head', $data = array())
	{
		if ($type == 'file') {
			ob_start();
			if (!empty($data))
				extract($data);
			try {
				include_once ASSETS_PATH . 'css/' . $css . '.css';
			} catch (\Throwable $th) {
				print_r($th);
			}
		}

		$params = array(
			'style' => $type == 'file' ? ob_get_contents() : $css,
			'type' => 'inline',
			'pos' => $pos
		);
		$this->CI->setParams($params, 'extra_css', true);
		if ($type == 'file')
			ob_end_clean();
	}
	function add_stylesheet($css)
	{
		if (isset($css['pos'])) {
			$this->CI->setParams($css, 'extra_css', true);
		} else {
			foreach ($css as $c) {
				$this->CI->setParams($c, 'extra_css', true);
			}
		}
	}
	function myOS()
	{
		$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
		$os_platform    =   "Unknown OS Platform";
		$os_array       =   array(
			'/windows nt 6.2/i'     =>  'Windows 8',
			'/windows nt 6.1/i'     =>  'Windows 7',
			'/windows nt 6.0/i'     =>  'Windows Vista',
			'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     =>  'Windows XP',
			'/windows xp/i'         =>  'Windows XP',
			'/windows nt 5.0/i'     =>  'Windows 2000',
			'/windows me/i'         =>  'Windows ME',
			'/win98/i'              =>  'Windows 98',
			'/win95/i'              =>  'Windows 95',
			'/win16/i'              =>  'Windows 3.11',
			'/macintosh|mac os x/i' =>  'Mac OS X',
			'/mac_powerpc/i'        =>  'Mac OS 9',
			'/linux/i'              =>  'Linux',
			'/ubuntu/i'             =>  'Ubuntu',
			'/iphone/i'             =>  'iPhone',
			'/ipod/i'               =>  'iPod',
			'/ipad/i'               =>  'iPad',
			'/android/i'            =>  'Android',
			'/blackberry/i'         =>  'BlackBerry',
			'/webos/i'              =>  'Mobile'
		);

		foreach ($os_array as $regex => $value) {

			if (preg_match($regex, $user_agent)) {
				$os_platform    =   $value;
			}
		}

		return $os_platform;
	}
	function setView($views)
	{
		$this->views[] = $views;
	}
	function setParams($params, $key, $arrayOfArray = false)
	{
		if ($arrayOfArray)
			$this->params[$key][] = $params;
		else
			$this->params[$key] = $params;
	}
	public function render()
	{
		foreach ($this->views as $view) {
			$this->load->view($view, $this->params);
		}
		$this->views = [];
		$this->params = [];
	}
}

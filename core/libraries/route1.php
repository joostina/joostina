<?php

/**
 * RouteMap
 *
 * Rails like routes system for mapping URLs to controllers/actions
 * and generating URLs
 * This particular implementation is inspired by Pylons' Routes2 system.
 *
 * $Revision: 97d699af6957 $
 * $Date: 2008/05/15 05:04:34 $
 *
 * Copyright (c) 2008, Adis Nezirović <adis at localhost.ba>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *   - Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *   - Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */
class joosRoute {

	/**
	 *
	 * @var RouteMap
	 */
	private static $instance;


	public static function instance() {
		if (self::$instance === NULL) {

			$map = new RouteMap(
							array('base_url' => JPATH_SITE, 'url_rewriting' => true)
			);

			$routes = require(JPATH_BASE . DS . 'app' . DS . 'route.php');

			foreach ($routes as $route_name => $route) {
				$map->connect($route_name, $route['href'], $route['action'], $route['task']);
			}


			// @todo все нижеидущие правила надо переписать на массивы в /app/route.php
			$map->connect('content_view', 'view/catalog/*slug', 'content', 'view', array('slug' => '\S+'));
			$map->connect('category_view', 'catalog/*slug', 'content', 'category', array('slug' => '\S+'));

			$map->connect('content_cats_paginate', 'catalog/:slug', 'content', 'category', array('slug' => '\S+'));
			$map->connect('content_cats_paginate_page_index', 'catalog/:slug/page', 'content', 'category', array('slug' => '\S+'));
			$map->connect('content_cats_paginate_page_num', 'catalog/:slug/page/:page', 'content', 'category', array('slug' => '\S+', 'page' => '\d+'));


			// --------------------------------------------------------------------------Работа с пользователями
			$map->connect('register', 'register', 'users', 'register');
			$map->connect('register_check', 'register/check/*', 'users', 'check');
			$map->connect('lostpassword', 'lostpassword', 'users', 'lostpassword');
			$map->connect('login', 'login', 'users', 'login');
			$map->connect('logout', 'logout', 'users', 'logout');
			$map->connect('user_view', 'user/:username', 'users', 'showuser', array('username' => '[a-zA-Zа-яА-Я0-9-_*ё@).]+'));
			$map->connect('user_edit', 'user/:username/edit', 'users', 'edituser', array('username' => '[a-zA-Zа-яА-Я0-9-_*ё@).]+'));

			//------------------------------------------------------------------------------------------Контакты
			$map->connect('contacts', 'feedback', 'contacts', 'index');

			//------------------------------------------------------------------------------------------Карта сайта
			$map->connect('sitemap', 'sitemap', 'sitemap', 'index');

			//------------------------------------------------------------------------------------------опросы
			$map->connect('polls', 'polls', 'polls', 'index');
			$map->connect('poll_view', 'polls/:id', 'polls', 'view', array('id' => '\d+'));

			// -----------------------------------------------------------------------------------Новости
			$map->connect('news', 'news', 'news', 'index');
			$map->connect('news_page_index', 'news/page', 'news', 'index');
			$map->connect('news_page_num', 'news/page/:page', 'news', 'index', array('page' => '\d+'));

			$map->connect('news_archive', 'news/archive', 'news', 'archive');
			$map->connect('news_archive_page_index', 'news/archive/page', 'news', 'archive');
			$map->connect('news_archive_page_num', 'news/archive/page/:page', 'news', 'archive', array('page' => '\d+'));

			$map->connect('news_archive_year', 'news/archive/:year', 'news', 'archive', array('year' => '\d+'));
			$map->connect('news_archive_year_page_index', 'news/archive/:year/page', 'news', 'archive', array('year' => '\d+'));
			$map->connect('news_archive_year_page_num', 'news/archive/:year/page/:page', 'news', 'archive', array('page' => '\d+', 'year' => '\d+'));

			$map->connect('news_view', 'news/:id', 'news', 'view', array('id' => '\d+'));

			// -----------------------------------------------------------------------------------Поиск
			$map->connect('search', 'search', 'search', 'index');
			$map->connect('search_process', 'search/:slug/:searchword', 'search', 'index', array('slug' => '\S+', 'searchword' => '(.*?)'));
			$map->connect('search_page_index', 'search/:slug/:searchword', 'search', 'index', array('slug' => '\S+', 'searchword' => '(.*?)'));
			$map->connect('search_page_num', 'search/:slug/:searchword/page/:page', 'search', 'index', array('slug' => '\S+', 'searchword' => '(.*?)', 'page' => '\d+'));
			//$map->connect('search_page_num', 'search/:slug/:searchword/:page', 'search', 'index', array('slug' => '\S+', 'searchword' => '\S+', 'page' => '\d+'));
			// -----------------------------------------------------------------------------------Вопрос-ответ
			$map->connect('faq', 'faq', 'faq', 'index');
			$map->connect('faq_page_index', 'faq/page', 'job', 'index');
			$map->connect('faq_page_num', 'faq/page/:page', 'faq', 'index', array('page' => '\d+'));
			$map->connect('faq_send_question', 'faq/send', 'faq', 'send_question');

			$map->connect('faq_archive', 'faq/archive', 'faq', 'archive');
			$map->connect('faq_archive_page_index', 'faq/archive/page', 'faq', 'archive');
			$map->connect('faq_archive_page_num', 'faq/archive/page/:page', 'faq', 'archive', array('page' => '\d+'));

			$map->connect('faq_archive_year', 'faq/archive/:year', 'faq', 'archive', array('year' => '\d+'));
			$map->connect('faq_archive_year_page_index', 'faq/archive/:year/page', 'faq', 'archive', array('year' => '\d+'));
			$map->connect('faq_archive_year_page_num', 'faq/archive/:year/page/:page', 'faq', 'archive', array('page' => '\d+', 'year' => '\d+'));

			// -----------------------------------------------------------------------------------Странички
			$map->connect('pages_view_by_id', 'pages/:id', 'pages', 'view_by_id', array('id' => '\d+'));

			// конкретные страницы, всегда должно быть последним!
			$map->connect('pages_view', ':page_name', 'pages', 'view', array('page_name' => '[a-z]+'));

			$map->connect('category_collections', 'collections', 'content', 'category_collections');

			self::$instance = $map;
		}
		return self::$instance;
	}

	public static function route() {

		self::instance();

		$_SERVER['QUERY_STRING'] = rtrim($_SERVER['QUERY_STRING'], '/');
		$routs = self::$instance->dispatch($_SERVER['QUERY_STRING']);

		joosController::$activroute = $routs['route'];
		joosController::$controller = $routs['action'][0] == 'autocontroller' ? $routs['args']['option'] : $routs['action'][0];
		joosController::$task = $routs['action'][1] == 'autotask' ? (isset($routs['args']['task']) ? $routs['args']['task'] : 'index') : $routs['action'][1];
		joosController::$param = $routs['args'];
	}

	public static function href($route_name, array $params = array()) {
		return self::$instance->url_for($route_name, $params);
	}

	public static function redirect($url, $msg = '', $type = 'success') {

		$iFilter = joosInputFilter::instance();
		$url = $iFilter->process($url);

		empty($msg) ? null : joosFlashMessage::add($iFilter->process($msg));

		$url = preg_split("/[\r\n]/", $url);
		$url = $url[0];

		if ($iFilter->badAttributeValue(array('href', $url))) {
			$url = JPATH_SITE;
		}


		if (headers_sent()) {
			echo "<script>document.location.href='$url';</script>\n";
		} else {
			!ob_get_level() ? : ob_end_clean();
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
		}

		exit();
	}

}

/**
 * Mapper class for routes.
 *
 * Routes themselves are represented as a series of arrays:
 *     - route name and associated URL template (splitted to parts by '/')
 *     - attached controller and action
 *     - route arguments specified in route url (parts preceeded with ':')
 *     - optional requirements for each argument.
 *
 * "Dynamic" route can have arguments, requirements, controller and action
 * "Static" route can't have arguments nor requirements, only controller/action
 * "Passive" route can only have name and full URL, it can't be matched.
 *
 * e.g. route 'news_by_date' could look like this: 'news/:year/:month/:day'
 *
 * Note: We don't use regular expressions for route matching, only for
 *       matching route argument requirements. And you get to write regexes!
 *
 * Note: I might be a good idea to escape RouteMap::match($url) argument, e.g.
 *         RouteMap::match(urldecode($url))
 *       You'll know you need it when you see it.
 *       The same applies to RouteMap::dispatch()
 */
class RouteMap {

	protected $_dynamic; // route => URL mappings (for dynamic routes)
	protected $_actions; // route => (controller, action) mappings
	protected $_reqs; // requirements for route arguments
	protected $_passive; // route => URL mappings (for passive routes)
	protected $_static; // route => URL mappings (for static routes)
	protected $_urls; // saved URLs for dynamic and static routes
	protected $_failed; // fail routes with error codes and messages
	protected $base_url; // helps us to construct full URL
	protected $url_rewriting; // shall we asume mod_rewrite functionality
	public $current; // Currently selected route (automatically set by dispatch)
	private $_notmatch_args; //Флаг, обозначающий, что парсить аргументы не нужно

	/**
	 * Initialize various route maps, extract and apply config options.
	 */

	public function __construct(array $config = NULL, array $routes = NULL) {
		$this->_dynamic = array();
		$this->_actions = array();
		$this->_reqs = array();
		$this->_passive = array();
		$this->_static = array();
		$this->_urls = array();
		$this->_failed = array();

		$this->base_url = (array_key_exists('base_url', $config)) ?
				$config['base_url'] : '';
		$this->url_rewriting = (array_key_exists('url_rewriting', $config)) ?
				$config['url_rewriting'] : False;

		$this->base_url = ($this->url_rewriting) ?
				$this->base_url . '/' : $this->base_url . '/index.php?';

		if (!empty($routes)) {
			$this->load($routes);
		}
	}

	/**
	 * Connect a route name to an URL pattern.
	 * Additionally, attach an action to a route, and impose some
	 * restrictions to route arguments.
	 *
	 * $route_name - string which represents our route.
	 * $route_url - route pattern, e.g. 'category/:arg/*wildcard_arg'
	 * $controller - name of the controller class
	 * $action - controller method to execute on match
	 * $reqs - array of requirements for route arguments,
	 *     keys are route argument names, values are regular expressions
	 *
	 * Please note that $controller and $action are only conventions. If you
	 * intend to do your own dispatching, you get to choose their meaning,
	 *   i.e. "controller" could be a file, "action" could be a class, and
	 *        you could extract "method" from arguments by hand.
	 *
	 * You can get the original route_url afterwards with
	 *     RouteMap::url_for();
	 */
	public function connect($route_name, $route_url, $controller = NULL, $action = NULL, array $reqs = NULL) {
		if (!empty($controller) || !empty($action)) {
			$this->_urls[$route_name] = $route_url;
			$parts = explode('/', $route_url);
			$this->_actions[$route_name] = array($controller, $action);

			$is_dynamic = False;

			foreach ($parts as $offset => $part) {
				if (!empty($part) && (($part[0] == ':') || ($part[0] == '*'))) {
					$is_dynamic = True;
					$this->_args[$route_name] = $part;
				}
			}

			if ($is_dynamic) {
				$this->_dynamic[$route_name] = $parts;

				if (!empty($reqs)) {
					$this->_reqs[$route_name] = $reqs;
				}
			} else {
				$this->_static[$route_name] = $parts;
			}
		} else {
			$this->_passive[$route_name] = $route_url;
		}
	}

	/**
	 * Extract route arguments for some matching url
	 *
	 * As usual, arguments named "parts" are arrays of path components.
	 */
	protected function extract_args(array $route_parts, array $url_parts) {
		$args = array();

		$i = 0;
		foreach ($route_parts as $offset => $part) {
			if ($part[0] == ':') {
				$args[substr($part, 1)] = $url_parts[$offset];
			} elseif ($part[0] == '*') {
				/* Wildcard arg can contain slashes, join all parts after '*'
				 * Additionally, it must be last argument, return imediately.
				 */
				$args[substr($part, 1)] = implode('/', array_slice($url_parts, $i));
				return $args;
			}
			$i++;
		}

		return $args;
	}

	/**
	 * Check each route arguments against corresponding
	 * requirement in RouteMap::_reqs.
	 *
	 * Requirement *must* be valid perl regular expression.
	 * It only makes sense to do full string matching for requirements
	 * so ^ and $ are *always* added automatically.
	 */
	protected function check_reqs($route_name, array $route_args) {
		if (empty($this->_reqs[$route_name]) || $this->_notmatch_args) {
			return true;
		}

		foreach ($this->_reqs[$route_name] as $arg_name => $arg_req) {
			// TODO тут опасное Joostina - место
			if (!preg_match('/^' . $arg_req . '$/iu', mb_convert_encoding($route_args[$arg_name], 'utf-8', 'auto'))) {
				return False;
			}
		}

		return True;
	}

	/**
	 * Return full route match for $url
	 * This includes: route_name, controller/action pair
	 * and route arguments.
	 *
	 * Unescape/urldecode $url as neccessary before matching.
	 */
	public function match($url) {
		/* First, try to match static route
		 * which is not in fail list
		 */
		$route_name = array_search($url, $this->_urls);

		if (($route_name !== False)
				&& (array_key_exists($route_name, $this->_static))) {
			return $this->match_failed(array($route_name,
				$this->_actions[$route_name], NULL));
		}
		unset($route_name);

		$url_parts = explode('/', $url);
		$url_parts_len = count($url_parts);

		$matched_routes = $this->_dynamic;
		$matched_wildcard = NULL;

		/* Find potential candidate(s), always preferring routes with
		 * static parts e.g. for routes
		 *   ':category/:article' and 'news/:article'
		 * and url
		 *   'news/something'
		 * we can safely discard the first route, otherwise we would have to
		 * specify requirement for the first: 'category' => '(?!(news)).*'
		 */
		foreach ($url_parts as $offset => $part) {
			$dynamic_part_routes = array();
			$static_part_routes = array();

			foreach ($matched_routes as $matched_name => $matched_parts) {
				if (count($matched_parts) <= $offset) {
					// route too short
					continue;
				}

				if (($matched_parts[$offset][0] != ':') && ($matched_parts[$offset] == $part)) {
					$static_part_routes[$matched_name] = $matched_parts;
				} elseif ($matched_parts[$offset][0] == ':') {
					$dynamic_part_routes[$matched_name] = $matched_parts;
				} elseif ($matched_parts[$offset][0] == '*') {
					// xxx: What if both, route and url contain * at same place?
					$wild_args = $this->extract_args($matched_parts, $url_parts);

					if ($this->check_reqs($matched_name, $wild_args)) {
						if (!$matched_wildcard) {
							$matched_wildcard = $this->match_failed(array($matched_name,
										$this->_actions[$matched_name], $wild_args));
						} else {
							throw new ERouteMapNoReqs();
						}
					} else {
						/* Some parts of wildcard route didn't match,
						  it will be discarded. */
						continue;
					}
				}
			}

			if (!empty($static_part_routes)) {
				$matched_routes = $static_part_routes;
			} else {
				$matched_routes = $dynamic_part_routes;
			}
		}

		if (count($matched_routes) == 0) {
			if (!$matched_wildcard) {
				throw new ERouteMapNoMatch();
			} else {
				return $matched_wildcard;
			}
		} elseif (count($matched_routes) == 1) {
			/* Anyone knows how to extract key->value pair from a dictionary
			 * with length=1 where we don't know the key?
			 */
			foreach ($matched_routes as $name => $route_parts) {
				$route_name = $name;
				$route_args = $this->extract_args($route_parts, $url_parts);
			}

			if (count($this->_dynamic[$route_name]) != $url_parts_len) {
				if (!$matched_wildcard) {
					// false match, actual route is longer
					throw new ERouteMapNoMatch();
				} else {
					return $matched_wildcard;
				}
			}

			if ($this->check_reqs($route_name, $route_args)) {
				return $this->match_failed(array($route_name,
					$this->_actions[$route_name], $route_args));
			} else {
				if (!$matched_wildcard) {
					throw new ERouteMapReqs();
				} else {
					return $matched_wildcard;
				}
			}
		} elseif (count($matched_routes) > 1) {
			// filter out false matches
			$real_matched = array();

			foreach ($matched_routes as $n_name => $n_parts) {
				if (count($n_parts) == $url_parts_len) {
					$real_matched[$n_name] = $n_parts;
				}
			}

			if (empty($real_matched)) {
				if (!$matched_wildcard) {
					throw new ERouteMapNoMatch();
				} else {
					return $matched_wildcard;
				}
			}

			/* OK, we have N routes with same signature
			 * At least N-1 routes *must* specify requirements. We try to match
			 * dynamic route with requirements. If requirements are not
			 * satisfied we select route without requirements (if such route
			 * exists). Otherwise we fail.
			 */

			$no_req_seen = False; // have we seen route without arguments?
			$no_req_name = NULL;
			$no_req_parts = NULL;

			foreach ($real_matched as $n_name => $n_parts) {
				if (empty($this->_reqs[$n_name])) {
					if ($no_req_seen) {
						if (!$matched_wildcard) {
							throw new ERouteMapNoReqs();
						} else {
							return $matched_wildcard;
						}
					} else {
						$no_req_seen = True;
						$no_req_name = $n_name;
						$no_req_parts = $n_parts;
					}
				} else {
					$n_args = $this->extract_args($n_parts, $url_parts);

					if ($this->check_reqs($n_name, $n_args)) {
						// We have a winner, return immediately
						return $this->match_failed(array($n_name,
							$this->_actions[$n_name], $n_args));
					}
				}
			}

			/**
			 * OK, at this point we either have "safe" route, without
			 * requirements, or none of the duplicates fulfills requirements.
			 */
			if ($no_req_seen) {
				$no_req_args = $this->extract_args($no_req_parts, $url_parts);

				return $this->match_failed(array($no_req_name,
					$this->_actions[$no_req_name], $no_req_args));
			} else {
				if (!$matched_wildcard) {
					throw new ERouteMapReqs();
				} else {
					return $matched_wildcard;
				}
			}
		}
	}

	/**
	 * Get stored URL for a named route, optionally filling
	 * route arguments in the process.
	 * Missing route args are replaced with '?'.
	 */
	public function url_for($route_name, array $route_args = NULL) {
		if (array_key_exists($route_name, $this->_passive)) {
			return $this->_urls[$route_name];
		}

		if (array_key_exists($route_name, $this->_static)) {
			return $this->base_url . $this->_urls[$route_name];
		}


		if (!array_key_exists($route_name, $this->_dynamic)) {
			throw new ERouteMapNotFound();
		}

		$url = '';
		$this->_notmatch_args = false;
		foreach ($this->_dynamic[$route_name] as $part) {
			if ($part[0] == ':') {
				$arg_name = substr($part, 1);
				$arg = $route_args[$arg_name];

				// Joostina, патч для пропуска параметров, равных 0
				if (empty($arg) && !($arg === 0)) {
					$arg = '?';
				}

				$url .= "/$arg";
			} elseif ($part[0] == '*') {
				$arg_name = substr($part, 1);
				$arg = $route_args[$arg_name];

				if (empty($arg)) {
					$arg = '';
				}

				$url .= "/$arg";
				break;
			} else {
				$url .= "/$part";
			}
		}

		return $this->base_url . ltrim($url, '/');
	}

	/**
	 * Basic redirection
	 *
	 * Since I often use it for on site redirects default HTTP code is 302
	 *   e.g. redirect to login page if a user is not logged in or similar.
	 */
	public function redirect_to($route_name, $route_args = NULL, $http_code = 302) {
		header('Location: ' . $this->url_for($route_name, $route_args), True, $http_code);
		exit();
	}

	/**
	 * Add route on failed list, with code and optional message
	 * RouteMap::error() is hardcoded fail action.
	 *
	 * We don't check whether $route_name exists, but if you forget
	 * to connect $route_name to URL pattern, it can't be returned
	 * from    RouteMap::match() as "fail" route.
	 */
	public function fail($route_name, $http_code = 404, $msg = NULL) {
		$this->_failed[$route_name] = array(
			'http_code' => $http_code,
			'msg' => $msg
		);
	}

	/**
	 * Checks whether route match, as returned from RouteMap::match(), is on
	 * the "fail" list, and returns new error match if that's the case.
	 */
	protected function match_failed(array $route_match) {
		$route_name = $route_match[0];

		if (array_key_exists($route_name, $this->_failed) !== False) {
			return array($route_name, array('RouteMap', 'error'),
				$this->_failed[$route_name]);
		} else {
			return $route_match;
		}
	}

	/**
	 * Default fail action. It will be returned by RouteMap::match_failed()
	 * if a route is on the failed list.
	 *
	 * Additionally, you can (ab)use this method for your own
	 * HTTP status messages. If you need more error codes, subclass RouteMap.
	 */
	static public function error($args) {
		$http_msgs = array(
			400 => '400 Bad Request',
			401 => '401 Unauthorized',
			403 => '403 Forbidden',
			404 => '404 Not Found',
			410 => '410 Gone',
			500 => '500 Internal Server Error',
		);

		$http_msg = $http_msgs[$args['http_code']];

		header("HTTP/1.1 $http_msg");
		print ($args['msg']);
		exit();
	}

	/**
	 * Bulk loading of routes.
	 */
	public function load(array $routes) {
		foreach ($routes as $r) {
			switch (count($r)) {
				case 2: // passive route
					$this->connect($r[0], $r[1]);
					break;
				case 4: // static route
					$this->connect($r[0], $r[1], $r[2], $r[3]);
					break;
				case 5: // dynamic route
					$this->connect($r[0], $r[1], $r[2], $r[3], $r[4]);
					break;
				default:
					// print some warning
					break;
			}
		}
	}

	/**
	 * Sample dispatcher. Write your own if you need anything fancy.
	 * Unescape/urldecode $url as neccessary before calling dispatch.
	 */
	public function dispatch($url) {
		try {
			list($route, $action, $args) = $this->match($url);
		} catch (ERouteMapNotFound $e) {
			JDEBUG ? joosDebug::add('ROUTE - Error: Route not found') : null;
			return joosController::$activroute = 404;
			//exit('Error: Route not found');
		} catch (ERouteMapNoMatch $e) {
			//$route = $action = '404';
			//$args = array();
			JDEBUG ? joosDebug::add('ROUTE - Error: Page not found') : null;
			return joosController::$activroute = 404;
			//exit('Error: Page not found');
		} catch (ERouteMapReqs $e) {
			JDEBUG ? joosDebug::add('ROUTE - Error: Invalid URL arguments') : null;
			return joosController::$activroute = 404;
			//exit('Error: Invalid URL arguments');
		} catch (ERouteMapNoReqs $e) {
			JDEBUG ? joosDebug::add('ROUTE - Error: Ambiguous URL rules detected') : null;
			return joosController::$activroute = 404;
			//exit('Error: Ambiguous URL rules detected');
		}

		$this->current = $route;

		return array(
			'route' => $route,
			'action' => $action,
			'args' => $args
		);

		if (!empty($action[0])) {
			call_user_func($action, $args);
		} else {
			call_user_func($action[1], $args);
		}
	}

}

/**
 * Some common routing errors/exceptions.
 * You should catch these by name in your custom dispatchers
 * see RouteMap::dispatch()
 */
// Base exception
class ERouteMap extends joosException {

}

// Route name not found in routing tables
class ERouteMapNotFound extends ERouteMap {

}

// Route match did not find any results
class ERouteMapNoMatch extends ERouteMap {

}

// Route match did not fulfill all requirements
class ERouteMapReqs extends ERouteMap {

}

// Multiple routes with same signature but no reqirements
class ERouteMapNoReqs extends ERouteMap {

}

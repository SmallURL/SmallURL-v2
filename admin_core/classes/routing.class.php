<?php
class routing extends core {
    // Developed by Anonymous
    // In conjunction with Potato
    // :D

    private $established;
    private $routing;
    private $alias;
    private $defaultController;
    private $routingAlias;
    private $cpath;
    private $upath;

    function __construct() {
        $this->established = false;
        $this->cpath = $_SERVER['REQUEST_URI'];
    }

    /*
    @function addDomain
    @purpose Adds a domain to the routing map
    @req $domain - domain name (string) $alias - user alias(string)
    */

    public function addDomain($domain, $alias) {
        if(!$this->established):
            if (!isset($this->alias[$alias])):
                if(!isset($this->routing[$domain]['alias'])):
                    $this->routing[$domain]['domain'] = $domain;
                    $this->routing[$domain]['alias'] = $alias;
                    $this->alias[$alias] = $domain;
                else:
                    trigger_error("You can't redeclare a domain!", E_USER_ERROR);
                endif;
            else:
                trigger_error("Alias has already been defined", E_USER_ERROR);
            endif;
        else:
            trigger_error("Routing has already been handled, can't modify routing map.", E_USER_ERROR);
        endif;
    }

    /*
    @function addController
    @purpose Adds a controller to the routing map (PHP File)
    @req $alias - Domain Name (string) $controller - Controller Name (string) $default - Default Index  (string)
         $do404 - Display a 404 or point to default (bool) $reqLogin - check if user authed(bool)
    */

    public function addController($alias, $controller, $default, $do404, $reqLogin) {
        if(!$this->established):
            if(isset($this->alias[$alias])):
                if(!isset($this->routing[$this->alias[$alias]]['controllers'][$controller])):
                    $this->routing[$this->alias[$alias]]['controllers'][$controller] = array("default" => $default, "404" => $do404, "reqLogin" => $reqLogin);
                else:
                    trigger_error("You have already declared the controller ".htmlentities($controller).".", E_USER_ERROR);
                endif;
            else:
                trigger_error("This domain is unavailable at the current time.", E_USER_ERROR);
            endif;
        else:
            trigger_error("Routing has already been handled, can't modify routing map.", E_USER_ERROR);
        endif;
    }

    /*
    @function domainAlias
    @purpose Adds an alias from one domain to another
    @req $domain - Base domain (string) $toDomain - Domain to forward to (string)
    */

    public function domainAlias($domain, $toDomain) {
        if(!$this->established):
            if(isset($this->routing[$toDomain]['alias'])):
                $this->routingAlias[$domain] = $toDomain;
            else:
                trigger_error("You can't alias to a non-existent domain!", E_USER_ERROR);
            endif;
        else:
            trigger_error("Routing has already been handled, can't modify routing map.", E_USER_ERROR);
        endif;
    }

    /*
    @function addRoute
    @purpose Adds a route to the routing map, redirects a path to a seperate path
    @req $dRECV - Location (string) $dSEND - Where to send (string) $auth - Check user auth (bool)
    */

    public function addRoute($dRECV, $dSEND, $auth) {
        if(!$this->established):
            // if userpath = /login then we really go to home/login
            // check if controller exists, if it does then do what? fuck all.

            //
            //echo "adding specialized route from: $dRECV to: $dSEND ONLY if the user is currently logged in: $auth\n";
        else:
            trigger_error("Routing has already been handled, can't modify routing map.", E_USER_ERROR);
        endif;
    }
    /*
    @function defaultController
    @purpose Specifies the default controller
    @req $alias - The domain alias we are controlling (static) $default - The name of a controller (static)
    */

    public function defaultController($alias, $default) {
        if(!$this->established):
            if(isset($this->alias[$alias])):
                if(isset($this->routing[$this->alias[$alias]]['controllers'][$default])):
                    $this->defaultController[$this->alias[$alias]] = $default;
                else:
                    trigger_error("You can't set your controller to a non-existent controller!", E_USER_ERROR);
                endif;
            else:
                trigger_error("This domain is unavailable at the current time.", E_USER_ERROR);
            endif;
        else:
            trigger_error("Routing has already been handled, can't modify routing map.", E_USER_ERROR);
        endif;
    }

    /*
    @function templateDomain404
    @purpose Specifies the 404 template for the routing
    @req $template_loc - Template Location (string)
    */

    public function templateDomain404($template_loc) {
        if(!$this->established):
          //  echo "If we can't find the domain, display the following template: ".$template_loc."\n";
        else:
            trigger_error("Routing has already been handled, can't modify routing map.", E_USER_ERROR);
        endif;
    }

    /*
    @function templateController404
    @purpose Specifies the 404 template for the routing
    @req $template_loc - Template Location (string)
    */

    public function templateController404($template_loc) {
        if(!$this->established):
            //echo "If we can't find the controller/endpoint, display the following template: ".$template_loc."\n";
        else:
            trigger_error("Routing has already been handled, can't modify routing map.", E_USER_ERROR);
        endif;
    }

    /*
    @function handleRoute
    @purpose Puts the routing into use, takes the generated data and handles it.
    @req N/A
    */

    public function handleRoute() {
        $domain = $this->getDomain();

        $upath = explode("/", $this->cpath);
        array_shift($upath);

        // Check if we are using an alias
        if(isset($this->routingAlias[$domain])) {
            $domain = $this->routingAlias[$domain];
        }

        // Check if end domain exists
        if(isset($this->routing[$domain])):
            // No controller provided
            if($upath[0] == '') {
                $upath[0] = $this->defaultController[$domain];
            }
            if(isset($this->routing[$domain]['controllers'][$upath[0]])) {
                if($this->routing[$domain]['controllers'][$upath[0]]['reqLogin']) {
                    if($this->doAuth()) {
                        $proc = true;
                    } else {
                        $proc = false;
                    }
                } else {
                    $proc = true;
                }

                if($proc) {
                    // Check if the user has specified a endpoint
                    if(!isset($upath[1]) || $upath[1] == '') {
                        $upath[1] = $this->routing[$domain]['controllers'][$upath[0]]['default'];
                    }

                    $path = APPPATH."/controllers/".$upath[0].".controller.php";

                    if(file_exists($path)) {
                        include $path;
                        $loaded = new controller();

                        if(method_exists($loaded, $upath[1]) && is_callable(array($loaded, $upath[1]))) {
                            $loaded->$upath[1]();
                        } else {
                            if($this->routing[$domain]['controllers'][$upath[0]]['404'] === true) {
                                $this->handleRouteError("Controller", $upath);
                            } else {
                                $d = $this->routing[$domain]['controllers'][$upath[0]]['default'];
                                $loaded->$d();
                            }
                        }
                    } else {
                        trigger_error("The defined controller does NOT exist.", E_USER_ERROR );
                    }
                } else {
                    header("Location: http://account.".SITE_URL."/login");
                }

            } else {
                $this->handleRouteError("Controller", $upath);
            }
            //print_r($upath);
        else:
            trigger_error("Unable to route you to the domain.", E_USER_ERROR);
        endif;

        $this->established = true;
    }

    public function handleRouteError($type, $route) {
        if($type != 'access_error') {
            die("Route doesn't exist");
        } else {
            die("You need to login.");
        }
    }

    private function doAuth() {
        $account = new account();
        if(isset($_SESSION['uid'])) {
            if($account->getLevel() >= 70) {
                return true;
            } else {
                return false;
            }
        }
    }

}

$route = new routing();

?>

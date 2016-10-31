<?php

/**
 * Description of FBconnect
 *
 * @author pirhoo
 */

class FBconnect {

    public function  __construct($app_ID, $app_secret) {
        $this->app_ID = $app_ID;
        $this->app_secret = $app_secret;

        $this->doFBcookie();
    }

    public function getCookie() {
        return $this->cookie;
    }
    
    public function setCookie($cookie) {
        $this->cookie = $cookie;
    }

    public function isConnected() {
        return $this->cookie || $this->_cookie != null ? true : false;
    }

    public function getFriends() {
        if( $this->isConnected() )
            if($this->friends == null)
                $this->friends = json_decode( file_get_contents('https://graph.facebook.com/me/friends?access_token='.$this->cookie["access_token"]) );
       
        return $this->friends;
    }

    public function getUser() {
        
        if( $this->isConnected() )
            if($this->user == null)
                $this->user = json_decode( file_get_contents('https://graph.facebook.com/me?access_token='.$this->cookie["access_token"]) );

        return $this->user;
    }


    // Force une pseudo connexion à l'aide d'un token personalisé...
    // Pour trouver un token http://developers.facebook.com/docs/api
    public function startSimulation($token) {
        $this->_cookie = $this->cookie;
        $this->cookie = Array("access_token" => $token);

    }

    public function stopSimulation() {
        $this->cookie = $this->_cookie;
        $this->_cookie = null;

    }

    private function doFBcookie() {
        $args = array();
        parse_str(trim($_COOKIE['fbs_' . $this->app_ID], '\\"'), $args);
        ksort($args);
        $payload = '';
        
        foreach ($args as $key => $value)
            if ($key != 'sig')
                $payload .= $key . '=' . $value;
           
        if (md5($payload . $this->app_secret) != $args['sig'])
            $this->cookie = null;
        
        $this->cookie = $args;
    }

    protected $app_ID;
    protected $app_secret;
    protected $cookie;

    private $user = null;
    private $friends = null;
    private $_cookie; // util pour simuler une connexion est garder le cookie précédent en mémoire
}
?>

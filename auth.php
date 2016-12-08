<?php
/**
 * SAML2 authentication plugin with cascade to authplain
 *
 * @author     Maxime Appolonia <maxime.appolonia@restena.lu>
 */

require_once DOKU_PLUGIN . '/adfs/auth.php';
require_once DOKU_PLUGIN . '/authplain/auth.php';

class auth_plugin_saml2cascade extends auth_plugin_authplain {
    
    private $adfsPlugin;
    
    public function __construct() {
        parent::__construct();

        // We inherit from authplain so all the capabilities (create_user, do_login...) are set to true
        
        // we just add the external capability 
        $this->cando['external'] = true;
        $this->cando['logoff']   = true;

		// we maintain an instance of the afdsAuthPlugin for saml capabilities proxying
        $this->adfsPlugin = new auth_plugin_adfs();
    }

    
    /**
     * Checks the session to see if the user is already logged in
     *
     * If not logged in, redirects to SAML provider
     */
    public function trustExternal($user, $pass, $sticky = false) {
    	global $INPUT;
    	$session = $_SESSION[DOKU_COOKIE]['auth'];
    	
    	
    	if($INPUT->str("auth_source")== "plain"){
    		// we call the standard process (which will call the individual methods of the object from which the current object inherit)
    		$_SESSION[DOKU_COOKIE]['auth']['auth_source'] = "plain";
    		return auth_login($user, $pass, $sticky);
    		
    	}else if($INPUT->str("auth_source")== "saml2"){
    		// we call the adfs auth plugin
    		$_SESSION[DOKU_COOKIE]['auth']['auth_source'] = "saml2";
    		return $this->adfsPlugin->trustExternal($user, $pass, $sticky);
    		
    	}else{
    		$success = false;
    		if($session['auth_source'] == "plain"){
    			$success = auth_login($user, $pass, $sticky);
    		}else if ($session['auth_source'] == "saml2"){
    			$success = $this->adfsPlugin->trustExternal($user, $pass, $sticky);
    		}
    		
    		if(!$success)
    			$session['auth_source'] = "";
    		
    		return $success;
    	}
    	

    }
    
    public function logOff(){
    	$session = $_SESSION[DOKU_COOKIE]['auth'];
    	
    	if($session['auth_source'] == "plain"){
	    	// do logout of authplain using the standard function
	    	// in order to avoid it to call the auth plugin method in loop in which we are we fake that $auth is false;
	    	global $auth;
	    	$auth_backup = $auth;
	    	$auth = false;
	    	auth_logoff();
	    	$auth = $auth_backup;
    	}else if($session['auth_source'] == "saml2"){
    		// we call the adfs auth plugin
    		$this->adfsPlugin->logOff();
    	}
    	
    	$session['login_source'] = "";
    }

    
	function cleanUser($user) {
        return $this->adfsPlugin->cleanUser($user);
    }

    function cleanGroup($group) {
        return $this->adfsPlugin->cleanGroup($group);
    }
    
}

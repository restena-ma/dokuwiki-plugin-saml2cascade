<?php
/**
 * SAML2 authentication plugin with cascade to authplain
 *
 * @author     Maxime Appolonia <maxime.appolonia@restena.lu>
 */

class action_plugin_saml2cascade extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler $controller) {
    	$controller->register_hook('HTML_LOGINFORM_OUTPUT', 'BEFORE', $this, 'handle_loginform');
    }


    public function handle_loginform(&$event, $param) {
    	// This is a hook called after the login form has been generated
    	// we add an hidden field to detect if the user has validated the login form
        $event->data->addHidden('auth_source', 'plain');
        
        // and we add a link that point to a new login_source
        $event->data->insertElement(0, '<div style="margin-bottom:20px;">
        			<a href="'.wl($ID,array('do' => 'login', 'auth_source' => 'saml2')).'" style="background: #e9e9e9; border-radius: 5px; padding: 10px; border: 1px solid #ccc; font-weight: bold;">Login via Clueless SSO</a>
        		</div>');
                
        return;
    }

}
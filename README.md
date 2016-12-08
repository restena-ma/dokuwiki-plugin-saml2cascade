## dokuwiki-plugin-saml2cascade

### Description
This extension adds a "Login via SAML" button above the standard login form of dokuwiki.  
It then becomes possible to sign in either user the SSO or the plain auth extension.

### Requirements
The modified version of the "adfs saml" authentication extension is required for the backend authentication procedure.  
It is available [here](https://github.com/restena-ma/dokuwiki-plugin-adfs) (and the original is [here](https://github.com/cosmocode/dokuwiki-plugin-adfs))

### Installation
Once you have installed the adfs extension, you can install the archive available [here](https://github.com/restena-ma/dokuwiki-plugin-saml2cascade/zipball/master) using the dokuwiki extension manager.

### Configuration
You can use the configuration screen of dokuwiki or directly modifiy the config file.
The options related to the SAML configuration are in the configuration part of the adfs extension but you have to choose "saml2cascade" as authentication provider.

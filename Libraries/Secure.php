<?php 
namespace App\Libraries;
use App\Controllers\BaseController;

/**
 * CodeIgniter 4 URL Friendly Encryption
 *
 * Provides encyption and decryption result that allowed to be pass on URL.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Eimiza Faisha Bin Azmi
 * @link		https://github.com/eimiza/ci4-url-friendly-encryption/
 */

class Secure extends BaseController{

    public function __construct(){
        $this->session = service('session');
    }

    //Normal Encryption without considering the session. - less secured
    function enc($string){
        $encrypter = service('encrypter');
        $cipher = base64_encode($encrypter->encrypt($string));
        return str_replace(array('+', '/', '='), array('-', '_', '~'), $cipher);
    }

    //Normal Decryption without considering the session. - less secured
    function dec($cipher){
        $encrypter = service('encrypter');
        $cipher=str_replace(array('-', '_', '~'), array('+', '/', '='), $cipher);
        $cipher=base64_decode($cipher);
        $cipher=$encrypter->decrypt($cipher);
        return $cipher;
    }

    //Combined Decryption with a value of session (eg: user_id). - more secured
    function enc_session($string){
        $encrypter = service('encrypter');
        $sm_id = $this->session->get('session_id'); //Replace with your session id here
        $cipher = base64_encode($encrypter->encrypt($string.'/'.$sm_id));
        return str_replace(array('+', '/', '='), array('-', '_', '~'), $cipher);
    }

    //Combined Encryption with a value of session (eg: user_id). - more secured
    function dec_session($cipher){
        $encrypter = service('encrypter');
        $cipher=str_replace(array('-', '_', '~'), array('+', '/', '='), $cipher);
        $cipher=base64_decode($cipher);
        $cipher=$encrypter->decrypt($cipher);
        $pieces = explode("/", $cipher);
        if($pieces[1] == $this->session->get('session_id')){ //Replace with your session id here
            $cipher = $pieces[0];
        }else{
            echo 'BASECONTROLLER: SECURE ENCRYPTION ERROR'; exit;
        }
        return $cipher;
    }
}

?>

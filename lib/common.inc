<?php

class Shell {

  public static function connect($host, $port = 22, $user, $pub_key, $prv_key) {
    if(function_exists('ssh2_connect')) {
      require_once('SSH2.php');
      $ssh = new SSH2($host, $port);
      try {
        if(!$ssh->login($user, $pub_key, $prv_key)) return FALSE;
        else return $ssh;
      } catch(Exception $e) {
        return FALSE;
      }
    } else {
      require_once('Crypt/RSA.php');
      require_once('Net/SSH2.php');
      $ssh = new Net_SSH2($host, $port);
      // Load SSH keys
      $rsa_pub_key = new Crypt_RSA();
      $rsa_priv_key = new Crypt_RSA();
      $rsa_pub_key->loadKey(file_get_contents($pub_key));
      $rsa_priv_key->loadKey(file_get_contents($prv_key), CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
      if (!$ssh->login($user, $rsa_priv_key)) return FALSE;
      else return $ssh;
    }
  }
}

function usage() {
  echo("Usage: drums config.yml\nwhere config.yml is your config file.\n");
  exit();
}

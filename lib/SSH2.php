<?php

class SSH2 {

  private $host;
  private $port;
  private $prv_key;
  private $pub_key;
  private $connection;

  public function __construct($host, $port = 22, $timeout = 10) {
    $this->host = $host;
    $this->port = $port;
  }

  public function login($user, $pub_key, $prv_key) {
    if(!($this->connection = ssh2_connect($this->host, $this->port, array('hostkey'=>'ssh-rsa')))) {
      return FALSE;
    }

    if(!ssh2_auth_pubkey_file($this->connection, $user, $pub_key, $prv_key)) {
      return FALSE;
    }
    return TRUE;
  }

  public function exec($cmd) {
    if(!$this->connection) return("No connection\n");
    if(!($stream = ssh2_exec($this->connection, $cmd))) {
      die("SSH command failed\n");
    }
    stream_set_blocking($stream, true);
    $data = "";
    while ($buf = fread($stream, 4096)) {
      $data .= $buf;
    }
    fclose($stream);
    return $data;
  }

  public function disconnect() {
    $this->exec('echo "EXITING" && exit;');
    $this->connection = null;
  }

  public function __destruct() {
    $this->disconnect();
  }

  public function getHost() {
    return $this->host;
  }

  public function getPort() {
    return $this->port;
  }
}

<?php
/**
 * SSH2 class
 * @package scrissh
 */
/**
 * A simple interface for PHP SSH2 native implementation
 * @package scrissh
 */
class SSH2 {

  /**
   * The host to connect to
   * @access private
   * @var string
   */
  private $host;

  /**
   * The port to use
   * @access private
   * @var int
   */
  private $port;

  /**
   * The path to the private SSH2 key
   * @access private
   * @var string
   */
  private $prv_key;

  /**
   * The path to the public SSH2 key
   * @access private
   * @var string
   */
  private $pub_key;

  /**
   * The SSH2 connection object
   * @access private
   * @var SSH2 object
   */
  private $connection;

  /**
   * Constructor
   * @param string $host
   * @param int $port
   * @param int $timeout
   */
  public function __construct($host, $port = 22, $timeout = 10) {
    $this->host = $host;
    $this->port = $port;
  }

  /**
   * Logs in if connection is available, using SSH2 keys
   * @param string $user
   * @param string $pub_key
   * @param string $prv_key
   * @return bool true if success, false if failure
   */
  public function login($user, $pub_key, $prv_key) {
    if(!($this->connection = ssh2_connect($this->host, $this->port, array('hostkey'=>'ssh-rsa')))) {
      return FALSE;
    }

    if(!ssh2_auth_pubkey_file($this->connection, $user, $pub_key, $prv_key)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Executes a command on the remote host
   * @param string $cmd the command to execute
   * @return string the command result or dies if no connection
   */
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

  /**
   * Disconnects from the host
   */
  public function disconnect() {
    $this->exec('echo "EXITING" && exit;');
    $this->connection = null;
  }

  /**
   * Destructor
   */
  public function __destruct() {
    $this->disconnect();
  }

  /**
   * Returns the host
   *
   * @return string the host
   */
  public function getHost() {
    return $this->host;
  }

  /**
   * Returns the port
   *
   * @return string the port
   */
  public function getPort() {
    return $this->port;
  }
}

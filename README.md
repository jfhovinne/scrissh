scrissh
=======

A tool written in PHP to execute remote commands in batch with SSH.

This project started as an alternative to [Python Fabric](http://docs.fabfile.org/)
and evolved as a lightweight PHP script that allows the execution of multiple commands
on remote shells using SSH2 and YAML configuration files.

Requirements
------------

* PHP cli, see http://php.net/manual/en/features.commandline.php
* PHP libssh2 bindings must be installed on the client host.
See http://www.php.net/manual/en/book.ssh2.php for more information.
* A SSH server must be running and listening on the remote host (seems obvious).

In case libssh2 isn't available, a fallback using phpseclib is provided
(untested, any feedback appreciated).

This script has been written on and tested with Debian GNU/Linux.
Compatibility with other systems is unknown.

Example configuration file: example.yml
---------------------------------------

    SSH:
      keys:
        private-key: /home/foo/.ssh/id_rsa.prv
        public-key:  /home/foo/.ssh/id_rsa.pub
    Servers:
      server1:
        host: server1.example.com
        port: 22
        user: bar
        commands:
          - 'cd /etc && pwd'
          - 'cat /etc/hosts'
      server2:
        host: server2.example.com
        port: 22
        user: foo
        commands:
          - 'touch test.txt'

To execute the above remote commands in batch,
assuming scrissh and example.yml are located in the current directory:

`./scrissh example.yml`

Configuration sections
----------------------

* SSH: configures the SSH connection
** keys: defines the SSH key paths
*** private-key: the path to the private key (see important note below)
*** public-key: the path to the public key
* Servers: a list of hosts to connect to
** [server name]: the (random) name of the host to connect to
*** host: the hostname of the host
*** port: the port used by the connection
*** user: the remote user name
*** commands: a list of commands to execute

Note: due to PHP SSH2 native implementation limitations, the private key
must be decrypted, using the following command (replace foo by your username):

`openssl rsa -in /home/foo/.ssh/id_rsa -out /home/foo/.ssh/id_rsa.prv && chmod 400 /home/foo/.ssh/id_rsa.prv`

As this could be a severe security issue, there's a fallback to phpseclib
in case PHP libssh2 bindings aren't available.

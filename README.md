scrissh
=======

A tool written in PHP to execute local and remote commands in batch with SSH
using YAML configuration files.

This project started as an alternative to [Python Fabric](http://docs.fabfile.org/)
and evolved as a lightweight PHP script that allows the execution of multiple commands
on local and remote shells using SSH2 and YAML configuration files.

Requirements
------------

* PHP cli, see http://php.net/manual/en/features.commandline.php
* PHP libssh2 bindings must be installed on the client host.
See http://www.php.net/manual/en/book.ssh2.php for more information.
* A SSH server must be running and listening on the remote host (obviously).

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
        user: root
        commands:
          - remote: apt-get -s upgrade | grep "upgraded,"
            local: echo "server1: $0" >> upgrade-list
      server2:
        host: server2.example.com
        port: 22
        user: root
        commands:
          - remote: apt-get -s upgrade | grep "upgraded,"
            local: echo "server2: $0" >> upgrade-list
          - local: cat upgrade-list | mail -s "Upgrades" foo@example.com; rm upgrade-list

To execute the above remote commands in batch,
assuming scrissh and example.yml are located in the current directory:

`./scrissh example.yml`

This will simulate a package upgrade on both server1 and server2
and email the results to foo@example.com.

Configuration sections
----------------------

    SSH: configures the SSH connection
      keys: defines the SSH key paths
        private-key: the path to the private key (see important note below)
        public-key: the path to the public key
    Servers: a list of hosts to connect to
      [server name]: the name of the host to connect to (can be random)
        host: the hostname of the host
        port: the port used by the connection
        user: the remote user name
        commands: a list of commands to execute
          - remote: a command to be executed on the remote host
                    In case only remote commands are to be executed,
                    the 'remote' key is optional.
          - local: a command to be executed on localhost
                   The string $0 will be replaced by the result
                   of the previous remote command.

Note: due to PHP SSH2 native implementation limitations, the private key
must be decrypted, using the following command (replace foo by your username):

`openssl rsa -in /home/foo/.ssh/id_rsa -out /home/foo/.ssh/id_rsa.prv && chmod 400 /home/foo/.ssh/id_rsa.prv`

As this could be a security issue, there's a fallback (untested) to phpseclib
in case PHP libssh2 bindings aren't available.

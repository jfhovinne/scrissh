scrissh
=======

A tool written in PHP to execute remote commands in batch with SSH.

This project started as an alternative to [Python Fabric](http://docs.fabfile.org/)
and evolved as a lightweight PHP script that allows the execution of multiple commands
on remote shells using SSH2 and YAML configuration files.

Example configuration file: example.yml
---------------------------------------

    SSH:
      keys:
        private-key: /home/foo/.ssh/id_rsa
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

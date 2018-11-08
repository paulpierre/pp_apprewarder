<?php
// phpBB 3.0.x auto-generated configuration file
// Do not change anything in this file!

switch($_SERVER['MODE'])
{
    case "local":
        $dbms = 'mysqli';
        $dbhost = '127.0.0.1';
        $dbport = '3306';
        $dbname = 'apprewarder_forum';
        $dbuser = 'appinviterwrite';
        $dbpasswd = '##########';
        $table_prefix = 'phpbb_';
        $acm_type = 'file';
        $load_extensions = '';
		define('API_HOST','http://m.apprewarder');
    break;

    case "stage":
        $dbms = 'mysqli';
        $dbhost = 'localhost';
        $dbport = '3306';
        $dbname = 'phpbb_forum';
        $dbuser = 'forum';
        $dbpasswd = '##########';
        $table_prefix = 'phpbb_';
        $acm_type = 'file';
        $load_extensions = '';
		define('API_HOST','https://mstage.apprewarder.com');
    break;

    case "prod":
        $dbms = 'mysqli';
        $dbhost = 'localhost';
        $dbport = '3306';
        $dbname = 'phpbb_forum';
        $dbuser = 'forum';
        $dbpasswd = '##########';
        $table_prefix = 'phpbb_';
        $acm_type = 'file';
        $load_extensions = '';
		define('API_HOST','https://m.apprewarder.com');
	break;
}


@define('PHPBB_INSTALLED', true);
// @define('DEBUG', true);
// @define('DEBUG_EXTRA', true);

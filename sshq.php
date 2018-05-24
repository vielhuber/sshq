<?php
if (!isset($argv) || empty($argv) || !isset($argv[1]) || !file_exists('profiles/' . $argv[1] . '.json'))
{
    @unlink('run.bat');
    @unlink('run.sh');
    die('missing profile'.PHP_EOL);
}
$config = json_decode(file_get_contents('profiles/' . $argv[1] . '.json'));

$command = [];

$command[] = 'ssh';

$command[] = '-o TCPKeepAlive=yes';

$command[] = '-o StrictHostKeyChecking=no';

if (isset($config->port))
{
    $command[] = '-p ' . $config->port;
}
else
{
    $command[] = '-p 22';
}

if (isset($config->username))
{
    $command[] = '-l ' . $config->username;
}

if (isset($config->key) && (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || strtoupper(substr(PHP_OS, 0, 3)) === 'CYG'))
{
    $command[] = '-i ' . $config->key;
}

if (isset($config->host))
{
    $command[] = $config->host;
}
else
{
    $command[] = 'localhost ';
}

$command = implode(' ', $command);

// variant 1: here php process runs and there are timeout/performance issues
//passthru($command);

// variant 2: create batch file (which gets executed by original bat file)
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || strtoupper(substr(PHP_OS, 0, 3)) === 'CYG')
{
    file_put_contents('run.bat',"@echo off\n".$command);
}
else
{
    file_put_contents('run.sh',$command);
}

die();
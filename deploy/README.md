
# Automatic deployment for bitbucket.org web-based projects

Based on [«Automated git deployment» script](http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/) by [Jonathan Nicoal](http://jonathannicol.com/). See also [BitBucket Sync](https://bitbucket.org/alixandru/bitbucket-sync) by [alixandru](https://bitbucket.org/alixandru/).

Some fragments of this manual was taken from Jonathan Nicoal's documentation page.

Version 0.0.3
Last changes 2016.03.25

Documentation is in progress.

## Features

- Fixed new bitbucket.org webhooks interface (stream instead of POST). See [discussion](https://bitbucket.org/alixandru/bitbucket-sync/issues/34/bitbucket-api-change-breaks-gatewayphp) on another synchronizator _bitbucket-sync_ by [alixandru](https://bitbucket.org/alixandru/).
- Added support for multiple projects. See array `$PROJECTS` in **config.sample.php**.
- Optional fetching or cloning repositiories demand on their presence.
- Project and repository folders automaticly creating if they're not exists. (You need no to create empty folders before operations and can to reset and initiate full reload by simply removing entire repository/project folders.)
- Post hook command execution.

## Requirements

- PHP 5.3+;
- Git installed;
- Shell access;
- PHP exec function;
- SSH key pair for bitbucket created with **empty** passphrase;

## Installation

For your server to connect securely to Bitbucket without a password prompt, it needs to use an SSH key.

On your server navigate to the **~/.ssh** directory of the user that PHP runs under. You will need to create the user's .ssh directory if it doesn't exist. At a shell prompt type:

```
cd ~/.ssh
ssh-keygen -t rsa
```

When prompted either accept the default key name (**id_rsa**) or give your key a unique name. Press enter when asked for a passphrase, which will generate a passwordless key. Usually this isn't recommended, but we need our script to be able to connect to Bitbucket without a passphrase.

A public and private key pair will be generated. Copy your public key — the one with a _.pub_ extension — to the clipboard. On the Bitbucket website navigate to _Account > SSH Keys_, and choose to add a new key. Paste in your public key and save it.

Back on your server, edit your **~/.ssh/config** file to add _bitbucket.org_ as a host. This ensures that the correct key is used when connecting by SSH to _bitbucket.org_. You'll need to create the config file if it doesn't exist:

```
Host bitbucket.org
    IdentityFile ~/.ssh/bitbucket_rsa
```

Whenever you do a git fetch Bitbucket will verify your identity automatically, without prompting you for a password.

On the Bitbucket website navigate to your repository's _Administration > Webhooks_ screen and add a new webhook, pointed to `http://<domain>/<path>/bitbucket-hook.php`.

For more detailed information see the [original Jonathan's page](http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/), look config file and source code or simply ask me.

## Changes

### 2016.03.25, v.0.0.3
### 2015.10.05, v.0.0.2

- Added project parameter for post deploy execution: `$PROJECTS['repo-name']['postHookCmd']` (see _config.sample.php_). For example, touch _index.wsgi_ for django configuration reloading: `... 'postHookCmd' => 'touch index.wsgi', ...`. Command running in project folder (specified by `deployPath` parameter.

## TODO

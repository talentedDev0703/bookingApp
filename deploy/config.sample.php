<?php

/*{{{ v.151005.001 (0.0.2)

    Sample config file for bitbucket hooks.

    Based on 'Automated git deployment' script by Jonathan Nicoal:
    http://jonathannicol.com/blog/2013/11/19/automated-git-deployments-from-bitbucket/

    See README.md and config.sample.php

    ---
    Igor Lilliputten
    mailto: igor at lilliputten dot ru
    http://lilliputtem.ru/

    Ivan Pushkin
    mailto: iv dot pushk at gmail dot com

}}}*/

// Base tool configuration:
$CONFIG = array(
    'gitCommand'       => '/usr/local/cpanel/3rdparty/lib/path-bin/git',                   // Git command, *REQUIRED*
    'repositoriesPath' => '/home/adriaticsunsets', // Folder containing all repositories, *REQUIRED*
    'log'              => true,                    // Enable logging, optional
    'logFile'          => 'deploy.log',         // Logging file name, optional
    'logClear'         => true,                    // clear log each time, optional
    'verbose'          => true,                    // show debug info in log, optional
    'folderMode'       => 0700,                    // creating folder mode, optional
);

// List of deployed projects:
$PROJECTS = array(
    'nevenduranec/as' => array( // The key is a bitbucket.org repository full name *REQUIRED*
        'master' => array(
            'deployPath'  => '/home/adriaticsunsets/www/app',     // Path to deploy project, *REQUIRED*
            // 'postHookCmd' => '/home/adriaticsunsets/nodevenv/public_html/app/10/bin/npm install && /home/adriaticsunsets/nodevenv/public_html/app/10/bin/npm run build',     // command to execute after deploy, optional
        ),
    )
);

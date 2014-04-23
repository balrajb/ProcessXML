This is just a demo project to transform one form of '.xml' file to another '.xml' file.

You just need to run php process.php it will .xml get files from 'input' directory, process it, validate final output and save .xml files in 'output' directory. And archive the processed .xml files to 'archive' directory.

The code is on github https://github.com/balrajb/ProcessXML

How to use:

1. Get all file on local.
2. Composer (to install) twig (twig lib and template) phpunit (not required on prod):
composer install --no-dev
3. now run
php process.php
or
php process.php dummy

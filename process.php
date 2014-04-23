<?php
require_once __DIR__ . '/vendor/autoload.php';

use Balraj\ProcessXML\User;
use Balraj\ProcessXML\UserPage;

$dummy_run = FALSE;
if (count($argv) > 1) {
  $dummy_run = $argv[1] == 'dummy' ? TRUE : FALSE;
}
process_files($dummy_run);

function process_files($dummy_run = FALSE) {

    // Define input, output and archive directories.
    $input_dir = __DIR__ .'/input';
    $output_dir = array(__DIR__ . '/output');
    $archive_dir = __DIR__ . '/archive';

    // Open input directory.
    if ($handle = opendir($input_dir)) {

         // Loop over the input directory.
        while (false !== ($file = readdir($handle))) {
            $file_path_info = pathinfo($file);

            // Only process .xml files.
            if ($file_path_info['extension'] == 'xml' ) {

                $page_file_path = join('/', array($input_dir, $file));

                $user_page = new UserPage($page_file_path);

                // If SiteConfidence does not exist, jump to next file.
                if (!$user_page->hasSiteConfidence()) {
                    print_r('Missing SiteConfidence in file : ' . $page_file_path);
                    continue;
                }

                // @Todo - This is just for display purpose, so comment out or delete.
                print_r($user_page->getAccountId());
                print_r($user_page->getResponse());
                print_r($user_page->getAccount());

                // Create user object.
                $user = new User();
                $pages = array();

                // Get each element data.
                $user->setAccountId($user_page->getAccountId());
                $user->setResponse($user_page->getResponse());
                $user->setAccount($user_page->getAccount());
                $nitfDOM = $user->getNitfDOM();
                $nitfString = $nitfDOM->saveXML();

                // Validate final outut before writing to file.
                // if (!$nitfDOM->validate()) {
                //     var_dump('ERROR: Validation failed in : ' . $contentsPage->page_file);
                // }

                // Get file name.
                $filename = $user->getFilename();

                // If arrgument passed 'dummy', then dont write/archive files.
                if ($dummy_run) {
                    var_dump('Dummy Run: processed : ' . $filename);
                }
                else {
                    // Write file.
                    write_file($output_dir, $filename, $nitfString);
                    // Archive processed input files.
                    archive_files($input_dir, $archive_dir, $page_file_path);
                }
            }
        }
        closedir($handle);
    }
}

/**
 * Write final xml to a new .xml file, with ISO-8859-1 encoding.
 * @param  array $output_dir
 * @param  string $filename
 * @param  string $nitfString
 */
function write_file($output_dir, $filename, $nitfString) {
    foreach ($output_dir as $output_dir) {
        file_put_contents($output_dir . '/' . $filename , $nitfString);
    }
    var_dump('Written : ' . $filename);
}

/**
 * Move processed files to archive directory.
 * @param  string $input_dir
 * @param  string $archive_dir
 * @param  array $processed_file_path
 */
function archive_files($input_dir, $archive_dir, $processed_file_path) {
    $processed_file_path_array = explode('/',$processed_file_path);
    $processed_file = $processed_file_path_array[count($processed_file_path_array)-1];
    if (rename($input_dir . '/' . $processed_file, $archive_dir . '/' . $processed_file)) {
        var_dump("Archived : " . $processed_file);
    }
    else{
        throw new Exception("There was an error archiving the file!" . $processed_file);
    }
}

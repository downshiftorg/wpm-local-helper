<?php
/*
Plugin Name: ProPhoto Local WP Migrate Helper
Plugin URI: https://github.com/downshiftorg/wpm-local-helper
Author URI: https://pro.photo
Author: ProPhoto
Version: 0.1
Description: Helper plugin for syncing ProPhoto theme uploaded files when using the WP Migrate DB plugin. These ProPhoto files are not in the WordPress media library, and so are not synced by default. NOTE: this plugin only works for <em>syncing locally between two WordPress installs on the same server</em>. It is not designed for migrating between a local and remote environment. Requires PHP 5.3+.
*/


/**
 * Absolute filesystem path to migration SOURCE prophoto `uploads/pp` directory
 *
 * Edit this for your specific filesystem
 */
$srcProPhotoDir = '/staging/wp-content/uploads/pp';

/**
 * Absolute filesystem path to migration TARGET prophoto `uploads/pp` directory
 *
 * Edit this for your specific filesystem
 */
$targetProPhotoDir = '/production/wp-content/uploads/pp';








/**
 * STOP EDITING, YOU SHOULDN'T HAVE TO TOUCH BELOW HERE
 */

add_action('wpmdb_migration_complete', function() use ($srcProPhotoDir, $targetProPhotoDir) {
    $files = [];
    $subDirs = array('images', 'fonts', 'gallery', 'designs', 'placeholders');

    foreach ($subDirs as $subDir) {
        $files = array_merge($files, glob("$srcProPhotoDir/$subDir/*"));
        if (! is_dir("$targetProPhotoDir/$subDir")) {
            mkdir("$targetProPhotoDir/$subDir", 0755);
        }
    }


    foreach ($files as $file) {
        if (basename($file) === 'index.php') {
            continue;
        }

        $relPath = str_replace("$srcProPhotoDir/", '', $file);
        $destPath = "$targetProPhotoDir/$relPath";

        if (file_exists($destPath)) {
            unlink($destPath);
        }

        copy($file, $destPath);
    }
});


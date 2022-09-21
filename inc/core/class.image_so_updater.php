<?php
/*
Projekt: image-source-overlay
Soubor: class.image_so_updater.php
Uživatel: eda
Datum: 21.09.2022
*/
namespace Image_SO\Inc\Core;

final class Image_SO_Updater extends Image_SO_Base
{
    public function __construct()
    {
        parent::__construct();
        Image_SO_Activator::activate();
        $this->updateVersion();
    }

    private function updateVersion() {
        update_option('image_so_version_number', IMAGE_SO__VERSION_NUMBER);
    }
}
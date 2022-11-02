<?php

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
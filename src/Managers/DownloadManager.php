<?php

namespace Wnx\PhotoCrop\Managers;

class DownloadManager
{
    const DOWNLOAD_URL = 'http://www.fmwconcepts.com/imagemagick/downloadcounter.php?scriptname=multicrop&dirname=multicrop';

    const PATH_TO_BINARY = __DIR__ . '/../../bin/multicrop';

    /**
     * Downloads Multicrop Binary if it doesn't exist yet
     * @return void
     */
    public function download() :void
    {
        file_put_contents(
            self::PATH_TO_BINARY,
            file_get_contents(self::DOWNLOAD_URL)
        );

        $this->setPermissions();
    }

    public function doesBinaryExist() :bool
    {
        return file_exists(self::PATH_TO_BINARY);
    }

    public function setPermissions()
    {
        // equals to: `chmod +x`
        chmod(realpath(self::PATH_TO_BINARY), 0755);
    }

}
<?php
/** @noinspection PhpIncludeInspection */

namespace Nikacrm\Core;

class FileStorage
{

    private string $storageFileExt = '.storage';
    private string $storagePath = __DIR__.'/../storage/';

    public function delete($name)
    {
        $fileName = $this->storagePath.DIRECTORY_SEPARATOR.$name.$this->storageFileExt;
        if (file_exists($fileName)) {
            unlink($fileName);
        }
    }

    public function get($name)
    {
        $fileName = $this->storagePath.DIRECTORY_SEPARATOR.$name.$this->storageFileExt;
        if (file_exists($fileName)) {
            return file_get_contents($fileName);
        }

        return false;
    }

    public static function prepare(): FileStorage
    {
        return new self();
    }


    /**
     * @param $name
     * @param $data
     * @return void
     */
    public function set($name, $data = null): void
    {
        $fileName = $this->storagePath.DIRECTORY_SEPARATOR.$name.$this->storageFileExt;
        file_put_contents($fileName, je($data));
    }
}
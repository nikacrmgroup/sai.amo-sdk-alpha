<?php
/** @noinspection PhpIncludeInspection */

namespace Nikacrm\Core;

define('USERS_FILE', 'enums.php');

class Enums
{

    private $enums;
    private string $enumsPath = __DIR__.'/../config/';

    private function __construct($enumsFileName = USERS_FILE)
    {
        $this->enums = $this->getEnums($enumsFileName);
    }

    public function __get($property)
    {
        if (isset($this->enums[$property])) {
            return $this->enums[$property];
        }
        if (property_exists($this, $property)) {
            //todo Exception
            return false;
        }
    }

    public function getAll()
    {
        return $this->enums;
    }

    public static function prepare($enumsFileName = USERS_FILE)
    {
        Container::bind('enums', new self($enumsFileName));

    }

    private function getEnums($enumsFileName, $type = 'php')
    {
        if ($type === 'json') {
            try {
                return json_decode(
                  file_get_contents($this->enumsPath.$enumsFileName),
                  true,
                  512,
                  JSON_THROW_ON_ERROR
                );
            } catch (\JsonException $e) {
                //TODO add error handler
            }
        }
        if ($type === 'php') {
            $file = $this->enumsPath.$enumsFileName;

            return include($file);
        }

        return [];
    }
}
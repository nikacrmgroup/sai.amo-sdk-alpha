<?php
/** @noinspection PhpIncludeInspection */

namespace Nikacrm\Core;

define('TRANSLATE_FILE', 'translations.php');

final class Translate
{

    private $translate;
    private string $translatePath = __DIR__.'/../config/';

    private function __construct($translateFileName = TRANSLATE_FILE)
    {
        //TODO почему 2 раза
        $this->translate = $this->getTranslate($translateFileName);
    }

    public function __get($property)
    {
        if (isset($this->translate[$property])) {
            return $this->translate[$property];
        }
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function getAll()
    {
        return $this->translate;
    }

    public static function prepare($translateFileName = TRANSLATE_FILE)
    {
        Container::bind('translate', new self($translateFileName));
    }

    private function getTranslate($translateFileName, $type = 'php')
    {
        if ($type === 'json') {
            try {
                return json_decode(
                  file_get_contents($this->translatePath.$translateFileName),
                  true,
                  512,
                  JSON_THROW_ON_ERROR
                );
            } catch (\JsonException $e) {
                //TODO add error handler
            }
        }
        if ($type === 'php') {
            $file = $this->translatePath.$translateFileName;

            return include($file);
        }

        return [];
    }
}
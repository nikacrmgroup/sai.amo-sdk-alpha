<?php
/** @noinspection PhpIncludeInspection */

namespace Nikacrm\Core;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigTemplater
{

    public $twig;

    private function __construct()
    {
        $loader = new FilesystemLoader('views/twig');
        $twig   = new Environment($loader, [
            //'cache' => '/views/twig/compilation_cache',
            'debug' => true,
        ]);
        $twig->addExtension(new DebugExtension());
        $twig->addGlobal('publicFolder', public_folder());
        $twig->addGlobal('scriptFolder', script_folder());
        $twig->addGlobal('baseUrl', base_url());

        $this->twig = $twig;
    }

    public function instance(): Environment
    {
        return $this->twig;
    }

    public static function prepare()
    {
        Container::bind('twig', new self());
    }


}
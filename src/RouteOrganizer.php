<?php

class RouteOrganizer
{

    private object $data;


    public static function showError ( string $error )
    {
        self::outputView('error', ['error' => $error]);
    }


    public function __construct()
    {
        $this->data = (object)[];
    }


    public function showAuthDialog(): void
    {
        self::outputView('auth');
    }


    public function showDirectory(): void
    {
        $this->outputView('directory');
    }


    private static function outputView ( string $component, array $data = [] ): void
    {
        ob_start();
        include __DIR__ . "/../views/components/$component.php";
        $content = ob_get_clean();
        include __DIR__."/../views/main.php";
    }


}
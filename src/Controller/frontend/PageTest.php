<?php
namespace App\Controller\frontend;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageTest extends BasePage
{
     private $suckit = "";

    protected function template(): string
    {
        return 'calender.html.twig';
    }

    protected function injectionMap(): array
    {
        return [
            'lol' => $this->suckit
        ];
    }

    protected function preRender(): void
    {
        $this->suckit = "Something fantastic";
    }


    public function index(): Response {
        return $this->renderPage();
    }
}
<?php
namespace App\Controller\frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BasePage extends AbstractController
{
    protected abstract function template(): string;
    protected abstract function injectionMap(): array;

    protected function preRender(): void
    {

    }
    protected function postRender(): void
    {

    }


    protected function renderPage(): Response
    {
        $this->preRender();
        $resp = $this->render($this->template(), $this->injectionMap());
        $this->postRender();
        return $resp;
    }
}
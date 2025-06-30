<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


abstract class AbstractController
{
    protected function render(string $template, array $data) : void
    {
        extract($data); // pas utiliser ça sur des $ get ou des files
        require "templates/layout.phtml";
    }

    protected function redirect(string $route) : void
    {
        header("Location: $route");
    }
}
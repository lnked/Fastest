<?php

class Templater
{
    /** @var array Данные для шаблона */
    public $data = [];

    /** @var string Путь к шаблону */
    public $templatePath = "";

    public function render()
    {
        ob_start();
        ob_implicit_flush(false);
        if ($this->data) extract($this->data);
        require($this->templatePath);
        return ob_get_clean();
    }
}
<?php

namespace App\UrlShort\Interface;


use Symfony\Component\HttpFoundation\Request;

interface CommandInterface
{
public function  runAction(string|array $data);
}
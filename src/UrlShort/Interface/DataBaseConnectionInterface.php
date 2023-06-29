<?php

namespace App\UrlShort\Interface;

interface DataBaseConnectionInterface
{
	public function connectToDB():void;
}
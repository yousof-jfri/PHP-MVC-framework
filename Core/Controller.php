<?php

namespace Core;

abstract class Controller
{
    public function before()
    {
        return true;
    }

    public function after()
    {

    }
}
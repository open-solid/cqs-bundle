<?php

namespace Yceruto\CqsBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class CqrBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}

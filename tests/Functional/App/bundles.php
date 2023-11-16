<?php

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Yceruto\CqsBundle\CqsBundle;

return [
    new FrameworkBundle(),
    new DoctrineBundle(),
    new CqsBundle(),
];

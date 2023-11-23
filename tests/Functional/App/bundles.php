<?php

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use OpenSolid\CqsBundle\CqsBundle;

return [
    new FrameworkBundle(),
    new DoctrineBundle(),
    new CqsBundle(),
];

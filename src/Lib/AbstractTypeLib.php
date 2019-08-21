<?php

namespace Labstag\Lib;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractTypeLib extends AbstractType
{

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }
}

<?php

namespace App\Twig\Runtime;

use cebe\markdown\Markdown;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    private Markdown $markdown;
    public function __construct(Markdown $markdown)
    {
        // Inject dependencies if needed
        $this->markdown= $markdown;
    }

    public function doSomething($value)
    {
        return $this->markdown->parse($value);
    }
}

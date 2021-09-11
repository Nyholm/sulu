<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Component\Webspace\Manager\Dumper;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class WebspaceCollectionDumper
{
    protected function render($template, $parameters)
    {
        //TODO set path in a more elegant way
        $twig = new Environment(new FilesystemLoader(
            __DIR__ . '/../../Resources/skeleton/',
            $this->isLambda() ? '/var/task/' : (\getcwd() . \DIRECTORY_SEPARATOR)
        ));

        return $twig->render($template, $parameters);
    }

    private function isLambda(): bool
    {
        return false !== \getenv('LAMBDA_TASK_ROOT');
    }
}

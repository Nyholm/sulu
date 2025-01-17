<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\SecurityBundle;

use Sulu\Bundle\PersistenceBundle\PersistenceBundleTrait;
use Sulu\Bundle\SecurityBundle\DependencyInjection\Compiler\AccessControlProviderPass;
use Sulu\Bundle\SecurityBundle\DependencyInjection\Compiler\AliasForSecurityEncoderCompilerPass;
use Sulu\Bundle\SecurityBundle\DependencyInjection\Compiler\UserManagerCompilerPass;
use Sulu\Component\Security\Authentication\RoleInterface;
use Sulu\Component\Security\Authentication\RoleSettingInterface;
use Sulu\Component\Security\Authentication\UserInterface;
use Sulu\Component\Security\Authorization\AccessControl\AccessControlInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SuluSecurityBundle extends Bundle
{
    use PersistenceBundleTrait;

    public function build(ContainerBuilder $container)
    {
        $this->buildPersistence(
            [
                UserInterface::class => 'sulu.model.user.class',
                RoleInterface::class => 'sulu.model.role.class',
                RoleSettingInterface::class => 'sulu.model.role_setting.class',
                AccessControlInterface::class => 'sulu.model.access_control.class',
            ],
            $container
        );

        $container->addCompilerPass(new UserManagerCompilerPass());
        $container->addCompilerPass(new AccessControlProviderPass());
        $container->addCompilerPass(new AliasForSecurityEncoderCompilerPass());

        parent::build($container);
    }
}

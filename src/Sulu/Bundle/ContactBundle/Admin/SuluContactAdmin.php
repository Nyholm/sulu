<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\ContactBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Navigation\Navigation;
use Sulu\Bundle\AdminBundle\Navigation\NavigationItem;

class SuluContactAdmin extends Admin
{

    public function __construct()
    {
        $rootNavigationItem = new NavigationItem('Root');

        $rootNavigationItem->setHeaderTitle('Contacts');

        $contacts = new NavigationItem('Contacts');
        $contacts->setIcon('contact-book');
        $rootNavigationItem->addChild($contacts);

        $people = new NavigationItem('People');
        $people->setIcon('parents');
        $people->setType('content');
        $people->setAction('contacts/contacts');
        $contacts->addChild($people);

        $companies = new NavigationItem('Companies');
        $companies->setIcon('bank');
        $companies->setType('content');
        $companies->setAction('contacts/accounts');
        $contacts->addChild($companies);

        $this->setNavigation(new Navigation($rootNavigationItem));
    }

    /**
     * {@inheritdoc}
     */
    public function getCommands()
    {
        return array();
    }

}

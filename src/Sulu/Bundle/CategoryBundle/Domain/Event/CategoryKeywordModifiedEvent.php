<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\CategoryBundle\Domain\Event;

use Sulu\Bundle\CategoryBundle\Admin\CategoryAdmin;
use Sulu\Bundle\CategoryBundle\Entity\CategoryInterface;
use Sulu\Bundle\CategoryBundle\Entity\KeywordInterface;
use Sulu\Bundle\EventLogBundle\Domain\Event\DomainEvent;

class CategoryKeywordModifiedEvent extends DomainEvent
{
    /**
     * @var CategoryInterface
     */
    private $category;

    /**
     * @var KeywordInterface
     */
    private $keyword;

    public function __construct(
        CategoryInterface $category,
        KeywordInterface $keyword
    ) {
        parent::__construct();

        $this->category = $category;
        $this->keyword = $keyword;
    }

    public function getCategory(): CategoryInterface
    {
        return $this->category;
    }

    public function getKeyword(): CategoryInterface
    {
        return $this->category;
    }

    public function getEventType(): string
    {
        return 'keyword_modified';
    }

    public function getEventContext(): array
    {
        return [
            'keywordId' => $this->keyword->getId(),
            'keywordTitle' => $this->keyword->getKeyword(),
        ];
    }

    public function getResourceKey(): string
    {
        return CategoryInterface::RESOURCE_KEY;
    }

    public function getResourceId(): string
    {
        return (string) $this->category->getId();
    }

    public function getResourceLocale(): string
    {
        return $this->keyword->getLocale();
    }

    public function getResourceTitle(): ?string
    {
        $translation = $this->category->findTranslationByLocale($this->keyword->getLocale());

        return $translation ? $translation->getTranslation() : null;
    }

    public function getResourceSecurityContext(): ?string
    {
        return CategoryAdmin::SECURITY_CONTEXT;
    }
}

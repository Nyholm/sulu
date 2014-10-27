<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\SnippetBundle\Content;

use PHPCR\NodeInterface;
use Sulu\Bundle\WebsiteBundle\Resolver\StructureResolverInterface;
use Sulu\Component\Content\ComplexContentType;
use Sulu\Component\Content\Mapper\ContentMapperInterface;
use Sulu\Component\Content\PropertyInterface;
use PHPCR\PropertyType;
use Sulu\Component\Content\ContentTypeInterface;
use PHPCR\Util\UUIDHelper;
use Sulu\Component\Content\Structure\Snippet;

/**
 * ContentType for Snippets
 */
class SnippetContent extends ComplexContentType
{
    /**
     * @var ContentMapperInterface
     */
    protected $contentMapper;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $defaultSnippetType;

    /**
     * @var StructureResolverInterface
     */
    protected $structureResolver;

    /**
     * Constructor
     */
    public function __construct(
        ContentMapperInterface $contentMapper,
        StructureResolverInterface $structureResolver,
        $template,
        $defaultSnippetType
    ) {
        $this->contentMapper = $contentMapper;
        $this->structureResolver = $structureResolver;
        $this->template = $template;
        $this->defaultSnippetType = $defaultSnippetType;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return ContentTypeInterface::PRE_SAVE;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set data to given property
     */
    protected function setData(
        $refs,
        PropertyInterface $property,
        $webspaceKey,
        $languageCode
    ) {
        $snippets = array();

        foreach ($refs as $i => $ref) {
            // see https://github.com/jackalope/jackalope/issues/248
            if (UUIDHelper::isUUID($i)) {
                $ref = $i;
            } else {
            }

            $snippets[] = $this->contentMapper->load($ref, $webspaceKey, $languageCode);
        }

        $property->setValue($snippets);
    }

    /**
     * {@inheritdoc}
     */
    public function read(NodeInterface $node, PropertyInterface $property, $webspaceKey, $languageCode, $segmentKey)
    {
        $refs = $node->getPropertyValueWithDefault($property->getName(), array());
        $this->setData($refs, $property, $webspaceKey, $languageCode);
    }

    /**
     * {@inheritdoc}
     */
    public function readForPreview($data, PropertyInterface $property, $webspaceKey, $languageCode, $segmentKey)
    {
        $this->setData($data, $property, $webspaceKey, $languageCode);
    }

    /**
     * {@inheritdoc}
     */
    public function write(
        NodeInterface $node,
        PropertyInterface $property,
        $userId,
        $webspaceKey,
        $languageCode,
        $segmentKey
    ) {
        $snippetReferences = array();
        foreach ((array)$property->getValue() as $value) {
            if ($value instanceof Snippet) {
                $snippetReferences[] = $value->getUuid();
            } elseif (UUIDHelper::isUUID($value)) {
                $snippetReferences[] = $value;
            } else {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Property value must either be a UUID or a Snippet, "%s" given.',
                        gettype($value)
                    )
                );
            }
        }

        $node->setProperty($property->getName(), $snippetReferences, PropertyType::REFERENCE);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(NodeInterface $node, PropertyInterface $property, $webspaceKey, $languageCode, $segmentKey)
    {
        if ($node->hasProperty($property->getName())) {
            $node->getProperty($property->getName())->remove();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultParams()
    {
        return array(
            'defaultSnippetType' => $this->defaultSnippetType
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getViewData(PropertyInterface $property)
    {
        $snippets = $property->getValue();
        $viewData = array();

        foreach ($snippets as $snippet) {
            $resolved = $this->structureResolver->resolve($snippet);
            $viewData[] = $resolved['view'];
        }

        return $viewData;
    }

    /**
     * {@inheritDoc}
     */
    public function getContentData(PropertyInterface $property)
    {
        $snippets = $property->getValue();
        $contentData = array();

        foreach ($snippets as $snippet) {
            $resolved = $this->structureResolver->resolve($snippet);
            $contentData[] = $resolved['content'];
        }

        return $contentData;
    }
}

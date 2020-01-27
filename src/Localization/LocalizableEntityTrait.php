<?php

namespace MyPackage\Localization;

defined('C5_EXECUTE') or die('Access denied.');

trait LocalizableEntityTrait
{
    /**
     * @var \MyPackage\Localization\LocalizedEntityInterface|null
     */
    protected $localizedEntity;

    /**
     * @var string|null
     */
    private $localizedEntityLocale;

    /**
     * {@inheritdoc}
     *
     * @see \MyPackage\Localization\LocalizableEntityInterface::isEntityInitializedFor()
     */
    public function isEntityInitializedFor($forLocale)
    {
        return $forLocale === $this->localizedEntityLocale;
    }

    /**
     * {@inheritdoc}
     *
     * @see \MyPackage\Localization\LocalizableEntityInterface::setLocalizedEntity()
     */
    public function setLocalizedEntity($forLocale, LocalizedEntityInterface $localizedEntity = null)
    {
        $this->localizedEntityLocale = $forLocale;
        $this->localizedEntity = $localizedEntity;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see \MyPackage\Localization\LocalizableEntityInterface::getLocalizedEntity()
     */
    public function getLocalizedEntity()
    {
        return $this->localizedEntity;
    }
}

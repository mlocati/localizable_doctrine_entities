<?php

namespace MyPackage\Localization;

defined('C5_EXECUTE') or die('Access denied.');

interface LocalizableEntityInterface
{
    /**
     * Check if the localization has been loaded for a specific locale.
     *
     * @param string $forLocale
     *
     * @return bool
     */
    public function isEntityInitializedFor($forLocale);

    /**
     * Get all the available localized entities associated to this localizable entity.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|\MyPackage\Localization\LocalizedEntityInterface[]
     */
    public function getLocalizations();

    /**
     * Set the localized entity for a locale.
     *
     * @param string $forLocale
     * @param \MyPackage\Localization\LocalizedEntityInterface|null $localizedEntity
     *
     * @return $this
     */
    public function setLocalizedEntity($forLocale, LocalizedEntityInterface $localizedEntity = null);

    /**
     * Get the localized entity for the current locale.
     *
     * @return \MyPackage\Localization\LocalizedEntityInterface|null
     */
    public function getLocalizedEntity();
}

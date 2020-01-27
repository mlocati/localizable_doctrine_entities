<?php

namespace MyPackage\Localization;

defined('C5_EXECUTE') or die('Access denied.');

interface LocalizedEntityInterface
{
    /**
     * Get the parent localizable entity.
     *
     * @return \MyPackage\Localization\LocalizedEntityInterface
     */
    public function getBaseEntity();

    /**
     * Set the parent localizable entity.
     *
     * @param \MyPackage\Localization\LocalizableEntityInterface $value
     *
     * @return $this
     */
    public function setBaseEntity(LocalizableEntityInterface $value);

    /**
     * Get the associated locale.
     *
     * @return string
     */
    public function getLocale();

    /**
     * Set the associated locale.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setLocale($value);
}

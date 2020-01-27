<?php

namespace MyPackage\Localization;

defined('C5_EXECUTE') or die('Access denied.');

interface LocaleDependentRepositoryInterface
{
    /**
     * The locale code for which we don't have localized data.
     *
     * @var string
     */
    const NEUTRAL_LOCALE_ID = 'en_US';

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getLocalizedRepository();
}

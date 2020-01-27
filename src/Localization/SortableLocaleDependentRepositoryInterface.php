<?php

namespace MyPackage\Localization;

defined('C5_EXECUTE') or die('Access denied.');

interface SortableLocaleDependentRepositoryInterface extends LocaleDependentRepositoryInterface
{
    /**
     * @param \MyPackage\Localization\LocalizableEntityInterface[] $entities
     */
    public function sortEntities(array &$entities);
}

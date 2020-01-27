<?php

namespace MyPackage\Repository;

use Doctrine\ORM\EntityRepository;
use MyPackage\Entity\Item;
use MyPackage\Entity\ItemLocalized;
use MyPackage\Localization\LocaleDependentRepositoryTrait;
use MyPackage\Localization\SortableLocaleDependentRepositoryInterface;
use Punic\Comparer;

defined('C5_EXECUTE') or die('Access denied.');

class ItemRepository extends EntityRepository implements SortableLocaleDependentRepositoryInterface
{
    use LocaleDependentRepositoryTrait;

    /**
     * {@inheritdoc}
     *
     * @see \MyPackage\Localization\LocaleDependentRepositoryInterface::getLocalizedRepository()
     */
    public function getLocalizedRepository()
    {
        if ($this->localizedRepository === null) {
            $this->localizedRepository = $this->getEntityManager()->getRepository(ItemLocalized::class);
        }

        return $this->localizedRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @see \MyPackage\Localization\SortableLocaleDependentRepositoryInterface::sortEntities()
     */
    public function sortEntities(array &$entities)
    {
        $cmp = new Comparer();
        usort($entities, function (Item $a, Item $b) use ($cmp) {
            return $cmp->compare($a->getName(), $b->getName());
        });
    }
}

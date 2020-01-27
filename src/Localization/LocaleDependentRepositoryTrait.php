<?php

namespace MyPackage\Localization;

use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Support\Facade\Application;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ExpressionBuilder;
use Exception;

defined('C5_EXECUTE') or die('Access denied.');

trait LocaleDependentRepositoryTrait
{
    use ApplicationAwareTrait;

    /**
     * @var \Doctrine\ORM\EntityRepository|null
     */
    private $localizedRepository;

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ORM\EntityRepository::find()
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return $this->finalizeLocalizedEntity(parent::find($id, $lockMode, $lockVersion));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ORM\EntityRepository::findBy()
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->finalizeLocalizedEntities(parent::findBy($criteria, $orderBy, $limit, $offset));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ORM\EntityRepository::findOneBy()
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->finalizeLocalizedEntity(parent::findOneBy($criteria, $orderBy));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ORM\EntityRepository::matching()
     */
    public function matching(Criteria $criteria)
    {
        return $this->finalizeLocalizedEntities(parent::matching($criteria));
    }

    /**
     * @param \MyPackage\Localization\LocalizableEntityInterface|null $entity
     *
     * @reurn \MyPackage\Localization\LocalizableEntityInterface|null
     */
    public function finalizeLocalizedEntity(LocalizableEntityInterface $entity = null)
    {
        if ($entity !== null) {
            $this->finalizeLocalizedEntities([$entity]);
        }

        return $entity;
    }

    /**
     * @param \MyPackage\Localization\LocalizableEntityInterface|null $entity
     * @param mixed $locale
     *
     * @reurn \MyPackage\Localization\LocalizableEntityInterface|null
     */
    public function finalizeLocalizedEntityFor(LocalizableEntityInterface $entity = null, $locale)
    {
        if ($entity !== null) {
            $this->finalizeLocalizedEntitiesFor([$entity], $locale);
        }

        return $entity;
    }

    /**
     * @param \MyPackage\Localization\LocalizableEntityInterface[]|\Traversable $entities
     *
     * @return \MyPackage\Localization\LocalizableEntityInterface[]|\Traversable
     */
    public function finalizeLocalizedEntities($entities)
    {
        try {
            $locale = $this->getCurrentLocale();
        } catch (Exception $x) {
            return $entities;
        }

        return $this->finalizeLocalizedEntitiesFor($entities, $locale);
    }

    /**
     * @param \MyPackage\Localization\LocalizableEntityInterface[]|\Traversable $entities
     * @param string $locale
     *
     * @return \MyPackage\Localization\LocalizableEntityInterface[]|\Traversable
     */
    public function finalizeLocalizedEntitiesFor($entities, $locale)
    {
        $allEntities = [];
        $entitiesToLoad = [];
        foreach ($entities as $entity) {
            $allEntities[] = $entity;
            if ($entity->isEntityInitializedFor($locale) === false) {
                $entity->setLocalizedEntity($locale, null);
                $entitiesToLoad[] = $entity;
            }
        }
        if (!empty($entitiesToLoad)) {
            $x = new ExpressionBuilder();
            $criteria = Criteria::create()
                ->andWhere($x->in('baseEntity', $entitiesToLoad))
                ->andWhere($x->eq('locale', $locale))
            ;
            $localizedEntities = $this->getLocalizedRepository()->matching($criteria);
            foreach ($localizedEntities as $localizedEntity) {
                $index = array_search($localizedEntity->getBaseEntity(), $entitiesToLoad, true);
                $entitiesToLoad[$index]->setLocalizedEntity($locale, $localizedEntity);
            }
        }
        $sorted = false;
        if ($this instanceof SortableLocaleDependentRepositoryInterface && count($allEntities) > 1) {
            $this->sortEntities($allEntities);
            $sorted = true;
        }

        if ($entities instanceof Collection) {
            if ($sorted) {
                $entities->clear();
                foreach ($allEntities as $entity) {
                    $entities->add($entity);
                }
            }

            return $entities;
        } else {
            return $allEntities;
        }
    }

    /**
     * @return \Concrete\Core\Application\Application
     */
    protected function getApplication()
    {
        if ($this->app === null) {
            $this->app = Application::getFacadeApplication();
        }

        return $this->app;
    }

    /**
     * @return \Concrete\Core\Localization\Localization
     */
    protected function getLocalization()
    {
        return $this->getApplication()->make(Localization::class);
    }

    /**
     * @return string
     */
    protected function getCurrentLocale()
    {
        return $this->getLocalization()->getLocale();
    }

    /**
     * @return string
     */
    protected function getNonNeutralLocaleId()
    {
        $locale = $this->getCurrentLocale();

        return $locale === static::NEUTRAL_LOCALE_ID ? '' : $locale;
    }
}

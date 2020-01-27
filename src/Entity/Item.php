<?php

namespace MyPackage\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use MyPackage\Localization\LocalizableEntityInterface;
use MyPackage\Localization\LocalizableEntityTrait;

defined('C5_EXECUTE') or die('Access denied.');

/**
 * @\Doctrine\ORM\Mapping\Entity(
 *     repositoryClass="MyPackage\Repository\ItemRepository",
 * )
 * @\Doctrine\ORM\Mapping\Table(
 *     name="Items",
 *     options={
 *         "comment": "Items"
 *     }
 * )
 */
class Item implements LocalizableEntityInterface
{
    use LocalizableEntityTrait;

    /**
     * The item identifier.
     *
     * @\Doctrine\ORM\Mapping\Column(type="integer", nullable=false, options={"unsigned": true, "comment": "Item identifier"})
     * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
     * @\Doctrine\ORM\Mapping\Id
     *
     * @var int|null
     */
    protected $id;

    /**
     * The item name.
     *
     * @\Doctrine\ORM\Mapping\Column(type="string", length=100, nullable=false, options={"comment": "Item name"})
     *
     * @var string
     */
    protected $name;

    /**
     * The item description.
     *
     * @\Doctrine\ORM\Mapping\Column(type="text", length=65535, nullable=false, options={"comment": "Item description"})
     *
     * @var string
     */
    protected $description;

    /**
     * The ItemLocalized instances associated to this item.
     *
     * @\Doctrine\ORM\Mapping\OneToMany(targetEntity="MyPackage\Entity\ItemLocalized", mappedBy="baseEntity", orphanRemoval=true, cascade={"all"})
     *
     * @var ArrayCollection|\MyPackage\Entity\ItemLocalized[]
     */
    protected $localizations;

    /**
     * Initialize the instance.
     */
    protected function __construct()
    {
        $this->localizations = new ArrayCollection();
    }

    /**
     * @return static
     */
    public static function create()
    {
        $result = new static();
        $result
            ->setName('')
            ->setDescription('')
        ;

        return $result;
    }

    /**
     * Get the item identifier.
     *
     * @return int|null NULL if not yet saved
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the item name.
     *
     * @param bool $localized
     *
     * @return string
     */
    public function getName($localized = true)
    {
        if ($localized && $this->localizedEntity !== null) {
            $name = (string) $this->localizedEntity->getName();
            if ($name !== '') {
                return $name;
            }
        }

        return $this->name;
    }

    /**
     * Set the item name.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setName($value)
    {
        $this->name = (string) $value;

        return $this;
    }

    /**
     * Get the item description.
     *
     * @param bool $localized
     *
     * @return string
     */
    public function getDescription($localized = true)
    {
        if ($localized && $this->localizedEntity !== null) {
            $description = (string) $this->localizedEntity->getDescription();
            if ($description !== '') {
                return $description;
            }
        }

        return $this->description;
    }

    /**
     * Set the item description.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setDescription($value)
    {
        $this->description = (string) $value;

        return $this;
    }

    /**
     * Get the ItemLocalized instances associated to this item.
     *
     * @return ArrayCollection|\MyPackage\Entity\ItemLocalized[]
     */
    public function getLocalizations()
    {
        return $this->localizations;
    }
}

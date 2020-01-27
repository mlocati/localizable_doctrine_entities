<?php

namespace MyPackage\Entity;

use MyPackage\Localization\LocalizableEntityInterface;
use MyPackage\Localization\LocalizedEntityInterface;

defined('C5_EXECUTE') or die('Access denied.');

/**
 * Localized informations for items.
 *
 * @\Doctrine\ORM\Mapping\Entity()
 * @\Doctrine\ORM\Mapping\Table(
 *     name="ItemsLocalized",
 *     options={
 *         "comment": "Localized informations for items"
 *     }
 * )
 */
class ItemLocalized implements LocalizedEntityInterface
{
    /**
     * The item identifier.
     *
     * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="MyPackage\Entity\Item", inversedBy="localizations")
     * @\Doctrine\ORM\Mapping\JoinColumn(name="item", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @\Doctrine\ORM\Mapping\Id
     *
     * @var \MyPackage\Entity\Item
     */
    protected $baseEntity;

    /**
     * The locale identifier.
     *
     * @\Doctrine\ORM\Mapping\Column(type="string", length=64, nullable=false, options={"comment": "Localized item locale"})
     * @\Doctrine\ORM\Mapping\Id
     *
     * @var string
     */
    protected $locale;

    /**
     * The localized item name.
     *
     * @\Doctrine\ORM\Mapping\Column(type="string", length=100, nullable=false, options={"comment": "Localized item name"})
     *
     * @var string
     */
    protected $name;

    /**
     * The localized item description.
     *
     * @\Doctrine\ORM\Mapping\Column(type="text", length=65535, nullable=false, options={"comment": "Localized item description"})
     *
     * @var string
     */
    protected $description;

    /**
     * Initialize the instance.
     */
    protected function __construct()
    {
    }

    /**
     * @param \MyPackage\Entity\Item $item
     * @param string $locale
     *
     * @return static
     */
    public static function create(Item $item, $locale)
    {
        $result = new static();
        $result
            ->setBaseEntity($item)
            ->setLocale($locale)
            ->setName('')
            ->setDescription('')
        ;

        return $result;
    }

    /**
     * Get the item identifier.
     *
     * @return \MyPackage\Entity\Item
     */
    public function getBaseEntity()
    {
        return $this->baseEntity;
    }

    /**
     * Set the item identifier.
     *
     * @param \MyPackage\Entity\Item $value
     *
     * @return $this
     */
    public function setBaseEntity(LocalizableEntityInterface $value)
    {
        $this->baseEntity = $value;

        return $this;
    }

    /**
     * Get the locale identifier.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set the locale identifier.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setLocale($value)
    {
        $this->locale = (string) $value;

        return $this;
    }

    /**
     * Get the localized item name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the localized item name.
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
     * Get the localized item description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the localized item description.
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
}

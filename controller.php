<?php

namespace Concrete\Package\MyPackage;

use Concrete\Core\Http\ResponseFactoryInterface;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Package\Package;
use Concrete\Core\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use MyPackage\Entity\Item;
use MyPackage\Entity\ItemLocalized;
use Symfony\Component\HttpFoundation\Response;

defined('C5_EXECUTE') or die('Access denied.');

/**
 * The package controller.
 */
class Controller extends Package
{
    /**
     * The package handle.
     *
     * @var string
     */
    protected $pkgHandle = 'my_package';

    /**
     * The package version.
     *
     * @var string
     */
    protected $pkgVersion = '0.0.1';

    /**
     * The minimum concrete5 version.
     *
     * @var string
     */
    protected $appVersionRequired = '8.5.0';

    /**
     * Map folders to PHP namespaces, for automatic class autoloading.
     *
     * @var array
     */
    protected $pkgAutoloaderRegistries = [
        'src' => 'MyPackage',
    ];

    /**
     * {@inheritdoc}
     */
    public function getPackageName()
    {
        return t('My Package');
    }

    /**
     * {@inheritdoc}
     */
    public function getPackageDescription()
    {
        return t('Sample package with localizable Doctrine entities.');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Package\Package::install()
     */
    public function install()
    {
        parent::install();
        $this->app->make(EntityManagerInterface::class)->transactional(function (EntityManagerInterface $em) {
            $item = Item::create()
                ->setName('Untranslated name')
                ->setDescription('Untranslated description')
            ;
            $item->getLocalizations()->add(
                ItemLocalized::create($item, 'it_IT')
                    ->setName('Italian name')
                    ->setDescription('Italian description')
            );
            $em->persist($item);
            $em->flush();
        });
    }

    public function on_start()
    {
        $this->app->make(RouterInterface::class)->registerMultiple([
            '/test1' => [
                function () {
                    $localization = $this->app->make(Localization::class);
                    $localization->setLocale('en_US');
                    $em = $this->app->make(EntityManagerInterface::class);
                    $repo = $em->getRepository(Item::class);
                    $item = $repo->findOneBy([], ['id' => 'DESC']);

                    return $this->app->make(ResponseFactoryInterface::class)->create(
                        'Name in en_US: ' . $item->getName(),
                        Response::HTTP_OK,
                        [
                            'Content-Type' => 'text/plain; charset=' . APP_CHARSET,
                        ]
                    );
                },
            ],
            '/test2' => [
                function () {
                    $localization = $this->app->make(Localization::class);
                    $localization->setLocale('it_IT');
                    $em = $this->app->make(EntityManagerInterface::class);
                    $repo = $em->getRepository(Item::class);
                    $item = $repo->findOneBy([], ['id' => 'DESC']);

                    return $this->app->make(ResponseFactoryInterface::class)->create(
                        'Name in it_IT: ' . $item->getName(),
                        Response::HTTP_OK,
                        [
                            'Content-Type' => 'text/plain; charset=' . APP_CHARSET,
                        ]
                        );
                },
                ],
            ]
        );
    }
}

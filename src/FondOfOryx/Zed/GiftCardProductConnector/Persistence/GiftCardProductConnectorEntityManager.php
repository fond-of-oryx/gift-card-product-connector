<?php

namespace FondOfOryx\Zed\GiftCardProductConnector\Persistence;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyGiftCardProductAbstractConfigurationEntityTransfer;
use Generated\Shared\Transfer\SpyGiftCardProductAbstractConfigurationLinkEntityTransfer;
use Generated\Shared\Transfer\SpyGiftCardProductConfigurationEntityTransfer;
use Generated\Shared\Transfer\SpyGiftCardProductConfigurationLinkEntityTransfer;
use Orm\Zed\GiftCard\Persistence\Base\SpyGiftCardProductConfigurationLink;
use Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfigurationLink;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \FondOfOryx\Zed\GiftCardProductConnector\Persistence\GiftCardProductConnectorPersistenceFactory getFactory()
 */
class GiftCardProductConnectorEntityManager extends AbstractEntityManager implements GiftCardProductConnectorEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param string $pattern
     *
     * @return \Generated\Shared\Transfer\SpyGiftCardProductAbstractConfigurationEntityTransfer
     */
    public function saveGiftCardProductAbstractConfiguration(
        ProductAbstractTransfer $productAbstractTransfer,
        string $pattern
    ): SpyGiftCardProductAbstractConfigurationEntityTransfer {
        $entity = $this->getFactory()
            ->createSpyGiftCardProductAbstractConfigurationQuery()
            ->filterByCodePattern($pattern)
            ->findOneOrCreate();

        $entity->save();

        $entityTransfer = $this->getFactory()
            ->createGiftCardProductAbstractConfigurationMapper()
            ->mapEntityToTransfer($entity, new SpyGiftCardProductAbstractConfigurationEntityTransfer());

        return $entityTransfer
            ->addSpyGiftCardProductAbstractConfigurationLinks(
                $this->createGiftCardProductAbstractConfigurationLink($productAbstractTransfer, $entityTransfer),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\SpyGiftCardProductAbstractConfigurationEntityTransfer $entityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyGiftCardProductAbstractConfigurationLinkEntityTransfer
     */
    protected function createGiftCardProductAbstractConfigurationLink(
        ProductAbstractTransfer $productAbstractTransfer,
        SpyGiftCardProductAbstractConfigurationEntityTransfer $entityTransfer
    ): SpyGiftCardProductAbstractConfigurationLinkEntityTransfer {
        $productAbstractEntity = $this->getFactory()->createProductAbstractQuery()
            ->findOneBySku($productAbstractTransfer->getSku());

        $configurationLink = $this->findGiftCardProductAbstractConfigurationLinkByProductAbstractId($productAbstractEntity);

        if (
            $configurationLink !== null
            && $configurationLink->getFkGiftCardProductAbstractConfiguration() !== $entityTransfer->getIdGiftCardProductAbstractConfiguration()
        ) {
            $this->deleteGiftCardProductAbstractConfigurationLink($configurationLink);
        }

        $entity = $this->getFactory()->createSpyGiftCardProductAbstractConfigurationLinkQuery()
            ->filterByFkGiftCardProductAbstractConfiguration($entityTransfer->getIdGiftCardProductAbstractConfiguration())
            ->filterByFkProductAbstract($productAbstractEntity->getIdProductAbstract())
            ->findOneOrCreate();

        $entity->save();

        return $this->getFactory()
            ->createGiftCardProductAbstractConfigurationLinkMapper()
            ->mapEntityToTransfer(
                $entity,
                new SpyGiftCardProductAbstractConfigurationLinkEntityTransfer(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param int $value
     *
     * @return \Generated\Shared\Transfer\SpyGiftCardProductConfigurationEntityTransfer
     */
    public function saveGiftCardProductConfiguration(
        ProductConcreteTransfer $productConcreteTransfer,
        int $value
    ): SpyGiftCardProductConfigurationEntityTransfer {
        $entity = $this->getFactory()
            ->createSpyGiftCardProductConfigurationQuery()
            ->filterByValue($value)
            ->findOneOrCreate();

        $entity->save();

        $entityTransfer = $this->getFactory()
            ->createGiftCardProductConfigurationMapper()
            ->mapEntityToTransfer($entity, new SpyGiftCardProductConfigurationEntityTransfer());

        return $entityTransfer
            ->addSpyGiftCardProductConfigurationLinks(
                $this->createGiftCardProductConfigurationLink($productConcreteTransfer, $entityTransfer),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\SpyGiftCardProductConfigurationEntityTransfer $entityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyGiftCardProductConfigurationLinkEntityTransfer
     */
    protected function createGiftCardProductConfigurationLink(
        ProductConcreteTransfer $productConcreteTransfer,
        SpyGiftCardProductConfigurationEntityTransfer $entityTransfer
    ): SpyGiftCardProductConfigurationLinkEntityTransfer {
        $productEntity = $this->getFactory()->createProductQuery()
            ->findOneBySku($productConcreteTransfer->getSku());

        $configurationLink = $this->findGiftCardProductConfigurationLinkByProductId($productEntity);

        if (
            $configurationLink !== null
            && $configurationLink->getFkGiftCardProductConfiguration() !== $entityTransfer->getIdGiftCardProductConfiguration()
        ) {
            $this->deleteGiftCardProductConfigurationLink($configurationLink);
        }

        $entity = $this->getFactory()->createSpyGiftCardProductConfigurationLinkQuery()
            ->filterByFkGiftCardProductConfiguration($entityTransfer->getIdGiftCardProductConfiguration())
            ->filterByFkProduct($productEntity->getIdProduct())
            ->findOneOrCreate();

        $entity->save();

        return $this->getFactory()
            ->createGiftCardProductConfigurationLinkMapper()
            ->mapEntityToTransfer(
                $entity,
                new SpyGiftCardProductConfigurationLinkEntityTransfer(),
            );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfigurationLink|null
     */
    private function findGiftCardProductAbstractConfigurationLinkByProductAbstractId(
        SpyProductAbstract $productAbstractEntity
    ): ?SpyGiftCardProductAbstractConfigurationLink {
        return $this->getFactory()->createSpyGiftCardProductAbstractConfigurationLinkQuery()
            ->filterByFkProductAbstract($productAbstractEntity->getIdProductAbstract())
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Orm\Zed\GiftCard\Persistence\Base\SpyGiftCardProductConfigurationLink|null
     */
    private function findGiftCardProductConfigurationLinkByProductId(
        SpyProduct $productEntity
    ): ?SpyGiftCardProductConfigurationLink {
        return $this->getFactory()->createSpyGiftCardProductConfigurationLinkQuery()
            ->filterByFkProduct($productEntity->getIdProduct())
            ->findOne();
    }

    /**
     * @param \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfigurationLink $configurationLink
     *
     * @return int
     */
    protected function deleteGiftCardProductAbstractConfigurationLink(
        SpyGiftCardProductAbstractConfigurationLink $configurationLink
    ): int {
        return $this->getFactory()->createSpyGiftCardProductAbstractConfigurationLinkQuery()
            ->filterByIdGiftCardProductAbstractConfigurationLink(
                $configurationLink->getIdGiftCardProductAbstractConfigurationLink(),
            )
            ->delete();
    }

    /**
     * @param \Orm\Zed\GiftCard\Persistence\Base\SpyGiftCardProductConfigurationLink $configurationLink
     *
     * @return int
     */
    protected function deleteGiftCardProductConfigurationLink(
        SpyGiftCardProductConfigurationLink $configurationLink
    ): int {
        return $this->getFactory()->createSpyGiftCardProductConfigurationLinkQuery()
            ->filterByIdGiftCardProductConfigurationLink(
                $configurationLink->getIdGiftCardProductConfigurationLink(),
            )
            ->delete();
    }
}

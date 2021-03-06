<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Inventory\Model\SourceItem\Command\SourceItemsSave;
use Magento\InventoryApi\Api\Data\SourceItemInterface;
use Magento\InventoryApi\Api\SourceItemRepositoryInterface;
use Magento\TestFramework\Helper\Bootstrap;

/** @var SourceItemRepositoryInterface $sourceItemRepository */
$sourceItemRepository = Bootstrap::getObjectManager()->create(SourceItemRepositoryInterface::class);

$searchCriteriaBuilder = Bootstrap::getObjectManager()->create(SearchCriteriaBuilder::class);
$searchCriteria = $searchCriteriaBuilder
    ->addFilter(SourceItemInterface::SKU, ['simple_10', 'simple_20'], 'in')
    ->addFilter(SourceItemInterface::SOURCE_CODE, 'us-1')
    ->create();

$sourceItems = $sourceItemRepository->getList($searchCriteria)->getItems();
$sourceItemsToSave = [];
foreach ($sourceItems as $sourceItem) {
    $sourceItem->setQuantity(100);
    $sourceItem->setStatus(SourceItemInterface::STATUS_IN_STOCK);
    $sourceItemsToSave[] = $sourceItem;
}

/** @var SourceItemsSave $sourceItemSave */
$sourceItemSave = Bootstrap::getObjectManager()->create(SourceItemsSave::class);
$sourceItemSave->execute($sourceItemsToSave);

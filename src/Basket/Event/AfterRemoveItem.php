<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Storefront\Basket\Event;

use TheCodingMachine\GraphQLite\Types\ID;

final class AfterRemoveItem extends AbstractItemEvent implements BasketModifyInterface
{

    /** @var ID */
    protected $basketItemId;

    public function __construct(
        ID $basketId,
        ID $basketItemId,
        float $amount
    ) {
        $this->basketItemId = $basketItemId;
        parent::__construct($basketId, $amount);
    }

    public function getBasketItemId(): ID
    {
        return $this->basketItemId;
    }
}

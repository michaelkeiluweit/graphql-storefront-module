<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Storefront\Country\Service;

use OxidEsales\GraphQL\Base\DataType\Pagination\Pagination as PaginationFilter;
use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Storefront\Country\DataType\Country as CountryDataType;
use OxidEsales\GraphQL\Storefront\Country\DataType\CountryFilterList;
use OxidEsales\GraphQL\Storefront\Country\DataType\CountrySorting;
use OxidEsales\GraphQL\Storefront\Country\Exception\CountryNotFound;
use OxidEsales\GraphQL\Storefront\Shared\Service\AbstractActiveFilterService;
use TheCodingMachine\GraphQLite\Types\ID;

final class Country extends AbstractActiveFilterService
{
    /**
     * @throws InvalidLogin
     * @throws CountryNotFound
     */
    public function country(ID $id): CountryDataType
    {
        try {
            /** @var CountryDataType $country */
            $country = $this->repository->getById(
                (string)$id,
                CountryDataType::class,
                false
            );
        } catch (NotFound $e) {
            throw CountryNotFound::byId((string)$id);
        }

        if ($country->isActive()) {
            return $country;
        }

        if (!$this->authorizationService->isAllowed($this->getInactivePermission())) {
            throw new InvalidLogin('Unauthorized');
        }

        return $country;
    }

    /**
     * @return CountryDataType[]
     */
    public function countries(
        CountryFilterList $filter,
        CountrySorting $sorting
    ): array {
        $this->setActiveFilter($filter);

        return $this->repository->getList(
            CountryDataType::class,
            $filter,
            new PaginationFilter(),
            $sorting
        );
    }

    protected function getInactivePermission(): string
    {
        return 'VIEW_INACTIVE_COUNTRY';
    }
}

<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Accounts\GetContactRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class AccountsResource extends BaseResource
{
    /**
     * Get a single contact (customer, supplier, etc.) by ID.
     *
     * Suppliers in PlentyONE are stored as contacts with typeId = 4.
     *
     * @param  int          $contactId  The contact ID
     * @param  string|null  $with       Comma-separated list of relations: addresses, accounts, options
     *
     * @see https://developers.plentymarkets.com/en-gb/plentymarkets-rest-api/index.html#/Account/get_rest_accounts_contacts__contactId_
     */
    public function getContact(int $contactId, ?string $with = null): Response
    {
        return $this->connector->send(new GetContactRequest($contactId, $with));
    }
}

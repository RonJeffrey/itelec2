<?php

declare(strict_types=1);

/*
 * PaypalServerSdkLib
 *
 * This file was automatically generated by APIMATIC v3.0 ( https://www.apimatic.io ).
 */

namespace PaypalServerSdkLib\Models;

use stdClass;

/**
 * The details about a saved PayPal Wallet payment source.
 */
class PaypalWalletVaultResponse implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $status;

    /**
     * @var LinkDescription[]|null
     */
    private $links;

    /**
     * @var PaypalWalletCustomer|null
     */
    private $customer;

    /**
     * Returns Id.
     * The PayPal-generated ID for the saved payment source.
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets Id.
     * The PayPal-generated ID for the saved payment source.
     *
     * @maps id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * Returns Status.
     * The vault status.
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Sets Status.
     * The vault status.
     *
     * @maps status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * Returns Links.
     * An array of request-related HATEOAS links.
     *
     * @return LinkDescription[]|null
     */
    public function getLinks(): ?array
    {
        return $this->links;
    }

    /**
     * Sets Links.
     * An array of request-related HATEOAS links.
     *
     * @maps links
     *
     * @param LinkDescription[]|null $links
     */
    public function setLinks(?array $links): void
    {
        $this->links = $links;
    }

    /**
     * Returns Customer.
     * The details about a customer in PayPal's system of record.
     */
    public function getCustomer(): ?PaypalWalletCustomer
    {
        return $this->customer;
    }

    /**
     * Sets Customer.
     * The details about a customer in PayPal's system of record.
     *
     * @maps customer
     */
    public function setCustomer(?PaypalWalletCustomer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * Encode this object to JSON
     *
     * @param bool $asArrayWhenEmpty Whether to serialize this model as an array whenever no fields
     *        are set. (default: false)
     *
     * @return array|stdClass
     */
    #[\ReturnTypeWillChange] // @phan-suppress-current-line PhanUndeclaredClassAttribute for (php < 8.1)
    public function jsonSerialize(bool $asArrayWhenEmpty = false)
    {
        $json = [];
        if (isset($this->id)) {
            $json['id']       = $this->id;
        }
        if (isset($this->status)) {
            $json['status']   = PaypalWalletVaultStatus::checkValue($this->status);
        }
        if (isset($this->links)) {
            $json['links']    = $this->links;
        }
        if (isset($this->customer)) {
            $json['customer'] = $this->customer;
        }

        return (!$asArrayWhenEmpty && empty($json)) ? new stdClass() : $json;
    }
}

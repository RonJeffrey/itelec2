<?php

declare(strict_types=1);

/*
 * PaypalServerSdkLib
 *
 * This file was automatically generated by APIMATIC v3.0 ( https://www.apimatic.io ).
 */

namespace PaypalServerSdkLib\Models\Builders;

use Core\Utils\CoreHelper;
use PaypalServerSdkLib\Models\VaultedDigitalWalletShippingDetails;
use PaypalServerSdkLib\Models\VaultVenmoExperienceContext;
use PaypalServerSdkLib\Models\VaultVenmoRequest;

/**
 * Builder for model VaultVenmoRequest
 *
 * @see VaultVenmoRequest
 */
class VaultVenmoRequestBuilder
{
    /**
     * @var VaultVenmoRequest
     */
    private $instance;

    private function __construct(VaultVenmoRequest $instance)
    {
        $this->instance = $instance;
    }

    /**
     * Initializes a new vault venmo request Builder object.
     */
    public static function init(): self
    {
        return new self(new VaultVenmoRequest());
    }

    /**
     * Sets description field.
     */
    public function description(?string $value): self
    {
        $this->instance->setDescription($value);
        return $this;
    }

    /**
     * Sets shipping field.
     */
    public function shipping(?VaultedDigitalWalletShippingDetails $value): self
    {
        $this->instance->setShipping($value);
        return $this;
    }

    /**
     * Sets permit multiple payment tokens field.
     */
    public function permitMultiplePaymentTokens(?bool $value): self
    {
        $this->instance->setPermitMultiplePaymentTokens($value);
        return $this;
    }

    /**
     * Sets usage type field.
     */
    public function usageType(?string $value): self
    {
        $this->instance->setUsageType($value);
        return $this;
    }

    /**
     * Sets customer type field.
     */
    public function customerType(?string $value): self
    {
        $this->instance->setCustomerType($value);
        return $this;
    }

    /**
     * Sets experience context field.
     */
    public function experienceContext(?VaultVenmoExperienceContext $value): self
    {
        $this->instance->setExperienceContext($value);
        return $this;
    }

    /**
     * Initializes a new vault venmo request object.
     */
    public function build(): VaultVenmoRequest
    {
        return CoreHelper::clone($this->instance);
    }
}
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
 * Customizes the payer confirmation experience.
 */
class OrderConfirmApplicationContext implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $brandName;

    /**
     * @var string|null
     */
    private $locale;

    /**
     * @var string|null
     */
    private $returnUrl;

    /**
     * @var string|null
     */
    private $cancelUrl;

    /**
     * @var StoredPaymentSource|null
     */
    private $storedPaymentSource;

    /**
     * Returns Brand Name.
     * Label to present to your payer as part of the PayPal hosted web experience.
     */
    public function getBrandName(): ?string
    {
        return $this->brandName;
    }

    /**
     * Sets Brand Name.
     * Label to present to your payer as part of the PayPal hosted web experience.
     *
     * @maps brand_name
     */
    public function setBrandName(?string $brandName): void
    {
        $this->brandName = $brandName;
    }

    /**
     * Returns Locale.
     * The [language tag](https://tools.ietf.org/html/bcp47#section-2) for the language in which to
     * localize the error-related strings, such as messages, issues, and suggested actions. The tag is made
     * up of the [ISO 639-2 language code](https://www.loc.gov/standards/iso639-2/php/code_list.php), the
     * optional [ISO-15924 script tag](https://www.unicode.org/iso15924/codelists.html), and the [ISO-3166
     * alpha-2 country code](/api/rest/reference/country-codes/) or [M49 region code](https://unstats.un.
     * org/unsd/methodology/m49/).
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * Sets Locale.
     * The [language tag](https://tools.ietf.org/html/bcp47#section-2) for the language in which to
     * localize the error-related strings, such as messages, issues, and suggested actions. The tag is made
     * up of the [ISO 639-2 language code](https://www.loc.gov/standards/iso639-2/php/code_list.php), the
     * optional [ISO-15924 script tag](https://www.unicode.org/iso15924/codelists.html), and the [ISO-3166
     * alpha-2 country code](/api/rest/reference/country-codes/) or [M49 region code](https://unstats.un.
     * org/unsd/methodology/m49/).
     *
     * @maps locale
     */
    public function setLocale(?string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * Returns Return Url.
     * The URL where the customer is redirected after the customer approves the payment.
     */
    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }

    /**
     * Sets Return Url.
     * The URL where the customer is redirected after the customer approves the payment.
     *
     * @maps return_url
     */
    public function setReturnUrl(?string $returnUrl): void
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * Returns Cancel Url.
     * The URL where the customer is redirected after the customer cancels the payment.
     */
    public function getCancelUrl(): ?string
    {
        return $this->cancelUrl;
    }

    /**
     * Sets Cancel Url.
     * The URL where the customer is redirected after the customer cancels the payment.
     *
     * @maps cancel_url
     */
    public function setCancelUrl(?string $cancelUrl): void
    {
        $this->cancelUrl = $cancelUrl;
    }

    /**
     * Returns Stored Payment Source.
     * Provides additional details to process a payment using a `payment_source` that has been stored or is
     * intended to be stored (also referred to as stored_credential or card-on-file).<br/>Parameter
     * compatibility:<br/><ul><li>`payment_type=ONE_TIME` is compatible only with
     * `payment_initiator=CUSTOMER`.</li><li>`usage=FIRST` is compatible only with
     * `payment_initiator=CUSTOMER`.</li><li>`previous_transaction_reference` or
     * `previous_network_transaction_reference` is compatible only with `payment_initiator=MERCHANT`.
     * </li><li>Only one of the parameters - `previous_transaction_reference` and
     * `previous_network_transaction_reference` - can be present in the request.</li></ul>
     */
    public function getStoredPaymentSource(): ?StoredPaymentSource
    {
        return $this->storedPaymentSource;
    }

    /**
     * Sets Stored Payment Source.
     * Provides additional details to process a payment using a `payment_source` that has been stored or is
     * intended to be stored (also referred to as stored_credential or card-on-file).<br/>Parameter
     * compatibility:<br/><ul><li>`payment_type=ONE_TIME` is compatible only with
     * `payment_initiator=CUSTOMER`.</li><li>`usage=FIRST` is compatible only with
     * `payment_initiator=CUSTOMER`.</li><li>`previous_transaction_reference` or
     * `previous_network_transaction_reference` is compatible only with `payment_initiator=MERCHANT`.
     * </li><li>Only one of the parameters - `previous_transaction_reference` and
     * `previous_network_transaction_reference` - can be present in the request.</li></ul>
     *
     * @maps stored_payment_source
     */
    public function setStoredPaymentSource(?StoredPaymentSource $storedPaymentSource): void
    {
        $this->storedPaymentSource = $storedPaymentSource;
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
        if (isset($this->brandName)) {
            $json['brand_name']            = $this->brandName;
        }
        if (isset($this->locale)) {
            $json['locale']                = $this->locale;
        }
        if (isset($this->returnUrl)) {
            $json['return_url']            = $this->returnUrl;
        }
        if (isset($this->cancelUrl)) {
            $json['cancel_url']            = $this->cancelUrl;
        }
        if (isset($this->storedPaymentSource)) {
            $json['stored_payment_source'] = $this->storedPaymentSource;
        }

        return (!$asArrayWhenEmpty && empty($json)) ? new stdClass() : $json;
    }
}

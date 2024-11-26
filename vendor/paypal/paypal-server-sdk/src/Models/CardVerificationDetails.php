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
 * Card Verification details including the authorization details and 3D SECURE details.
 */
class CardVerificationDetails implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $networkTransactionId;

    /**
     * @var string|null
     */
    private $date;

    /**
     * @var string|null
     */
    private $network;

    /**
     * @var string|null
     */
    private $time;

    /**
     * @var Money|null
     */
    private $amount;

    /**
     * @var CardVerificationProcessorResponse|null
     */
    private $processorResponse;

    /**
     * Returns Network Transaction Id.
     * Transaction Identifier as given by the network to indicate a previously executed CIT authorization.
     * Only present when authorization is successful for a verification.
     */
    public function getNetworkTransactionId(): ?string
    {
        return $this->networkTransactionId;
    }

    /**
     * Sets Network Transaction Id.
     * Transaction Identifier as given by the network to indicate a previously executed CIT authorization.
     * Only present when authorization is successful for a verification.
     *
     * @maps network_transaction_id
     */
    public function setNetworkTransactionId(?string $networkTransactionId): void
    {
        $this->networkTransactionId = $networkTransactionId;
    }

    /**
     * Returns Date.
     * The date that the transaction was authorized by the scheme. This field may not be returned for all
     * networks. MasterCard refers to this field as "BankNet reference date".
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * Sets Date.
     * The date that the transaction was authorized by the scheme. This field may not be returned for all
     * networks. MasterCard refers to this field as "BankNet reference date".
     *
     * @maps date
     */
    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    /**
     * Returns Network.
     * The card network or brand. Applies to credit, debit, gift, and payment cards.
     */
    public function getNetwork(): ?string
    {
        return $this->network;
    }

    /**
     * Sets Network.
     * The card network or brand. Applies to credit, debit, gift, and payment cards.
     *
     * @maps network
     */
    public function setNetwork(?string $network): void
    {
        $this->network = $network;
    }

    /**
     * Returns Time.
     * The date and time, in [Internet date and time format](https://tools.ietf.org/html/rfc3339#section-5.
     * 6). Seconds are required while fractional seconds are optional.<blockquote><strong>Note:</strong>
     * The regular expression provides guidance but does not reject all invalid dates.</blockquote>
     */
    public function getTime(): ?string
    {
        return $this->time;
    }

    /**
     * Sets Time.
     * The date and time, in [Internet date and time format](https://tools.ietf.org/html/rfc3339#section-5.
     * 6). Seconds are required while fractional seconds are optional.<blockquote><strong>Note:</strong>
     * The regular expression provides guidance but does not reject all invalid dates.</blockquote>
     *
     * @maps time
     */
    public function setTime(?string $time): void
    {
        $this->time = $time;
    }

    /**
     * Returns Amount.
     * The currency and amount for a financial transaction, such as a balance or payment due.
     */
    public function getAmount(): ?Money
    {
        return $this->amount;
    }

    /**
     * Sets Amount.
     * The currency and amount for a financial transaction, such as a balance or payment due.
     *
     * @maps amount
     */
    public function setAmount(?Money $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * Returns Processor Response.
     * The processor response information for payment requests, such as direct credit card transactions.
     */
    public function getProcessorResponse(): ?CardVerificationProcessorResponse
    {
        return $this->processorResponse;
    }

    /**
     * Sets Processor Response.
     * The processor response information for payment requests, such as direct credit card transactions.
     *
     * @maps processor_response
     */
    public function setProcessorResponse(?CardVerificationProcessorResponse $processorResponse): void
    {
        $this->processorResponse = $processorResponse;
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
        if (isset($this->networkTransactionId)) {
            $json['network_transaction_id'] = $this->networkTransactionId;
        }
        if (isset($this->date)) {
            $json['date']                   = $this->date;
        }
        if (isset($this->network)) {
            $json['network']                = CardBrand::checkValue($this->network);
        }
        if (isset($this->time)) {
            $json['time']                   = $this->time;
        }
        if (isset($this->amount)) {
            $json['amount']                 = $this->amount;
        }
        if (isset($this->processorResponse)) {
            $json['processor_response']     = $this->processorResponse;
        }

        return (!$asArrayWhenEmpty && empty($json)) ? new stdClass() : $json;
    }
}
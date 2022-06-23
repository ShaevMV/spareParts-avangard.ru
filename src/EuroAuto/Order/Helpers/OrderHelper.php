<?php

namespace ApiFacade\EuroAuto\Order\Helpers;

class OrderHelper
{
    public const STATUS_LIST = [
        self::INIT,
        self::CREATED,
        self::CANCELED,
        self::PENDING_APPROVAL,
        self::PENDING_PAYMENT,
        self::PROCESSING,
        self::SHIPPED,
        self::PENDING_RECEIPT,
        self::RECEIVED,
        self::ARCHIVED,
    ];

    public const INIT = 'init';
    public const CREATED = 'created';
    public const CANCELED = 'canceled';
    public const PENDING_APPROVAL = 'pending_approval';
    public const PENDING_PAYMENT = 'pending_payment';
    public const PROCESSING = 'processing';
    public const SHIPPED = 'shipped';
    public const PENDING_RECEIPT = 'pending_receipt';
    public const RECEIVED = 'received';
    public const ARCHIVED = 'archived';

}

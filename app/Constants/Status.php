<?php

namespace App\Constants;

class Status
{

    const ENABLE = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO = 0;

    const VERIFIED = 1;
    const UNVERIFIED = 0;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS = 1;
    const PAYMENT_PENDING = 2;
    const PAYMENT_REJECT = 3;

    const TICKET_OPEN = 0;
    const TICKET_ANSWER = 1;
    const TICKET_REPLY = 2;
    const TICKET_CLOSE = 3;

    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;

    const USER_ACTIVE = 1;
    const USER_BAN = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING = 2;
    const KYC_VERIFIED = 1;

    //user role
    const CUSTOMER = 0;
    const FIELD_TECHNICIAN = 1;

    //admin role
    const ADMIN = 0;
    const SALES_TEAM = 1;
    const DISPATCH_TEAM = 2;

    //work order status
    const PENDING = 1;
    const CONTACTED = 2;
    const CONFIRM = 3;
    const AT_RISK = 4;
    const DELAYED = 5;
    const ON_HOLD = 6;
    const EN_ROUTE = 7;
    const CHECKED_IN = 8;
    const CHECKED_OUT = 9;
    const NEEDS_APPROVAL = 10;
    const ISSUE = 11; // Needs Review
    const APPROVED = 12;
    const INVOICED = 13;
    const PAST_DUE = 14;
    const PAID = 15;

    //work order Type
    const SERVICE = 1;
    const PROJECT = 2;
    const INSTALL = 3;

    //work order stage
    const STAGE_NEW = 1;
    const STAGE_NEED_DISPATCH = 2;
    const STAGE_DISPATCH = 3;
    const STAGE_CLOSED = 4;
    const STAGE_BILLING = 5;
    const STAGE_HOLD = 1;
    const STAGE_CANCEL = 7;
}

<?php

namespace Ycs77\NewebPay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Ycs77\NewebPay\NewebPayMPG payment(string $no, int $amt, string $desc, string $email) 付款
 * @method static \Ycs77\NewebPay\NewebPayCancel creditCancel(string $no, int $amt, string $type = 'order')
 * @method static \Ycs77\NewebPay\NewebPayClose requestPayment(string $no, int $amt, string $type = 'order')
 * @method static \Ycs77\NewebPay\NewebPayClose requestRefund(string $no, int $amt, string $type = 'order')
 * @method static \Ycs77\NewebPay\NewebPayQuery query(string $no, int $amt)
 * @method static \Ycs77\NewebPay\NewebPayCreditCard creditcardFirstTrade(array $data)
 * @method static \Ycs77\NewebPay\NewebPayCreditCard creditcardTradeWithToken(array $data)
 * @method static mixed decode(string $encryptString)
 * @method static mixed decodeFromRequest()
 *
 * @see \Ycs77\NewebPay\NewebPay
 */
class NewebPay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Ycs77\NewebPay\NewebPay::class;
    }
}

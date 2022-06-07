<?php

namespace Ycs77\NewebPay;

use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Throwable;
use Ycs77\NewebPay\Exceptions\NewebpayDecodeFailException;

class NewebPayCVS extends BaseNewebPay
{
    public function boot()
    {
        $this->setApiPath('API/gateway/cvs');
        $this->setAsyncSender();

        $this->setNotifyURL();
    }

    public function trade($amt, $no, $email, $desc = '', ?Carbon $expireDate = null)
    {
        if ($expireDate === null) {
            $expireDate = Carbon::now()->addDays($this->config['ExpireDate'] ?? 7);
        }

        $this->TradeData['P3D'] = false;
        $this->TradeData['MerchantOrderNo'] = $no;
        $this->TradeData['Email'] = $email;
        $this->TradeData['Amt'] = $amt;
        $this->TradeData['ProdDesc'] = $desc;
        $this->TradeData['ExpireDate'] = $expireDate->format('YYYYMMDD');
        unset($this->TradeData['ReturnURL']);

        return $this;
    }

    /**
     * Get request data.
     *
     * @return array
     */
    public function getRequestData()
    {
        $tradeInfo = $this->encryptDataByAES($this->TradeData, $this->HashKey, $this->HashIV);

        return [
            'MerchantID_' => $this->MerchantID,
            'PostData_' => $tradeInfo,
            'Pos_' => $this->config['RespondType'],
        ];
    }
}

<?php

namespace Ycs77\NewebPay;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Request;
use Throwable;
use Ycs77\NewebPay\Exceptions\NewebpayDecodeFailException;

class NewebPayVACC extends BaseNewebPay
{
    public function boot()
    {
        $this->setApiPath('API/gateway/vacc');
        $this->setAsyncSender();

        $this->setNotifyURL();
    }

    public function getSupportedBanks()
    {
        return [
            '004' => 'BOT',
            '812' => 'Taishin',
            '008' => 'HNCB',
        ];
    }

    public function trade($amt, $bankCode, $no, $email, $desc = '', ?Carbon $expireDate = null)
    {
        $map = $this->getSupportedBanks();
        if (!isset($map[$bankCode])) {
            throw new Exception("bank($bankCode) is not suppored");
        }

        if ($expireDate === null) {
            $expireDate = Carbon::now()->addDays($this->config['ExpireDate'] ?? 7);
        }

        $this->TradeData['P3D'] = false;
        $this->TradeData['BankType'] = $map[$bankCode];
        $this->TradeData['MerchantOrderNo'] = $no;
        $this->TradeData['Email'] = $email;
        $this->TradeData['Amt'] = $amt;
        $this->TradeData['ProdDesc'] = $desc;
        $this->TradeData['ExpireDate'] = $expireDate->format('Ymd');
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

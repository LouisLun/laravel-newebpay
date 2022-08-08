<?php

namespace Ycs77\NewebPay;

class NewebPayCreditCard extends BaseNewebPay
{
    /**
     * The newebpay boot hook.
     *
     * @return void
     */
    public function boot()
    {
        $this->setApiPath('API/CreditCard');
        $this->setAsyncSender();

        $this->setP3D(false);
    }

    /**
     * 3d 驗證交易
     *
     * @param  bool  $p3d
     * @return $this
     */
    public function setP3D($p3d = false)
    {
        // 需考慮傳送 notify & return url when p3d is true;
        $this->TradeData['P3D'] = $p3d;

        return $this;
    }

    /**
     * 授權信用卡交易
     * 
     * @param array $data
     *              $data['no'] 訂單id
     *              $data['amt'] 金額
     *              $data['desc'] 商品描述
     *              $data['email'] 信箱
     *              $data['cardNo'] 信用卡
     *              $data['exp'] 信用卡期限
     *              $data['cvc'] 信用卡後三碼
     * 
     */
    public function trade($data)
    {
        $this->TradeData['MerchantOrderNo'] = $data['no'];
        $this->TradeData['Amt'] = $data['amt'];
        $this->TradeData['ProdDesc'] = $data['desc'];
        $this->TradeData['PayerEmail'] = $data['email'];
        $this->TradeData['CardNo'] = $data['cardNo'];
        $this->TradeData['Exp'] = $data['exp'];
        $this->TradeData['CVC'] = $data['cvc'];
        $this->TradeData['Inst'] = $data['inst'] ?? 0;

        return $this;
    }

    /**
     * 分期付款
     * 
     * @param array $data
     *              $data['no'] 訂單id
     *              $data['amt'] 金額
     *              $data['desc'] 商品描述
     *              $data['email'] 信箱
     *              $data['cardNo'] 信用卡
     *              $data['exp'] 信用卡期限
     *              $data['cvc'] 信用卡後三碼
     *              $data['inst'] 分期付款
     */
    public function instTrade($data)
    {
        $this->TradeData['MerchantOrderNo'] = $data['no'];
        $this->TradeData['Amt'] = $data['amt'];
        $this->TradeData['ProdDesc'] = $data['desc'];
        $this->TradeData['PayerEmail'] = $data['email'];
        $this->TradeData['CardNo'] = $data['cardNo'];
        $this->TradeData['Exp'] = $data['exp'];
        $this->TradeData['CVC'] = $data['cvc'];
        $this->TradeData['Inst'] = $data['inst'];

        return $this;
    }

    /**
     * 首次授權信用卡交易
     *
     * @param  array  $data
     * @return $this
     */
    public function firstTrade($data)
    {
        $this->TradeData['TokenSwitch'] = 'get';

        $this->TradeData['MerchantOrderNo'] = $data['no'];
        $this->TradeData['Amt'] = $data['amt'];
        $this->TradeData['ProdDesc'] = $data['desc'];
        $this->TradeData['PayerEmail'] = $data['email'];
        $this->TradeData['CardNo'] = $data['cardNo'];
        $this->TradeData['Exp'] = $data['exp'];
        $this->TradeData['CVC'] = $data['cvc'];
        $this->TradeData['TokenTerm'] = $data['tokenTerm'];

        return $this;
    }

    /**
     * 使用 Token 授權
     *
     * @param  array  $data
     * @return $this
     */
    public function tradeWithToken($data)
    {
        $this->TradeData['TokenSwitch'] = 'on';

        $this->TradeData['MerchantOrderNo'] = $data['no'];
        $this->TradeData['Amt'] = $data['amt'];
        $this->TradeData['ProdDesc'] = $data['desc'];
        $this->TradeData['PayerEmail'] = $data['email'];
        $this->TradeData['TokenValue'] = $data['tokenValue'];
        $this->TradeData['TokenTerm'] = $data['tokenTerm'];

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

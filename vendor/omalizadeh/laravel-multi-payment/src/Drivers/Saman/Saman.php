<?php

namespace Omalizadeh\MultiPayment\Drivers\Saman;

use SoapClient;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Omalizadeh\MultiPayment\RedirectionForm;
use Omalizadeh\MultiPayment\Drivers\Contracts\Driver;
use Omalizadeh\MultiPayment\Exceptions\PaymentFailedException;
use Omalizadeh\MultiPayment\Exceptions\PurchaseFailedException;
use Omalizadeh\MultiPayment\Exceptions\InvalidConfigurationException;
use Omalizadeh\MultiPayment\Receipt;

class Saman extends Driver
{
    public function purchase(): string
    {
        $data = $this->getPurchaseData();
        $headers = $this->getRequestHeaders();
        $response = Http::withHeaders($headers)->post($this->getPurchaseUrl(), $data);
        if ($response->successful()) {
            if ((int) $response['status'] !== $this->getSuccessResponseStatusCode()) {
                throw new PurchaseFailedException($response['errorDesc'], $response['errorCode']);
            }
            $token = $response['token'];
            $this->getInvoice()->setToken($token);
            return $this->getInvoice()->getInvoiceId();
        }
        throw new PurchaseFailedException($response->body(), $response->status());
    }

    public function pay(): RedirectionForm
    {
        $payUrl = $this->getPaymentUrl();
        $data = [
            'Token' => $this->getInvoice()->getToken(),
            'GetMethod' => $this->getCallbackMethod()
        ];

        return $this->redirect($payUrl, $data);
    }

    public function verify(): Receipt
    {
        $statusCode = (int) request('Status');
        if ($statusCode !== $this->getSuccessfulPaymentStatusCode()) {
            throw new PaymentFailedException($this->getStatusMessage($statusCode), $statusCode);
        }

        $data = $this->getVerificationData();
        $soapOptions = $this->getSoapOptions();
        $soap = new SoapClient($this->getVerificationUrl(), $soapOptions);
        $responseCode = (int) $soap->verifyTransaction($data['RefNum'], $data['MerchantID']);
        if ($responseCode < 0) {
            throw new PaymentFailedException($this->getStatusMessage($responseCode), $responseCode);
        }
        $this->getInvoice()->setTransactionId(request('RefNum'));
        $this->getInvoice()->setInvoiceId(request('ResNum'));

        return new Receipt($this->getInvoice(), request('TraceNo'), request('Rrn'), request('SecurePan'));
    }

    protected function getPurchaseData(): array
    {
        if (empty($this->settings['terminal_id'])) {
            throw new InvalidConfigurationException('Terminal id has not been set.');
        }
        $cellNumber = $this->getInvoice()->getPhoneNumber();
        if (!empty($cellNumber)) {
            $cellNumber = $this->checkPhoneNumberFormat($cellNumber);
        }

        return [
            'Action' => 'Token',
            'TerminalId' => $this->settings['terminal_id'],
            'Amount' => $this->getInvoice()->getAmount(),
            'RedirectUrl' => $this->getInvoice()->getCallbackUrl() ?: $this->settings['callback_url'],
            'CellNumber' => $cellNumber,
            'ResNum' => $this->getInvoice()->getInvoiceId(),
        ];
    }

    protected function getVerificationData(): array
    {
        return [
            'RefNum' => request('RefNum', $this->getInvoice()->getTransactionId()),
            'MerchantID' => $this->settings['terminal_id']
        ];
    }

    protected function getStatusMessage($statusCode): string
    {
        $messages = [
            -1 => 'خطا در پردازش اطلاعات ارسالی (مشکل در یکی از ورودی ها و ناموفق بودن فراخوانی متد برگشت تراکنش)',
            -3 => 'ورودیها حاوی کاراکترهای غیرمجاز میباشند.',
            -4 => 'کلمه عبور یا کد فروشنده اشتباه است (Merchant Authentication Failed)',
            -6 => 'سند قبال برگشت کامل یافته است. یا خارج از زمان 30 دقیقه ارسال شده است.',
            -7 => 'رسید دیجیتالی تهی است.',
            -8 => 'طول ورودیها بیشتر از حد مجاز است.',
            -9 => 'وجود کاراکترهای غیرمجاز در مبلغ برگشتی.',
            -10 => 'رسید دیجیتالی به صورت Base64 نیست (حاوی کاراکترهای غیرمجاز است)',
            -11 => 'طول ورودیها کمتر از حد مجاز است.',
            -12 => 'مبلغ برگشتی منفی است.',
            -13 => 'مبلغ برگشتی برای برگشت جزئی بیش از مبلغ برگشت نخورده ی رسید دیجیتالی است.',
            -14 => 'چنین تراکنشی تعریف نشده است.',
            -15 => 'مبلغ برگشتی به صورت اعشاری داده شده است.',
            -16 => 'خطای داخلی سیستم',
            -17 => 'برگشت زدن جزیی تراکنش مجاز نمی باشد.',
            -18 => 'IP Address فروشنده نا معتبر است و یا رمز تابع بازگشتی (reverseTransaction) اشتباه است.',
            1 => 'کاربر انصراف داده است.',
            2 => 'پرداخت با موفقیت انجام شد.',
            3 => 'پرداخت انجام نشد.',
            4 => 'کاربر در بازه زمانی تعیین شده پاسخی ارسال نکرده است.',
            5 => 'پارامترهای ارسالی نامعتبر است.',
            8 => 'آدرس سرور پذیرنده نامعتبر است.',
            10 => 'توکن ارسال شده یافت نشد.',
            11 => 'با این شماره ترمینال فقط تراکنش های توکنی قابل پرداخت هستند.',
            12 => 'شماره ترمینال ارسال شده یافت نشد.',
        ];
        $unknownError = 'خطای ناشناخته رخ داده است.';

        return array_key_exists($statusCode, $messages) ? $messages[$statusCode] : $unknownError;
    }

    protected function getSuccessResponseStatusCode(): int
    {
        return 1;
    }

    protected function getSuccessfulPaymentStatusCode(): int
    {
        return 2;
    }

    protected function getPurchaseUrl(): string
    {
        return 'https://sep.shaparak.ir/MobilePG/MobilePayment';
    }

    protected function getPaymentUrl(): string
    {
        return 'https://sep.shaparak.ir/OnlinePG/OnlinePG';
    }

    protected function getVerificationUrl(): string
    {
        return 'https://verify.sep.ir/Payments/ReferencePayment.asmx?WSDL';
    }

    private function getCallbackMethod()
    {
        if (isset($this->settings['callback_method']) && strtoupper($this->settings['callback_method']) === 'GET') {
            return "true";
        }
        return null;
    }

    private function getSoapOptions(): array
    {
        return config('gateway_saman.soap_options', [
            'encoding' => 'UTF-8'
        ]);
    }

    private function getRequestHeaders(): array
    {
        return config('gateway_saman.request_headers', [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    private function checkPhoneNumberFormat(string $phoneNumber): string
    {
        if (strlen($phoneNumber) === 12 and Str::startsWith($phoneNumber, '98')) {
            return $phoneNumber;
        }
        if (strlen($phoneNumber) === 11 and Str::startsWith($phoneNumber, '0')) {
            return Str::replaceFirst('0', '98', $phoneNumber);
        }
        if (strlen($phoneNumber) === 10) {
            return '98'.$phoneNumber;
        }
        return $phoneNumber;
    }
}

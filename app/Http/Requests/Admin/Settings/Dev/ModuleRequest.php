<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class ModuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'autotrader' => ['nullable', 'array'],
            'autotrader.id' => [new RequiredIf($this->autotrader), 'integer', 'exists:settings,id'],
            'autotrader.value' => [new RequiredIf($this->autotrader), 'boolean'],

            'trade' => ['nullable', 'array'],
            'trade.id' => [new RequiredIf($this->trade), 'integer', 'exists:settings,id'],
            'trade.value' => [new RequiredIf($this->trade), 'boolean'],

            'invest' => ['nullable', 'array'],
            'invest.id' => [new RequiredIf($this->invest), 'integer', 'exists:settings,id'],
            'invest.value' => [new RequiredIf($this->invest), 'boolean'],

            'kyc_verify' => ['nullable', 'array'],
            'kyc_verify.id' => [new RequiredIf($this->kyc_verify), 'integer', 'exists:settings,id'],
            'kyc_verify.value' => [new RequiredIf($this->kyc_verify), 'boolean'],

            'kyc_verify_button' => ['nullable', 'array'],
            'kyc_verify_button.id' => [new RequiredIf($this->kyc_verify_button), 'integer', 'exists:settings,id'],
            'kyc_verify_button.value' => [new RequiredIf($this->kyc_verify_button), 'boolean'],

            'overnight_com' => ['nullable', 'array'],
            'overnight_com.id' => [new RequiredIf($this->overnight_com), 'integer', 'exists:settings,id'],
            'overnight_com.value' => [new RequiredIf($this->overnight_com), 'boolean'],

            'can_trade' => ['nullable', 'array'],
            'can_trade.id' => [new RequiredIf($this->can_trade), 'integer', 'exists:settings,id'],
            'can_trade.value' => [new RequiredIf($this->can_trade), 'boolean'],

            'joint_account' => ['nullable', 'array'],
            'joint_account.id' => [new RequiredIf($this->joint_account), 'integer', 'exists:settings,id'],
            'joint_account.value' => [new RequiredIf($this->joint_account), 'boolean'],

            'separate_bonus' => ['nullable', 'array'],
            'separate_bonus.id' => [new RequiredIf($this->separate_bonus), 'integer', 'exists:settings,id'],
            'separate_bonus.value' => [new RequiredIf($this->separate_bonus), 'boolean'],

            'disable_mobile_view' => ['nullable', 'array'],
            'disable_mobile_view.id' => [new RequiredIf($this->disable_mobile_view), 'integer', 'exists:settings,id'],
            'disable_mobile_view.value' => [new RequiredIf($this->disable_mobile_view), 'boolean'],

            'multi_currency' => ['nullable', 'array'],
            'multi_currency.id' => [new RequiredIf($this->multi_currency), 'integer', 'exists:settings,id'],
            'multi_currency.value' => [new RequiredIf($this->multi_currency), 'boolean'],

            'exchange' => ['nullable', 'array'],
            'exchange.id' => [new RequiredIf($this->exchange), 'integer', 'exists:settings,id'],
            'exchange.value' => [new RequiredIf($this->exchange), 'boolean'],

            'multi_lang' => ['nullable', 'array'],
            'multi_lang.id' => [new RequiredIf($this->multi_lang), 'integer', 'exists:settings,id'],
            'multi_lang.value' => [new RequiredIf($this->multi_lang), 'boolean'],

            'referrals' => ['nullable', 'array'],
            'referrals.id' => [new RequiredIf($this->referrals), 'integer', 'exists:settings,id'],
            'referrals.value' => [new RequiredIf($this->referrals), 'boolean'],

            'auto_profit_lose' => ['nullable', 'array'],
            'auto_profit_lose.id' => [new RequiredIf($this->auto_profit_lose), 'integer', 'exists:settings,id'],
            'auto_profit_lose.value' => [new RequiredIf($this->auto_profit_lose), 'boolean'],
        ];
    }
}

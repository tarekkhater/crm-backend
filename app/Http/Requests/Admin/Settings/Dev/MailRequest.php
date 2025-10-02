<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class MailRequest extends FormRequest
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
            'cannot_trade_msg' => ['nullable', 'array'],
            'cannot_trade_msg.id' => [new RequiredIf($this->cannot_trade_msg), 'integer', 'exists:settings,id'],
            'cannot_trade_msg.value' => [new RequiredIf($this->cannot_trade_msg), 'boolean'],

            'welcome_mail' => ['nullable', 'array'],
            'welcome_mail.id' => [new RequiredIf($this->welcome_mail), 'integer', 'exists:settings,id'],
            'welcome_mail.value' => [new RequiredIf($this->welcome_mail), 'boolean'],

            'admin_newuser_notify' => ['nullable', 'array'],
            'admin_newuser_notify.id' => [new RequiredIf($this->admin_newuser_notify), 'integer', 'exists:settings,id'],
            'admin_newuser_notify.value' => [new RequiredIf($this->admin_newuser_notify), 'boolean'],

            'enable_withdraw_mail' => ['nullable', 'array'],
            'enable_withdraw_mail.id' => [new RequiredIf($this->enable_withdraw_mail), 'integer', 'exists:settings,id'],
            'enable_withdraw_mail.value' => [new RequiredIf($this->enable_withdraw_mail), 'boolean'],

            'suspend_trade_mail' => ['nullable', 'array'],
            'suspend_trade_mail.id' => [new RequiredIf($this->suspend_trade_mail), 'integer', 'exists:settings,id'],
            'suspend_trade_mail.value' => [new RequiredIf($this->suspend_trade_mail), 'boolean'],

            'deposit_mail' => ['nullable', 'array'],
            'deposit_mail.id' => [new RequiredIf($this->deposit_mail), 'integer', 'exists:settings,id'],
            'deposit_mail.value' => [new RequiredIf($this->deposit_mail), 'boolean'],

            'message_email' => ['nullable', 'array'],
            'message_email.id' => [new RequiredIf($this->message_email), 'integer', 'exists:settings,id'],
            'message_email.value' => [new RequiredIf($this->message_email), 'boolean'],

            'plan_upgrade_mail' => ['nullable', 'array'],
            'plan_upgrade_mail.id' => [new RequiredIf($this->plan_upgrade_mail), 'integer', 'exists:settings,id'],
            'plan_upgrade_mail.value' => [new RequiredIf($this->plan_upgrade_mail), 'boolean'],

            'user_activated_mail' => ['nullable', 'array'],
            'user_activated_mail.id' => [new RequiredIf($this->user_activated_mail), 'integer', 'exists:settings,id'],
            'user_activated_mail.value' => [new RequiredIf($this->user_activated_mail), 'boolean'],
        ];
    }
}

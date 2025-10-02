<?php

namespace App\Http\Resources\User\KYC;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class KYCResource extends JsonResource
{
    public function toArray($request)
    {
        $toUrl = fn($p) => $p ? Storage::disk('public')->url($p) : null;

        return [
            'id'                 => $this->id,
            'front_id'           => $this->documents[0]->value,
            'back_id'            => $this->documents[1]->value,
            'front_credit_card'  =>$this->documents[2]->value,
            'back_credit_card'   => $this->documents[3]->value,
            'selfy'              => $this->documents[4]->value, // عمود DB اسمه selfy
            'por'                => $this->documents[5]->value,
            'status'             => $this->status_label ?? $this->statusText(),
        ];
    }

    // بديل بسيط لو ما عندكش Accessor في الموديل
    private function statusText(): string
    {
        return match ((int) $this->status) {
            0       => 'Pending',
            1       => 'Approved',
            default => 'Failed',
        };
    }
}

<?php

namespace App\Http\Controllers\admin\Settings\Dev;

use Illuminate\Http\Request;
use App\Models\PaymentGateWay;
use App\Http\Controllers\Controller;
use App\Services\Settings\UpdateSpecificSettingFromRequest;
use App\Http\Requests\Admin\Settings\Dev\LeadConfigurationRequest;
use Illuminate\Http\JsonResponse;

class LeadConfigurationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): JsonResponse
    {
        $settings = PaymentGateWay::select('id', 'name', 'status', 'min', 'max')->get();
        $this->setData($settings);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\Admin\Settings\Dev\LeadConfigurationRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(LeadConfigurationRequest $request): JsonResponse
    {
        $status = UpdateSpecificSettingFromRequest::update($request);

        if (!$status) {
            return $this->errorResponse("Data cannot be processed.", 422);
        }

        return $this->successResponse('success');
    }
}

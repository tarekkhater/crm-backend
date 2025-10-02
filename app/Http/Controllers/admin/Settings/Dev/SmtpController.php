<?php

namespace App\Http\Controllers\admin\Settings\Dev;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SmtpSettingRequest;
use App\Http\Requests\Admin\Settings\Dev\CrmRequest;
use App\Http\Requests\Admin\Settings\Dev\SmtpRequest;
use App\Services\Settings\UpdateSpecificSettingFromRequest;

class SmtpController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): JsonResponse
    {
        $settings = Setting::select('id', 'key', 'value')->where('page', 'smtp')->get();
        $this->setData($settings);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\Admin\Settings\Dev\SmtpRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(SmtpRequest $request): JsonResponse
    {
        $status = UpdateSpecificSettingFromRequest::update($request);

        if (!$status) {
            return $this->errorResponse("Data cannot be processed.", 422);
        }

        return $this->successResponse('success');
    }
}

<?php

namespace App\Http\Controllers\admin\Settings\Dev;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Dev\CrmRequest;
use App\Services\Settings\UpdateSpecificSettingFromRequest;
use Illuminate\Http\JsonResponse;

class CrmController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): JsonResponse
    {
        $settings = Setting::select('id', 'key', 'value')->where('page', 'crm')->get();
        $this->setData($settings);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\Admin\Settings\Dev\CrmRequest $request
     * @return JsonResponse|mixed
     */
    public function update(CrmRequest $request): JsonResponse
    {
        $status = UpdateSpecificSettingFromRequest::update($request);

        if (!$status) {
            return $this->errorResponse("Data cannot be processed.", 422);
        }

        return $this->successResponse('success');
    }
}

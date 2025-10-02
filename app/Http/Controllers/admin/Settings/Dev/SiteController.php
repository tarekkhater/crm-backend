<?php

namespace App\Http\Controllers\admin\Settings\Dev;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Dev\SiteRequest;
use App\Services\Settings\UpdateSpecificSettingFromRequest;

class SiteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): JsonResponse
    {
        $settings = Setting::select('id', 'key', 'value')->where('page', 'site')->get();
        $this->setData($settings);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\Admin\Settings\Dev\SiteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SiteRequest $request): JsonResponse
    {
        $status = UpdateSpecificSettingFromRequest::update($request);

        if (!$status) {
            return $this->errorResponse("Data cannot be processed.", 422);
        }

        return $this->successResponse('success');
    }
}

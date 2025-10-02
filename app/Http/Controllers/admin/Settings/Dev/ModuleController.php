<?php

namespace App\Http\Controllers\admin\Settings\Dev;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Dev\ModuleRequest;
use App\Services\Settings\UpdateSpecificSettingFromRequest;
use Illuminate\Http\JsonResponse;

class ModuleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): JsonResponse
    {
        $settings = Setting::select('id', 'key', 'value')->where('page', 'module')->get();
        $this->setData($settings);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\Admin\Settings\Dev\ModuleRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(ModuleRequest $request)
    {
        $status = UpdateSpecificSettingFromRequest::update($request);

        if (!$status) {
            return $this->errorResponse("Data cannot be processed.", 422);
        }

        return $this->successResponse('success');
    }
}

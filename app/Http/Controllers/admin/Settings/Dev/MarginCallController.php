<?php

namespace App\Http\Controllers\admin\Settings\Dev;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Dev\MarginCallRequest;
use App\Models\Setting;
use App\Services\Settings\UpdateSpecificSettingFromRequest;

class MarginCallController extends Controller
{
    public function __invoke()
    {
        $settings = Setting::select('id', 'key', 'value')->where('page', 'margin')->get();
        $this->setData($settings);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\Admin\Settings\Dev\MarginCallRequest $request
     * @return void
     */
    public function update(MarginCallRequest $request)
    {
        $status = UpdateSpecificSettingFromRequest::update($request);

        if (!$status) {
            return $this->errorResponse("Data cannot be processed.", 422);
        }

        return $this->successResponse('success');
    }
}

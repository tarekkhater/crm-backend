<?php

namespace App\traits\Admin\Settings\Dev;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\Settings\UpdateSpecificSettingFromRequest;

trait DevSettingUpdater
{
    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(FormRequest $request): JsonResponse
    {
        dd($request->all());
        $status = UpdateSpecificSettingFromRequest::update($request);

        if (!$status) {
            return $this->errorResponse("Data cannot be processed.", 422);
        }

        return $this->successResponse('success');
    }
}

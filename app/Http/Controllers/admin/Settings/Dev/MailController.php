<?php

namespace App\Http\Controllers\admin\Settings\Dev;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Dev\MailRequest;
use App\Services\Settings\UpdateSpecificSettingFromRequest;

class MailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\Admin\Settings\Dev\MailRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(MailRequest $request)
    {
        $status = UpdateSpecificSettingFromRequest::update($request);

        if (!$status) {
            return $this->errorResponse("Data cannot be processed.", 422);
        }

        return $this->successResponse('success');
    }
}

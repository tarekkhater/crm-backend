<?php

namespace App\Services\Settings;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSpecificSettingFromRequest
{
    public static function update(FormRequest $request): bool
    {
        $data = $request->all();

        $values = array_values($data);

        if (empty($values)) {
            return false;
        }

        try {
            foreach ($values as $value) {
                Setting::where("id", $value['id'])->update([
                    "value" => static::setter($value["value"])
                ]);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }

        // return Setting::find($values["id"])->update([
        //     'value' => static::setter($values["value"])
        // ]);
    }

    public static function setter($value)
    {
        if (isBase64Image($value)) {
            return uploadImage($value, 'settings');
        }

        return $value;
    }
}

<?php
namespace App\Services\Settings\Status;
use App\Models\Status;
use Illuminate\Http\Request;
class IndexServices{
    public function all(Request $request){
        $status = Status::all();
        return $status;
    }

    public function show($id){
        $statuss = Status::find($id);
        return $statuss;
    }

    public function store($request){
        $statuss = Status::create([
            'name' => $request->name,
            'status' => $request->status
        ]);
        if ($request->has('icon')) {
            $createData['icon'] = $request->icon;
        }
        $statuss = Status::create($createData);
    return $statuss;
    }
    public function update($request, $id){
        $statuss = Status::find($id);

        if (!$statuss) {
            return false; // أو throw new Exception("Status not found");
        }

        $statuss->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

        // إذا كان النموذج يدعم الصلاحيات
        if (method_exists($statuss, 'syncPermissions')) {
            $statuss->syncPermissions($request->permission);
        }

        return $statuss; // إرجاع النموذج المحدث
    }
    public function destroy($id){
        $status = Status::find($id);

        if (!$status) {
            return false; // أو throw new Exception("Status not found");
        }

        $status->delete();
        return true; // إرجاع نجاح العملية
    }
}

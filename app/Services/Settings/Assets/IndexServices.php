<?php
namespace App\Services\Settings\Assets;
use App\Models\CurrencyPair;
use Illuminate\Http\Request;
class IndexServices{
    public function all(Request $request){
        $query = CurrencyPair::query()->orderedForDisplay();
        if(isset($request->search) && $request->search != ''){
            $query->where('name','LIKE','%'.$request->search.'%');
        }
        return $query->paginate(15);
    }
    
    public function allindex(){
        return CurrencyPair::orderedForDisplay()->get();
    }
    

    
    public function types(){
        $types = CurrencyPair::select('type as name')->groupBy('type')->get();
        $data = [];
        foreach($types as $type){
            $data[] = [
                'value'=>$type->name,
                'label'=>$type->name,
            ];
        }
        return $data;
      
    }
    
    public function getbytype($type){
        return CurrencyPair::where('type', $type)->orderedForDisplay()->get();
    }

    public function updateOrder(string $type, array $orderedIds): void
    {
        $uniqueIds = array_values(array_unique($orderedIds));
        $assets = CurrencyPair::where('type', $type)
            ->whereIn('id', $uniqueIds)
            ->get()
            ->keyBy('id');

        if ($assets->count() !== count($uniqueIds)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'order' => ['One or more assets do not belong to the selected type.'],
            ]);
        }

        foreach ($uniqueIds as $index => $id) {
            CurrencyPair::where('id', $id)->update(['sort_order' => $index + 1]);
        }
    }

    public function show($id){
        $Currencys = CurrencyPair::find($id);
        return $Currencys;
    }

    public function store($request){
        $data = $request->all();
        if( !$request->days || $request->days == null ){
            switch ($request->type) {
                case 'stocks':
                    $defaultDays = ["Monday","Tuesday","Wednesday","Thursday","Friday"];
                    $open_at = "15:00:00";
                    $close_at = "22:00:00";
                    break;
                case 'crypto':
                    $defaultDays = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
                    $open_at = "00:00:00";
                    $close_at = "23:59:59";
                    break;
                case 'commodities':
                    $defaultDays = ["Monday","Tuesday","Wednesday","Thursday","Friday"];
                    $open_at = "00:00:00";
                    $close_at = "23:59:59";
                    break;
                case 'indices':
                    $defaultDays = ["Monday","Tuesday","Wednesday","Thursday","Friday"];
                    $open_at = "09:30:00";
                    $close_at = "22:00:00";
                    break;
                case 'forex':
                    $defaultDays = ["Monday","Tuesday","Wednesday","Thursday","Friday"];
                    $open_at = "00:00:00";
                    $close_at = "23:59:59";
                    break;
                default:
                    $defaultDays = ["Monday","Tuesday","Wednesday","Thursday","Friday"];
                    $open_at = "00:00:00";
                    $close_at = "23:59:59";
                    break;
            }
            $data['open_at'] = $open_at;
            $data['close_at'] = $close_at;
            $data['days'] = json_encode($defaultDays);
        }
        $maxOrder = CurrencyPair::where('type', $request->type)->max('sort_order') ?? 0;
        $data['sort_order'] = $maxOrder + 1;
        $Currencys = CurrencyPair::create($data);
         if(isset($request->image)){
            $Currencys->image = $this->uploadRealImage($request->image, 'Assets');
            $Currencys->save();
        }
    }
    
    
    public function updated($request,$id){
        $Currencys = CurrencyPair::find($id);
        $Currencys->update($request->all());
        if(isset($request->image)){
            $Currencys->image = $this->uploadRealImage($request->image, 'Assets');
            $Currencys->save();
        }

    }
    public function destroy($request){
        $Currencys = CurrencyPair::find($id);
        $Currencys->delete();
    }
    
    
    public function uploadRealImage($file, $path)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('storage/' . $path, $fileName, 'public');
        return $filePath;
    }

}

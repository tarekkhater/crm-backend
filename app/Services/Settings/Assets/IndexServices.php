<?php
namespace App\Services\Settings\Assets;
use App\Models\CurrencyPair;
use Illuminate\Http\Request;
class IndexServices{
    public function all(Request $request){
        $Currencys = CurrencyPair::paginate(15);
        if(isset($request->search) && $request->search != ''){
            $Currencys = CurrencyPair::where('name','LIKE','%'.$request->search.'%')->paginate(15);
        }
        return $Currencys;
    }
    
    public function allindex(){
        $Currencys = CurrencyPair::get();
        return $Currencys;
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
         $Currencys = CurrencyPair::wheretype($type)->get();
        return $Currencys;
    }

    public function show($id){
        $Currencys = CurrencyPair::find($id);
        return $Currencys;
    }

    public function store($request){
        $Currencys = CurrencyPair::create($request->all());
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

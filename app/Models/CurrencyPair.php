<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
class CurrencyPair extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['sy','buy_p','sell_p','label','value'];

    protected $casts = [
        'leverage' => 'integer',
        'disabled' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeOrderedForDisplay($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
protected $attr = ['amount'];

    protected static function booted()
    {
        static::saving(function (CurrencyPair $position) {
            $position->calculateRate();
        });
        
        static::saved(function (CurrencyPair $position) {
            $position->calculateRate();
        });
        
    }
    
    
    public function positions()
{
    return $this->hasMany(Position::class, 'symbol', 'ex_sym');
}


    public function scopeWithOpenTrades($query)
{
    return $query->whereIn('ex_sym', function ($subQuery) {
        $subQuery->select('symbol')
            ->from('positions')
            ->whereNull('close_at');
    });
}
    
    
    public function calculateRate(){
        $controller = new Controller();
        $rate = $controller->getCurRate($this->sym, $this->base, $this->type);
        $this->rate = $rate;
        
        Model::withoutEvents(function () {
            $this->save(); // ✅ لن يطلق أي Events
        });
    }
    
    
    
    
   
   public function getLeverageAttribute($value)
{
    // Legacy plan-based leverage logic (disabled):
    // if ($value > 0) {
    //     if (auth()->check()) {
    //         $user = auth()->user();
    //         $lastActivePlan = $user->userInfo;
    //         if ($lastActivePlan) {
    //             $plan = \App\Models\planLeverag::where('plan_id',$lastActivePlan->plan_id)->where('type',$this->type)->first();
    //             $features = $plan->leverag ?? $value;
    //             return (int)  $features;
    //         }
    //
    //         return $value; // Default if no plan found
    //     }
    //     return $value;
    // }
    //
    // return 1;

    // Force same leverage for all users and all plans.
    return 30;
}


public function getAmountAttribute()
{
    if($this->type !== null){
        $assettype = \App\Models\AssetType::where('title','like',$this->type)->first();
        return $assettype->amount;
    }
}

/**
 * Extract leverage value from features array
 */
/**
 * Extract leverage from features with multiple pattern support
 */
private function extractLeverageFromFeatures($features)
{
    foreach ($features as $feature) {
        $feature = trim($feature);
        
        // Pattern 1: "Leverage 1:100"
        if (preg_match('/Leverage\s+1:(\d+)/i', $feature, $matches)) {
            return (int) $matches[1];
        }
        
        // Pattern 2: "Leverage: 1:100"
        if (preg_match('/Leverage:\s*1:(\d+)/i', $feature, $matches)) {
            return (int) $matches[1];
        }
        
        // Pattern 3: "Max Leverage 1:100"
        if (preg_match('/Max\s+Leverage\s+1:(\d+)/i', $feature, $matches)) {
            return (int) $matches[1];
        }
        
        // Pattern 4: "Leverage up to 1:100"
        if (preg_match('/Leverage\s+up\s+to\s+1:(\d+)/i', $feature, $matches)) {
            return (int) $matches[1];
        }
        
        // Pattern 5: Just numbers after "Leverage"
        if (preg_match('/Leverage.*?(\d+)/i', $feature, $matches)) {
            return (int) $matches[1];
        }
    }
    
    return 10; // Default leverage
}




    public function buyPrice(){
        $value = ($this->buy_spread * $this->rate) / 100;
        return number_format($value + $this->rate, 2);
    }
    public function sellPrice(){
        $value = ($this->sell_spread * $this->rate) / 100;
        return number_format($this->rate -  $value, 2);
    }

    public function getBaseAttribute($value){
        if($value){
            return strtoupper($value);
        }else{
            return 'USD';
        }
    }

    public function getImageAttribute($value) {
        if(!$value) {
            $colors = ['E91E63', '9C27B0', '673AB7', '3F51B5', '0D47A1', '01579B', '00BCD4', '009688', '33691E', '1B5E20', '33691E', '827717', 'E65100',  'E65100', '3E2723', 'F44336', '212121'];
            $background = $colors[$this->id%count($colors)];
            return "https://ui-avatars.com/api/?size=256&background=".$background."&color=fff&name=".urlencode($this->name);
        }
        return asset($value);
    }

    public function getSyAttribute(){
        return $this->sym.'USD';
    }
    
     public function getLabelAttribute(){
        return $this->name;
    }
    
     public function getValueAttribute(){
        return $this->id;
    }
    
    public function getBuyPAttribute(){
        return $this->buyPrice();
    }
    public function getSellPAttribute(){
        return $this->sellPrice();
    }
}

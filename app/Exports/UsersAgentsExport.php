<?php
namespace App\Exports;

use App\Models\Admin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\InfoTradeUser;

class UsersAgentsExport implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;

    /**
     * Return the collection of users for export.
     *
     * @return \Illuminate\Support\Collection
     */
     
     protected $type;

    public function __construct($type)
    {
        $this->type = $type; // Store request data if needed for further use
    }
    
   
        // Filter users based on the type
        public function collection()
    {
        
        $users = match ($this->type) {
                '0' => $this->teamLeaderSales(),
                '1' => $this->AgentSales(),
                '2' => $this->teamLeaderRetenation(),
                '3' => $this->AgentRetenation(),
                '4' => $this->AgentAdmin(),
                default => $this->teamLeaderSales(),
            };
        
        return $users;
    }

    /**
     * Set the title of the sheet.
     *
     * @return string
     */
    // public function title(): string
    // {
    //     return 'Clients';
    // }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return ["ID", "Name", "Surname", "Email", "Phone"];
    }
    public function title(): string
    {
        // Return the sheet title based on the type
        $typeTitles = [
            0 => 'Teamleader Sales',
            1 => 'Agent Sales',
            2 => 'Teamleader retenation',
            3 => 'Agent retenation',
        ];

        return $typeTitles[$this->type] ?? 'Users';
    }
    
    
    
    public function teamLeaderSales(){
                $ids = getTeamLeaderIds();
                $users = Admin::whereIn('id',$ids)->where('type_id',6)->where('sub_type_id',7)->get();
        return $users;
    }
    
     public function teamLeaderRetenation(){

                $ids = getTeamLeaderIds();
                $users = Admin::whereIn('id',$ids)->where('type_id',6)->where('sub_type_id',8)->get();
        return $users;
    }
    
    
     public function AgentSales(){
            $ids = getAgentsIds();
           if(auth()->user()->type_id != 6){
            $users = Admin::with(['teamleader'])->whereIn('id',$ids)->where('type_id',7)->get();
           
            }else{
                $users = Admin::with(['teamleader'])->whereIn('id',$ids)->where('type_id',7)->get();
                
            } 
        return $users;
    }
    
     public function AgentRetenation(){
            $ids = getAgentsIds();
               if (auth()->user()->type_id != 6) {
                $users = Admin::with(['teamleader'])->whereIn('id', $ids)->where('type_id', 8)->get();
               
            } else {
                $users = Admin::with(['teamleader'])->whereIn('id', $ids)->where('type_id', 8)->get();
                
            }
        return $users;
    }
    
    public function AgentAdmin(){
        $users = Admin::where('type_id', 1)->get();
        return $users;
    }
    
    
    
    
}

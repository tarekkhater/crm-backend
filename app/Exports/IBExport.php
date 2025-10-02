<?php
namespace App\Exports;

use App\Models\Admin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\InfoTradeUser;

class IBExport implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;

    /**
     * Return the collection of users for export.
     *
     * @return \Illuminate\Support\Collection
     */
     
     protected $getusers;

    public function __construct($users)
    {
        $this->getusers = $users; // Store request data if needed for further use
    }
    
   
        // Filter users based on the type
        public function collection()
    {
        $users = $this->getusers;

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
       

        return  'IB';
    }
    
    
    
 
    
    
}

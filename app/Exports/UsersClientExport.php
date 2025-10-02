<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\InfoTradeUser;
use App\Models\AssignUserManager;

class UsersClientExport implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;

    protected $type;

    public function __construct($type)
    {
        $this->type = $type; // Store request data if needed for further use
    }
    
    // Filter users based on the type
    public function collection()
    {
        switch ($this->type) {
            case 0:
                return $this->leads(); 
            case 10:
                return $this->leadsCenter(); 
            case 1:
                return $this->potinal();    
            case 2:
                return $this->Active();    
            case 5:
                return $this->publicCustomer();    
            case 9:
                return $this->ArchiveCustomer();    
            default:
                return $this->FTD();  
        }
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return ["id", "name", "surname", "email", "phone", "country", 'address'];
    }

    public function title(): string
    {
        // Return the sheet title based on the type
        $typeTitles = [
            '0' => 'leads',
            '1' => 'potinal',
            '2' => 'Active',
            '4' => 'FTD',
            '5' => 'publicCustomer',
            '9' => 'ArchiveCustomer',
        ];

        return $typeTitles[$this->type] ?? 'Users';
    }

    // Method to get leads
    public function leads()
    {
        $idspotinal = InfoTradeUser::whereIn('user_id', getUsersIds())->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager', 'userInfo', 'countries'])->where('type_id', 1)->orderByDesc('created_at')->get();

        return $this->transformUsers($users);
    }

    // Method to get active users
    public function Active()
    {
        $id = AssignUserManager::whereIn('user_id', getUsersIds())->where('admin_id', '<>', 0)->pluck('user_id');
        $idspotinal = InfoTradeUser::whereIn('user_id', $id)->where('status_id', '<>', 4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager', 'userInfo', 'countries'])->where('type_id', 2)->orderByDesc('created_at')->get();

        return $this->transformUsers($users);
    }

    // Method to get potential users
    public function potinal()
    {
        $idspotinal = InfoTradeUser::whereIn('user_id', getUsersIds())->where('status_id', 4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager', 'userInfo', 'countries'])->where('type_id', 2)->orderByDesc('created_at')->get();

        return $this->transformUsers($users);
    }

    // Method to get archived users
    public function ArchiveCustomer()
    {
        $idspotinal = InfoTradeUser::whereIn('user_id', getUsersIds())->where('status_id', '<>', 4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager', 'userInfo', 'countries'])->where('type_id', 9)->orderByDesc('created_at')->get();

        return $this->transformUsers($users);
    }

    // Method to get FTD users
    public function FTD()
    {
        $idspotinal = InfoTradeUser::whereIn('user_id', getUsersIds())->where('status_id', '<>', 4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->where('type_id', 2)->where('depositedAcount', 1)->with(['Manager', 'userInfo', 'countries'])->orderByDesc('created_at')->get();

        return $this->transformUsers($users);
    }

    // Method to get public customers
    public function publicCustomer()
    {
        $id = AssignUserManager::where('admin_id', '<>', 0)->pluck('user_id');
        $idspotinal = InfoTradeUser::whereIn('user_id', getUsersIds())->whereNotIn('user_id', $id)->where('status_id', '<>', 4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager', 'userInfo', 'countries'])->where('type_id', 2)->orWhere('type_id', 5)->orderBy('created_at')->get();

        return $this->transformUsers($users);
    }

    // Helper method to transform user data
    private function transformUsers($users)
    {
        $data = [];
        foreach ($users as $user) {
            $phonecode = $user->countries->phonecode ?? '-';
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'email' => $user->email,
                'phone' => '(+' . $phonecode . ')' . $user->phone,
                'country' => $user->countries->name ?? '-',
                'address' => $user->address,
            ];
        }
        return collect($data); // Return as collection for export
    }
}

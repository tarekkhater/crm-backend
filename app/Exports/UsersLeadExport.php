<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UsersLeadExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request; // Store request data if needed for further use
    }

    public function collection()
    {
        // Query users with 'depositedAcount' equal to 0 and fetch specific columns
        $users = User::where('depositedAcount', '0')
                    ->select(['id', 'name', 'email', 'phone', 'depositedAcount'])
                    ->get();

        // Log data to verify if the correct records are being fetched
        Log::info('Users fetched for export:', $users->toArray());

        return $users;
    }

    public function headings(): array
    {
        // Define headers to match the selected fields
        return ['ID', 'Name', 'Email', 'Phone', 'Deposited Account'];
    }
}


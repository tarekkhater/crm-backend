<?php
namespace App\Exports;

use App\Models\TradingAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;

class TradingAccountExport implements FromCollection, WithTitle
{
    use Exportable;

    /**
     * Returns a collection of trading accounts for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Assuming `getUsersIds()` returns an array of user IDs
        $ids = getUsersIds();
        return TradingAccount::whereIn('user_id', $ids)->get();
    }

    /**
     * Set the title of the sheet.
     *
     * @return string
     */
    public function title(): string
    {
        return 'Clients';
    }
}

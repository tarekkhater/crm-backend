<?php
namespace App\Exports;

use App\Models\Deposit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class DepositExport implements FromCollection, WithTitle, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * Get the collection of deposits for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Get the user IDs for filtering
        $ids = getUsersIds(); // Ensure `getUsersIds()` returns an array of IDs
        return Deposit::whereIn('user_id', $ids)->with(['user'])->get();
    }

    /**
     * Map each row to the specific columns required for export.
     *
     * @param \App\Models\Deposit $deposit
     * @return array
     */
    public function map($deposit): array
    {
        return [
            $deposit->id,
            $deposit->created_at->format('Y-m-d'), // Format date as needed
            $deposit->user->email ?? 'N/A',        // Email from related User model
            $deposit->user->country ?? 'N/A',      // Country from related User model
            $deposit->user->phone ?? 'N/A',        // Phone from related User model
            $deposit->amount,
            $deposit->currency,
            $deposit->proof ?? 'N/A',              // Proof of deposit if available
            $deposit->details ?? 'N/A',            // Additional details if available
            $deposit->status,
        ];
    }

    /**
     * Define the column headings.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Email',
            'Country',
            'Number',
            'Amount',
            'Currency',
            'Proof',
            'Details',
            'Status',
        ];
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

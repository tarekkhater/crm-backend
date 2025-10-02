<?php
namespace App\Exports;

use App\Models\Withdrawal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class WithDrawlExport implements FromCollection, WithTitle, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * Return the collection of data to be exported.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Assuming `getUsersIds()` returns an array of user IDs
        $ids = getUsersIds();
        return Withdrawal::whereIn('user_id', $ids)->with(['user'])->get();
    }

    /**
     * Map each row to specific columns for export.
     *
     * @param \App\Models\Withdrawal $withdrawal
     * @return array
     */
    public function map($withdrawal): array
    {
        return [
            $withdrawal->id,
            $withdrawal->created_at->format('Y-m-d'), // Date of the withdrawal
            $withdrawal->user->email ?? 'N/A',        // Email from related User model
            $withdrawal->user->country ?? 'N/A',      // Country from related User model
            $withdrawal->user->phone ?? 'N/A',        // Phone from related User model
            $withdrawal->amount,                      // Withdrawal amount
            $withdrawal->currency ?? 'N/A',           // Currency type
            $withdrawal->proof ?? 'N/A',              // Proof document
            $withdrawal->net ?? 'N/A',                // Net amount after fees
            $withdrawal->details ?? 'N/A',            // Additional details
            $withdrawal->status ?? 'N/A',             // Status of the withdrawal
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
            'Net',
            'Details',
            'Status',
        ];
    }

    /**
     * Set the sheet title.
     *
     * @return string
     */
    public function title(): string
    {
        return 'Clients';
    }
}

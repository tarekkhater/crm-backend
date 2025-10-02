<?php
namespace App\Exports;

use App\Models\Message;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class MailingExport implements FromCollection, WithTitle, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * Returns a collection of identities for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Get user IDs for filtering
        $ids = getUsersIds(); // Ensure `getUsersIds()` returns an array of IDs
        return Message::whereIn('user_id', $ids)->with(['user'])->get();
    }

    /**
     * Map each row to specific columns for export.
     *
     * @param \App\Models\Identity $identity
     * @return array
     */
    public function map($identity): array
    {
        return [
            $identity->id,
            $identity->user->name ?? 'N/A',      // Name from related User model
            $identity->user->email ?? 'N/A',     // Email from related User model
            $identity->user->phone ?? 'N/A',     // Phone from related User model
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
            'Name',
            'Email',
            'Number',
        ];
    }

    /**
     * Set the title of the sheet.
     *
     * @return string
     */
    public function title(): string
    {
        return 'Mailing';
    }
}

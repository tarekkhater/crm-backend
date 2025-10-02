<?php
namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadImport implements ToCollection, WithHeadingRow
{
    /**
     * Process each row in the collection.
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Clean email before processing
            $email = $this->clean($row['email'] ?? '');

            if (empty($email)) {
                // Skip rows without a valid email
                continue;
            }

            // Attempt to find the user by cleaned email
            $exist = User::where('email', $email)->first();

            if ($exist) {
                // Update phone if the user exists
                // $exist->phone = '01145863221';
                // $exist->save();
            } else {
                // Optional: Create new user if they don't exist
                User::create([
                    'country'    => $this->clean($row['country'] ?? ''),
                    'address'    => $this->clean($row['address'] ?? ''),
                    'email'      => $email,
                    'first_name' => $this->clean($row['name'] ?? ''),
                    'last_name'  => $this->clean($row['surname'] ?? ''),
                    'phone'      => $this->clean($row['phone'] ?? ''),
                    'source'     => isset($row['source']) ? $this->clean($row['source']) : 1,
                ])->attachRole('lead');
            }
        }
    }

    /**
     * Clean a string by removing unwanted characters.
     * @param string|null $item
     * @return string
     */
    public function clean($item)
    {
        return str_replace('"', '', $item);
    }
}

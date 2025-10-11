<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


use Spatie\Permission\Models\Role;
class UserClientImport implements ToCollection, WithHeadingRow
{
    protected $type;

    /**
     * Constructor to set type for filtering.
     *
     * @param int|null $type
     */
    public function __construct($type = null)
    {
        $this->type = $type;
    }

    /**
     * Handle each row in the collection based on the specified type.
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // try {
            $user =  User::where('email',$row['email'])->first();
            // $user->phone = $row['phone'];
            // $user->save();
            $country = DB::table('countries')
             ->where('name', 'like', '%' . $row['country'] . '%')
             ->first();
             
            // $sources = DB::table('sources')
            //  ->where('name', 'like', '%' . $row['source'] . '%')
            //  ->first();
             
            //   $statuses = DB::table('statuses')
            //  ->where('name', 'like', '%' . $row['status'] . '%')
            //  ->first();
                // if(!$user){
                  $user = User::create([
                        'name' => $row['name']??" ",
                        'surname' => $row['surname'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'country' => $country->id??$row['country'] ,
                        'address' => $row['address']??$row['country'],
                        'offer_name' => $row['offer_name']??"",
                        'type_id'=>$this->type,
                    ]);
                $user->userInfo()->create([
                     'source_id' => 0,
                    'status_id' =>3,
                    'branch_id' => null,
                    'plan_id' => null,
                    'profit' =>  '0',
                    'fee' => '0',
                ]);
                    // $role = Role::find(8);
                    // $user->assignRole($role);
        
            // $manager = Admin::find(145);
            // $user->Manager()->create([
            //     'admin_id'=>(int)$manager->id,
            // ]);
            // if($manager->broker_id > 0 ){
            //     $user->broker_id = $manager->broker_id;
            //     $user->save();
            // }
                // }  
            // } catch (\Exception $e) {
            //     // Log specific row errors
            //     Log::error("Error importing user on row:", [
            //         'row' => $row,
            //         'error' => $e->getMessage()
            //     ]);
            // }
        }
    }

    /**
     * Clean a given string by removing quotes and trimming whitespace.
     *
     * @param string $item
     * @return string
     */
    public function clean($item)
    {
        return trim(str_replace('"', '', $item));
    }

    /**
     * Validate the row based on required fields and uniqueness.
     * Additionally, checks if the row matches the specified type.
     *
     * @param Collection $row
     * @return bool
     */
    protected function validateRow($row)
    {
        if (!isset($row['phone'], $row['email'])) {
            Log::warning("Row missing required fields", ['row' => $row]);
            return false;
        }

        $email = $this->clean($row['email']);
        $phone = $this->clean($row['phone']);

        // Check if a user with the same email or phone already exists
        if (User::where('email', $email)->orWhere('phone', $phone)->exists()) {
            Log::info("User already exists with email or phone", ['email' => $email, 'phone' => $phone]);
            return false;
        }

        // Optional: Add specific checks based on the type
        // For example, if type is 1, 2, etc., you can handle differently here.
        if ($this->type !== null && isset($row['type']) && $row['type'] != $this->type) {
            Log::info("Row does not match specified import type", ['row' => $row, 'type' => $this->type]);
            return false;
        }

        return true;
    }
}

<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\AgentUser;
use App\Models\AssignUserManager;
use App\Models\IBClient;
use App\Models\Message;
use App\Models\UserManager;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


if (!function_exists('amt')) {
    function amt($value)
    {
        return optional(auth()->user()->currency)->sign . '' . number_format($value, 2);
    }
}


if (!function_exists('convertFloat')) {
    function convertFloat($floatAsString)
    {
        $norm = strval(floatval($floatAsString));

        if (($e = strrchr($norm, 'E')) === false) {
            return $norm;
        }

        return number_format($norm, -intval(substr($e, 1)));
    }
}


if (!function_exists('getRealMimeType')) {
    function getRealMimeType($file)
    {

        $filePath = $file->getPathname();
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // Open fileinfo resource
        $realMime = finfo_file($finfo, $filePath); // Get the real MIME type
        finfo_close($finfo); // Close resource

        return $realMime;
    }
}

if (!function_exists('uploadRealImage')) {
    function uploadRealImage($file, $path)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('storage/' . $path, $fileName, 'public');
        return $filePath;
    }
}

if (!function_exists("uploadImage")) {
    function uploadImage($base64String, $path)
    {
        $image = explode('base64,', $base64String);
        $image = end($image);
        $image = str_replace(' ', '+', $image);
        $file =  uniqid() . '.png';

        Storage::disk('public')->put("storage/" . $path . '/' . $file, base64_decode($image));

        return $path . '/' . $file;
    }
}



function check_permission($key)
{
    $roles = explode(',', $key);
    foreach ($roles as $role) {
        $permission = \App\Models\Permission::whereName($key)->first();

        $roles_id = auth()->user()->roles()->get();
        foreach ($roles_id as $id) {
            $permission_id = $id->permissions()->get()->pluck('name')->toarray();

            if (in_array($role, $permission_id)) {
                return  true;
            }
        }
    }

    return false;
}



function check_permission_user($key, $user)
{
    $roles = explode(',', $key);
    foreach ($roles as $role) {
        $permission = \App\Models\Permission::whereName($key)->first();

        $roles_id = $user->roles()->get();

        foreach ($roles_id as $id) {
            $permission_id = $id->permissions()->get()->pluck('name')->toarray();

            if (in_array($role, $permission_id)) {
                return  true;
            }
        }
    }

    return false;
}

if (!function_exists('sendMessage')) {
    function sendMessage($data)
    {
        Message::create($data);
    }
}
if (!function_exists('getTeamLeaderManagedAgentIds')) {
    /**
     * Admin IDs of agents managed by a team leader.
     * Uses UserManager (type 0 = TL-created, type 1 = conversion/retention-created)
     * and Admin.manager_id as fallback for legacy rows.
     */
    function getTeamLeaderManagedAgentIds($admin = null)
    {
        $admin = $admin ?? auth()->user();
        if (!$admin) {
            return collect([]);
        }

        $fromUserManager = UserManager::where('admin_id', $admin->id)
            ->whereIn('type', ['0', '1'])
            ->pluck('user_id');

        $fromManagerId = Admin::withoutGlobalScopes()
            ->where('manager_id', $admin->id)
            ->whereNull('deleted_at')
            ->pluck('id');

        return $fromUserManager->merge($fromManagerId)->unique()->values();
    }
}

if (!function_exists('getTeamLeaderVisibleAgentIds')) {
    /**
     * Agent admin IDs a team leader may access:
     * direct reports (UserManager / manager_id) plus agents on the same desk
     * in the same department (conversion TL → type_id 7, retention TL → type_id 8).
     */
    function getTeamLeaderVisibleAgentIds($admin = null)
    {
        $admin = $admin ?? auth()->user();
        if (!$admin) {
            return collect([]);
        }

        $ids = getTeamLeaderManagedAgentIds($admin);

        if (!empty($admin->desk_id)) {
            $query = Admin::withoutGlobalScopes()
                ->where('desk_id', $admin->desk_id)
                ->whereIn('type_id', [7, 8])
                ->whereNull('deleted_at');

            if ((int) $admin->sub_type_id === 7) {
                $query->where('type_id', 7);
            } elseif ((int) $admin->sub_type_id === 8) {
                $query->where('type_id', 8);
            }

            $ids = $ids->merge($query->pluck('id'))->unique()->values();
        }

        return $ids;
    }
}

if (!function_exists('getUsersIds')) {
    function getUsersIds()
    {
        $ids = [];
        $admin = auth()->user();

        if ($admin->type_id == 3) {
            // Desk-scoped if: sub_type_id=4 OR has a desk_id assigned.
            // Super admin only if: sub_type_id≠4 AND no desk_id.
            $isDeskScoped = ($admin->sub_type_id == 4) || !empty($admin->desk_id);

            if ($isDeskScoped && $admin->desk_id) {
                // withoutGlobalScopes() prevents recursive scope application loop.
                $deskAdminIds = Admin::withoutGlobalScopes()
                    ->where('desk_id', $admin->desk_id)
                    ->whereNull('deleted_at')
                    ->pluck('id');
                $assignedIds = AssignUserManager::whereIn('admin_id', $deskAdminIds)->pluck('user_id');
                $deskRegIds  = User::where('registration_desk_id', $admin->desk_id)->pluck('id');
                $ids = $assignedIds->merge($deskRegIds)->unique()->values();
            } else {
                // Super admin (no desk assigned): sees all users
                $ids = User::pluck('id');
            }
        } elseif ($admin->type_id == 5) {
            // Broker: users tagged with this broker + desk-registered users if broker has a desk
            $brokerIds = User::where('broker_id', $admin->id)->pluck('id');
            if ($admin->desk_id) {
                $deskRegIds = User::where('registration_desk_id', $admin->desk_id)->pluck('id');
                $ids = $brokerIds->merge($deskRegIds)->unique()->values();
            } else {
                $ids = $brokerIds;
            }
        } elseif ($admin->type_id == 4) {
            $ids = AssignUserManager::where('admin_id', $admin->id)->pluck('user_id');
        } elseif ($admin->type_id == 6) {
            // Team leader: users assigned to them or to any visible agent on their desk
            $agentIds = getTeamLeaderVisibleAgentIds($admin);
            $ids = AssignUserManager::where(function ($q) use ($agentIds, $admin) {
                $q->whereIn('admin_id', $agentIds)
                  ->orWhere('admin_id', $admin->id);
            })->pluck('user_id');
        } else {
            // Conversion (7) / Retention (8) agent: only own assigned users
            $ids = AssignUserManager::where('admin_id', $admin->id)->pluck('user_id');
        }

        return $ids;
    }
}

if (!function_exists('getCrmLeadVisibilityUserIds')) {
    /**
     * User IDs visible in CRM leads list: same as getUsersIds(), plus ALL users
     * (leads type_id=1 and customers type_id=2) registered via this admin's desk
     * signup link even if not yet assigned to an agent.
     */
    function getCrmLeadVisibilityUserIds()
    {
        $base = getUsersIds();
        $ids = $base instanceof \Illuminate\Support\Collection ? $base->all() : (array) $base;

        $admin = auth()->user();
        if ($admin && $admin->desk_id) {
            $deskPool = User::query()
                ->where('registration_desk_id', $admin->desk_id)
                ->pluck('id')
                ->all();
            $ids = array_values(array_unique(array_merge($ids, $deskPool)));
        }

        return $ids;
    }
}


if (!function_exists('getTeamLeaderIds')) {
    function getTeamLeaderIds()
    {
        $admin = auth()->user();

        if ($admin->type_id == 5) {
            $ids = Admin::withoutGlobalScopes()
                ->where('broker_id', $admin->id)
                ->whereNull('deleted_at')
                ->pluck('id');

        } elseif ($admin->type_id == 3) {
            $isDeskScoped = ($admin->sub_type_id == 4) || !empty($admin->desk_id);
            if ($isDeskScoped && $admin->desk_id) {
                $ids = Admin::withoutGlobalScopes()
                    ->where('desk_id', $admin->desk_id)
                    ->whereNull('deleted_at')
                    ->pluck('id');
            } else {
                $ids = Admin::withoutGlobalScopes()->whereNull('deleted_at')->pluck('id');
            }

        } elseif ($admin->type_id == 6) {
            $ids = getTeamLeaderVisibleAgentIds($admin)->push($admin->id)->unique()->values();

        } else {
            // Agent (7/8): only themselves
            $ids = collect([$admin->id]);
        }

        return $ids;
    }
}


if (!function_exists('getAgentsIds')) {
    function getAgentsIds()
    {
        $ids = [];
        $admin = auth()->user();

        if ($admin->type_id == 3) {
            $isDeskScoped = ($admin->sub_type_id == 4) || !empty($admin->desk_id);
            if ($isDeskScoped && $admin->desk_id) {
                $ids = Admin::withoutGlobalScopes()
                    ->where('desk_id', $admin->desk_id)
                    ->whereNull('deleted_at')
                    ->pluck('id');
            } else {
                $ids = Admin::withoutGlobalScopes()->whereNull('deleted_at')->pluck('id');
            }
        } elseif ($admin->type_id == 5) {
            $ids = Admin::withoutGlobalScopes()
                ->where('broker_id', $admin->id)
                ->whereNull('deleted_at')
                ->pluck('id');
        } elseif ($admin->type_id == 6) {
            $ids = getTeamLeaderVisibleAgentIds($admin);
        } elseif (in_array((int) $admin->type_id, [7, 8], true)) {
            // Conversion / retention agent: only themselves
            $ids = collect([$admin->id]);
        }

        return $ids;
    }
}

if (!function_exists("AuthApi")) {
    function AuthApi()
    {
        return Auth::guard("apiUser")->user();
    }
}


if (!function_exists("AuthApiAdmin")) {
    function AuthApiAdmin()
    {
        return Auth::guard("api")->user();
    }
}

if (!function_exists("getDifInDays")) {
    function getDifInDays($start, $end)
    {
        $to = Carbon::createFromFormat('Y-m-d', $end);
        $from = Carbon::createFromFormat('Y-m-d', $start);
        return $to->diffInDays($from);
    }
}



if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 6)
    {
        $characters = '0123456789101112345789748784498848744144546554541545458778789545451qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLMNBVCXZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}


if (!function_exists('baseUrl')) {
    function baseUrl()
    {
        $base_url = URL::to('/') . '/';
        return $base_url;
    }
}

if (!function_exists('isBase64Image')) {

    /**
     * Check if the value is a valid Base64 image
     * @param mixed $base64
     * @return bool
     */
    function isBase64Image($base64): bool
    {
        // Check if the value is a valid Base64 string
        if (base64_decode($base64, true) === false) {
            return false;
        }

        // Decode the Base64 string
        $decoded = base64_decode($base64);

        // Check if the decoded string is a valid image
        $finfo = finfo_open();
        $mimeType = finfo_buffer($finfo, $decoded, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        // List of allowed mime types
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

        return in_array($mimeType, $allowedMimeTypes);
    }
}

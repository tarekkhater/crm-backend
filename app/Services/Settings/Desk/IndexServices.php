<?php
namespace App\Services\Settings\Desk;
use App\Models\Desk;
use Illuminate\Http\Request;

class IndexServices{

    protected function isPlatformSuperAdmin($admin): bool
    {
        return $admin
            && (int) $admin->type_id === 3
            && empty($admin->desk_id)
            && (int) $admin->sub_type_id !== 4;
    }

    protected function canAccessDesk($admin, int $deskId): bool
    {
        if (!$admin) {
            return false;
        }
        if ($this->isPlatformSuperAdmin($admin)) {
            return true;
        }
        if ((int) $admin->type_id === 5 && empty($admin->desk_id)) {
            return true;
        }
        return !empty($admin->desk_id) && (int) $admin->desk_id === $deskId;
    }

    protected function shouldExposeRegistrationUrl(Desk $desk): bool
    {
        $admin = auth()->user();
        if (!$admin) {
            return false;
        }
        if ((int) $admin->type_id === 3 && (int) $admin->sub_type_id !== 4) {
            return true;
        }
        return (bool) $admin->desk_id && (int) $admin->desk_id === (int) $desk->id;
    }

    protected function buildRegistrationUrl(Desk $desk): ?string
    {
        if (!$desk->registration_token) {
            return null;
        }
        $base = rtrim((string) config('app.user_register_url', config('app.url')), '/');
        $sep = str_contains($base, '?') ? '&' : '?';
        return $base . $sep . 'desk_ref=' . urlencode((string) $desk->registration_token);
    }

    protected function deskWithRegistrationUrl(?Desk $desk): ?Desk
    {
        if (!$desk) {
            return null;
        }
        if ($this->shouldExposeRegistrationUrl($desk)) {
            $desk->setAttribute('registration_url', $this->buildRegistrationUrl($desk));
        } else {
            $desk->setAttribute('registration_url', null);
        }
        return $desk;
    }

    public function all(Request $request){
        $admin = auth()->user();

        if ($this->isPlatformSuperAdmin($admin)) {
            $Deskss = Desk::all();
        } elseif (!empty($admin->desk_id)) {
            // Desk manager, team leader, agent, broker with desk — own desk only
            $Deskss = Desk::where('id', $admin->desk_id)->get();
        } elseif ($admin && (int) $admin->type_id === 5) {
            // Broker without desk assignment
            $Deskss = Desk::all();
        } else {
            $Deskss = collect([]);
        }

        return $Deskss->map(fn (Desk $d) => $this->deskWithRegistrationUrl($d));
    }

    public function show($id){
        $Desks = Desk::find($id);
        $admin = auth()->user();

        if (!$Desks || !$this->canAccessDesk($admin, (int) $Desks->id)) {
            return null;
        }

        return $this->deskWithRegistrationUrl($Desks);
    }

    public function store($request){
        $Desks = Desk::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);
        return $this->deskWithRegistrationUrl($Desks);
    }
    public function update($request,$id){
        $Desks = Desk::find($id);
        $Desks->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);
        return true;
    }
    public function destroy($request){
        return false;
		$Desks = Desk::findOrFail($request->id);
		$Desks->delete();
		return true;
	}
}

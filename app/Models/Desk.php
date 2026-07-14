<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Desk extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'registration_token'];

    protected $hidden = ['registration_token'];

    protected static function booted(): void
    {
        static::creating(function (Desk $desk) {
            if (empty($desk->registration_token)) {
                do {
                    $desk->registration_token = Str::random(40);
                } while (static::where('registration_token', $desk->registration_token)->exists());
            }
        });
    }

    public static function idForRegistrationToken(?string $token): ?int
    {
        if ($token === null || $token === '') {
            return null;
        }

        return static::query()->where('registration_token', $token)->value('id');
    }
}

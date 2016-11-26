<?php

namespace UHacWeb\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiKey extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'key',
        'apikeyable_id',
        'apikeyable_type',
    ];

    /**
     * @param $apikeyable
     * @return static
     */
    public static function make($apikeyable)
    {
        $apiKey = new ApiKey([
            'key'             => self::generateKey(),
            'apikeyable_id'   => $apikeyable->id,
            'apikeyable_type' => get_class($apikeyable),
        ]);

        $apiKey->save();

        return $apiKey;
    }

    /**
     * A sure method to generate a unique API key
     *
     * @return string
     */
    public static function generateKey()
    {
        do {
            $salt = sha1(time() . mt_rand());
            $newKey = substr($salt, 0, 40);
        } // Already in the DB? Fail. Try again
        while (self::keyExists($newKey));

        return $newKey;
    }

    /**
     * Checks whether a key exists in the database or not
     *
     * @param $key
     * @return bool
     */
    private static function keyExists($key)
    {
        $apiKeyCount = self::where('key', '=', $key)->limit(1)->count();

        if ($apiKeyCount > 0) return true;

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function apikeyable()
    {
        return $this->morphTo();
    }

}

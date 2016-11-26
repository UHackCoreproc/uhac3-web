<?php

namespace UHacWeb\Models\Mixins;

use UHacWeb\Models\Avatar;

trait HasAvatar
{

    public function getArrayableAppends()
    {
        $this->appends = array_unique(array_merge($this->appends, ['avatar_url']));

        return parent::getArrayableAppends();
    }

    public function saveAvatar($uploadedFile, $path, $disk = 'public')
    {
        $this->avatar()->create([
            'disk'         => $disk,
            'path'         => $path,
            'size'         => $uploadedFile->getSize(),
            'content_type' => $uploadedFile->getClientMimeType(),
        ]);
    }

    /**
     * Returns the profile picture of the employee
     *
     * @return Avatar
     */
    public function avatar()
    {
        return $this->morphOne(Avatar::class, 'avatarable');
    }

    public function getAvatarUrl()
    {
        if (empty($this->avatar)) {
            return null;
        }

        return asset($this->avatar->url());
    }

    public function getAvatarUrlAttribute()
    {
        return $this->getAvatarUrl();
    }

}

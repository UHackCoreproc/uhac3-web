<?php

namespace UHacWeb\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Avatar extends Model
{

    protected $fillable = [
        'disk',
        'path',
        'size',
        'content_type',
        'avatarable_id',
        'avatarable_type',
    ];

    public function url()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    /**
     * Delete the file object as well.
     *
     * @return bool|null
     */
    public function delete()
    {
        Storage::disk($this->disk)->delete($this->path);

        return parent::delete();
    }

}

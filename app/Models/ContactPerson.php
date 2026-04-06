<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'surname', 'contact_general_id','choices','other'])]
class ContactPerson extends Model
{

    // protected $fillable = ['name', 'surname', 'contact_general_id','choices','other'];
    public function general()
    {
        return $this->belongsTo(ContactGeneral::class, 'contact_general_id');
    }
}
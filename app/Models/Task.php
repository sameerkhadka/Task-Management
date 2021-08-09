<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class task extends Model
{

    use HasFactory;

    protected $fillable = [

        'title' , 'description' , 'department' , 'assigned_to' , 'contact_person' , 'priority' , 'image' , 'company' , 'completed_at' , 'email_proofed_at'

    ];

public static function user($id){
    return User::find($id)->first()->name;
}



public function company()
{
    return $this->belongsTo(Company::class);
}



}

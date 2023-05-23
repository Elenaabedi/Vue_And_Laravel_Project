<?php

namespace App\Models;

use Mail;
use App\Mail\ContactMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Contact extends Model
{
    use HasFactory;

    public $fillable = ['name', 'lastname', 'email', 'message'];
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public static function boot() {
  
        parent::boot();
  
        static::created(function ($item) {
                
            $adminEmail = "elena_bermudezdiaz@iescarlesvallbona.cat";
            Mail::to($adminEmail)->send(new ContactMail($item));
        });
    }
}
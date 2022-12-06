<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';

    protected $fillable = [
        'name','surname','email','phone','admin_id'
    ]; 

    public function admin(){
        return $this->belongsTo('App\users');
    }

    protected function get_employee_name($id){
    	return self::find($id)->name.' '.self::find($id)->surname;
    }
}

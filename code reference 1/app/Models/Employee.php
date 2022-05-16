<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = ['name','departments_id','salary', 'hobbies', 'gender'];

    protected $appends = [
        'department_name'
    ];


    public function getGenderAttribute($gender) {
        $genderArray = [
            'm' => 'Male',
            'f' => 'Female',
        ];

        $returnValue = 'Other';

        if(!empty($genderArray[$gender])) {
            $returnValue = $genderArray[$gender];
        }

        return $returnValue;

    }

    public function gethobbiesAttribute($hobbies) {

        $hobbiesArray = explode(",",  $hobbies);

        $hobbyArrayData = [
             1 => 'Reading',
             2 => 'Cricket',
             3 => 'Surfing',
             4 => 'Swimming',
             5 => 'Watching movies',
        ];

        $returnValue = array_intersect_key($hobbyArrayData, array_flip($hobbiesArray));

        return implode(", ", $returnValue);

    }

    public function getDepartmentNameAttribute()
    {
        return $this->hasOne(Department::class,'id' ,'departments_id')->first();
    }



}

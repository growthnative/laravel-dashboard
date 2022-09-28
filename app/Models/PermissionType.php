<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionType extends Model
{
    use HasFactory;

    public $table = "permission_types";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */

    protected $fillable = [
        'group_name'
    ];

    public function permission()
    {
        return $this->hasmany('Spatie\Permission\Models\Permission','permission_type_id');
    }
}

<?php

namespace App\Models\Api;

use App\Scopes\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use Filterable;
    use HasFactory;

    protected $table = 'tests';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = true;

    protected $with = [
        //
    ];

    protected $appends = [
        //
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'title',
        'description',
    ];

    protected $hidden = [];
}

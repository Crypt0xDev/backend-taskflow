<?php

namespace App\Modules\Category;

use App\Models\User;
use App\Modules\Task\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'description', 'color', 'user_id'];
    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'category_id');
    }

    protected static function booted(): void
    {
        static::deleting(function (Category $category) {
            $category->tasks()->withTrashed()->update(['category_id' => null]);
        });
    }
}

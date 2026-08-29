<?php

namespace App\Modules\Task;

use App\Models\User;
use App\Modules\Category\Category;
use App\Modules\Tag\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'task';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'description', 'category_id', 'user_id', 'status', 'priority', 'due_date'];
    protected $casts = [
        'category_id' => 'integer',
        'user_id' => 'integer',
        'due_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'task_tag');
    }
}

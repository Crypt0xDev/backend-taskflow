<?php

namespace App\Modules\Tag;

use App\Models\User;
use App\Modules\Task\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
    protected $table = 'tag';
    protected $fillable = ['name', 'description', 'color', 'user_id'];
    protected $casts = ['user_id' => 'integer'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_tag');
    }
}

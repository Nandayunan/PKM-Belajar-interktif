<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = ['module_id', 'name', 'question_ids'];

    protected $casts = [
        'question_ids' => 'array',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function questions()
    {
        if (empty($this->question_ids) || !is_array($this->question_ids)) return collect([]);
        return Question::whereIn('id', $this->question_ids)->get();
    }
}

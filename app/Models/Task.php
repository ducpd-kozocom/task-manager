<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Get the user who created this task
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all assignments for this task
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get all users assigned to this task through assignments
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'assignments')
            ->withPivot('assigned_at', 'completed_at')
            ->withTimestamps();
    }
}

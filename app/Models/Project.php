<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];
    protected $appends = [
        'completion_percentage',
        'tasks_by_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getCompletionPercentageAttribute()
    {
        $total = $this->tasks()->count();

        $completed = $this->tasks()->where('status', 'Finalizada')->count();

        return $total > 0 ? round(($completed / $total) * 100) : 0;
    }

    public function getTasksByStatusAttribute()
    {
        $possibleStatuses = ['Pendiente', 'En Progreso', 'Finalizada'];

        $counts = $this->tasks()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        
        foreach ($possibleStatuses as $status) {
            if (!array_key_exists($status, $counts)) {
                $counts[$status] = 0;
            }
        }

        return $counts;
    }
}

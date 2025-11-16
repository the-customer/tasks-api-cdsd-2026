<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index(Request $r)
    {
        $u = $r->user();
        $q = Task::query()
        ->with(['owner'])
        ->when($r->boolean('shared'),fn($qq)=> $qq->sharedWith($u->id), fn($qq)=> $qq->ownedBy($u->id))
        ->when($r->filled('status'), fn($qq)=> $qq->where('status', $r->string('status')))
        ->when($r->filled('visibility'), fn($qq)=> $qq->where('visibility', $r->string('visibility')))
        ->when($r->boolean('archived'), fn($qq)=> $qq->archived(), fn($qq)=> $qq->active())
        ->when($r->filled('q'), fn($qq)=> $qq->where(function($w) use($r){
            $w->where('title', 'like', "%{$r->q}%")
            ->orWhere('description', 'like', "%{$r->q}%");
        }))
        ->orderBy($r->string('orderBy', 'created_at'), $r->input('orderDir', 'desc'));
        // return $q->paginate($r->input('perPage', 10));
        return TaskResource::collection($q->paginate($r->input('perPage', 10)));
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        // return new TaskResource($task);
        return TaskResource::make($task);
    }
}

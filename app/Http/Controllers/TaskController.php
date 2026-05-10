<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Task::where('farm_id', Auth::user()->farm_id)->with('assignee');

        // Farmers only see their own tasks
        if (Auth::user()->role === 'farmer') {
            $query->where('assigned_to', Auth::id());
        }

        if ($request->has('status')) {
            $status = $request->status;
            if ($status === 'pending') {
                $query->where('completed', false)->where('due_date', '>=', now()->startOfDay());
            } elseif ($status === 'overdue') {
                $query->where('completed', false)->where('due_date', '<', now()->startOfDay());
            } elseif ($status === 'completed') {
                $query->where('completed', true);
            }
        }

        $tasks = $query->orderBy('due_date')->paginate(12);
            
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $this->authorize('create', Task::class);
        $crops = Crop::where('farm_id', Auth::user()->farm_id)->get();
        $users = User::where('farm_id', Auth::user()->farm_id)->get();
        return view('tasks.create', compact('crops', 'users'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Task::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id',
            'crop_id' => 'nullable|exists:crops,id',
        ]);

        $validated['farm_id'] = Auth::user()->farm_id;

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $crops = Crop::where('farm_id', Auth::user()->farm_id)->get();
        $users = User::where('farm_id', Auth::user()->farm_id)->get();
        return view('tasks.edit', compact('task', 'crops', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        // Handle AJAX completion toggle
        if ($request->has('completed')) {
            $task->update([
                'completed' => $request->completed,
                'completed_at' => $request->completed ? now() : null
            ]);
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            
            return back()->with('success', $task->completed ? 'Task completed!' : 'Task reopened.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id',
            'crop_id' => 'nullable|exists:crops,id',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }
}

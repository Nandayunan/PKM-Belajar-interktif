<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'module_id' => 'required|integer|exists:modules,id',
            'name' => 'required|string|max:255',
            'question_ids' => 'nullable|array',
            'question_ids.*' => 'integer|exists:questions,id',
        ]);

        $task = Task::create([
            'module_id' => $data['module_id'],
            'name' => $data['name'],
            'question_ids' => $data['question_ids'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil dibuat.');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->back()->with('success', 'Tugas dihapus.');
    }
}

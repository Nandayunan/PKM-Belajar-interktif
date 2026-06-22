<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Subject;
use App\Models\Module;
use App\Models\User;

class ModuleController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $subjects = Subject::where('created_by', $user->id)->get();
        $availableClasses = User::where('role', 0)
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->pluck('class')
            ->sort()
            ->values()
            ->all();

        return view('guru.modules.create', [
            'subjects' => $subjects,
            'availableClasses' => $availableClasses,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'module_number' => 'required|integer|min:1',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'published' => 'sometimes|accepted',
            'class' => 'nullable|string|max:50',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $module = new Module();
        $module->subject_id = $validated['subject_id'];
        // Normalize class input: trim, uppercase, replace spaces with '-' (e.g., "VII A" -> "VII-A")
        $rawClass = $validated['class'] ?? null;
        if ($rawClass) {
            $normalized = strtoupper(trim((string)$rawClass));
            $normalized = preg_replace('/\s+/', '-', $normalized);
            $normalized = preg_replace('/[^A-Z0-9\-]/', '', $normalized);
            $module->class = $normalized;
        } else {
            $module->class = null;
        }
        $module->name = $validated['name'];
        $module->module_number = $validated['module_number'];
        $module->content = $validated['content'] ?? null;
        $module->video_url = $validated['video_url'] ?? null;
        $module->created_by = $user->id;
        $module->published = isset($validated['published']);

        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('modules', 'public');
            $module->pdf_path = $path;
        }

        $module->save();

        return redirect()->route('guru.dashboard')->with('success', 'Modul berhasil dibuat');
    }

    public function edit($subjectId, $moduleId)
    {
        $module = Module::where('id', $moduleId)->where('subject_id', $subjectId)->firstOrFail();
        $subjects = Subject::where('created_by', Auth::id())->get();
        return view('guru.modules.edit', ['module' => $module, 'subjects' => $subjects]);
    }

    public function update(Request $request, $subjectId, $moduleId)
    {
        $module = Module::where('id', $moduleId)->where('subject_id', $subjectId)->firstOrFail();

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'module_number' => 'required|integer|min:1',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'published' => 'sometimes|accepted',
            'class' => 'nullable|string|max:50',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $module->subject_id = $validated['subject_id'];
        $rawClass = $validated['class'] ?? null;
        if ($rawClass) {
            $normalized = strtoupper(trim((string)$rawClass));
            $normalized = preg_replace('/\s+/', '-', $normalized);
            $normalized = preg_replace('/[^A-Z0-9\-]/', '', $normalized);
            $module->class = $normalized;
        } else {
            $module->class = null;
        }
        $module->name = $validated['name'];
        $module->module_number = $validated['module_number'];
        $module->content = $validated['content'] ?? null;
        $module->video_url = $validated['video_url'] ?? null;
        $module->published = isset($validated['published']);

        if ($request->hasFile('pdf')) {
            // delete old
            if ($module->pdf_path) {
                Storage::disk('public')->delete($module->pdf_path);
            }
            $path = $request->file('pdf')->store('modules', 'public');
            $module->pdf_path = $path;
        }

        $module->save();

        return redirect()->route('guru.dashboard')->with('success', 'Modul berhasil diperbarui');
    }

    public function destroy($subjectId, $moduleId)
    {
        $module = Module::where('id', $moduleId)->where('subject_id', $subjectId)->firstOrFail();
        if ($module->pdf_path) {
            Storage::disk('public')->delete($module->pdf_path);
        }
        $module->delete();
        return redirect()->route('guru.dashboard')->with('success', 'Modul dihapus');
    }
}

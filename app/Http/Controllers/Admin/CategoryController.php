<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return view('admin.categories', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
        ]);

        Subject::create($request->only('name'));

        return redirect()->route('admin.categories')
            ->with('success', 'Предмет успешно добавлен.');
    }

    public function edit(Subject $subject)
    {
        return view('admin.categories_edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
        ]);

        $subject->update($request->only('name'));

        return redirect()->route('admin.categories')
            ->with('success', 'Название предмета обновлено.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.categories')
            ->with('success', 'Предмет удалён.');
    }
}
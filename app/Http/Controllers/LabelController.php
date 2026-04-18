<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $request->user()->labels()->create($request->only('name', 'color'));

        return back();
    }

    public function update(Request $request, Label $label)
    {
        abort_unless($label->user_id === auth()->id(), 403);

        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $label->update($request->only('name', 'color'));

        return back();
    }

    public function destroy(Label $label)
    {
        abort_unless($label->user_id === auth()->id(), 403);

        $label->delete();

        return back();
    }
}

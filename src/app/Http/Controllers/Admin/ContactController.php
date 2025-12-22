<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $contacts = Contact::with('category')
            ->keyword($request->input('keyword'), $request->input('match', 'partial'))
            ->gender($request->input('gender'))
            ->category($request->input('category_id'))
            ->createdDate($request->input('date'))
            ->orderByDesc('created_at')
            ->paginate(7)
            ->withQueryString();

        return view('admin.contacts.index', compact('contacts', 'categories'));
    }

    public function destroy(Request $request)
    {
        $id = $request->input('contact_id');

        Contact::findOrFail($id)->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('message', '削除しました');
    }
}

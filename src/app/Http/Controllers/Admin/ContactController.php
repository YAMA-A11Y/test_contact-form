<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::with('category')
            ->orderByDesc('created_at')
            ->paginate(7);

        return view('admin.contacts.index', compact('contacts'));
    }
}

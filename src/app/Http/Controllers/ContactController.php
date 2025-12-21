<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Category;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('contacts.index', compact('categories'));
    }

    public function confirm(ContactRequest $request)
    {
        $contact = $request->only(['first_name', 'last_name','gender', 'email', 'tel1', 'tel2', 'tel3', 'address', 'building', 'category_id', 'detail']);

        $category = Category::find($contact['category_id']);
        $contact['category'] = $category->content;

        return view('contacts.confirm', ['contact' => $contact]);
    }

    public function store(ContactRequest $request)
    {
        $form = $request->all();

       unset($form['_token']);
       
       $form['tel'] = $form['tel1'] . $form['tel2'] . $form['tel3'];

       unset($form['tel1'], $form['tel2'], $form['tel3']);

       Contact::create($form);

       return redirect()->route('contact.thanks');
    }

    public function back(ContactRequest $request)
    {
        return redirect()->route('contact.index')->withInput($request->only(['last_name', 'first_name', 'gender', 'email', 'tel1', 'tel2', 'tel3', 'address', 'building',
        'category_id', 'detail',]));
    }

    public function thanks()
    {
        return view('contacts.thanks');
    }
}

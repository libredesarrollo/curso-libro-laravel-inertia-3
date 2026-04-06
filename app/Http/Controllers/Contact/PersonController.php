<?php
namespace App\Http\Controllers\Contact;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\PersonRequest;
use App\Models\ContactPerson;
use Illuminate\Http\Request;
class PersonController extends Controller
{
 
    public function create()
    {
        return inertia("contact/person/Form");
    }
    public function store(PersonRequest $request)
    {
        ContactPerson::create($request->validated());
    }
    public function edit(ContactPerson $contactPerson)
    {
        return inertia("contact/person/Form", compact('contactPerson'));
    }
 
    public function update(PersonRequest $request, ContactPerson $contactPerson)
    {
      $contactPerson->update($request->validated());
    }
}
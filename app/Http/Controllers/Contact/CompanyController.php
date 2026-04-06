<?php
namespace App\Http\Controllers\Contact;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\CompanyRequest;
use App\Models\ContactCompany;

class CompanyController extends Controller
{
 
    public function create()
    {
        return inertia("contact/company/Form");
    }
    public function store(CompanyRequest $request)
    {
        ContactCompany::create($request->validated());
    }
    public function edit(ContactCompany $contactCompany)
    {
        return inertia("contact/company/Form", compact('contactCompany'));
    }
 
    public function update(CompanyRequest $request, ContactCompany $contactCompany)
    {
      $contactCompany->update($request->validated());
    }
}
<?php
namespace App\Http\Controllers\Contact;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\DetailRequest;
use App\Models\ContactDetail;
use Illuminate\Http\Request;
class DetailController extends Controller
{
 
    public function create()
    {
        return inertia("contact/detail/Form");
    }
    public function store(DetailRequest $request)
    {
        ContactDetail::create($request->validated());
    }
    public function edit(ContactDetail $contactDetail)
    {
        return inertia("contact/detail/Form", compact('contactDetail'));
    }
 
    public function update(DetailRequest $request, ContactDetail $contactDetail)
    {
      $contactDetail->update($request->validated());
    }
}
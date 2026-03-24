<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::query();

        if ($request->search) {
            $query->where('name', 'like', "%$request->search%")
                  ->orWhere('code', 'like', "%$request->search%");
        }

        if ($request->city) {
            $query->where('city', $request->city);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $branches = $query->latest()->paginate(10);

        return view('branch', compact('branches'));
    }

    public function store(Request $request)
    {
       $request->validate([
    'name' => 'required|unique:branches,name',
    'code' => 'nullable|unique:branches,code',
    'address' => 'required',
    'city' => 'required',
    'province' => 'required',
    'mobile' => 'required|unique:branches,mobile',
    'email' => 'nullable|email|unique:branches,email',
], [
    'name.unique' => 'Branch name already exists.',
    'code.unique' => 'Branch code already used.',
    'mobile.unique' => 'Mobile number already used.',
    'email.unique' => 'Email already used.',
]);
        Branch::create([
            'name'=>$request->name,
            'code'=>$request->code ?? $this->generateCode(),
            'address'=>$request->address,
            'city'=>$request->city,
            'province'=>$request->province,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
            'status'=>$request->status
        ]);

        return back()->with('success','Branch added');
    }

    public function update(Request $request,$id)
    {
        $branch = Branch::findOrFail($id);

       $request->validate([
    'name' => 'required|unique:branches,name,' . $id,
    'code' => 'nullable|unique:branches,code,' . $id,
    'address' => 'required',
    'city' => 'required',
    'province' => 'required',
    'mobile' => 'required|unique:branches,mobile,' . $id,
    'email' => 'nullable|email|unique:branches,email,' . $id,
], [
    'name.unique' => 'Branch name already exists.',
    'code.unique' => 'Branch code already used.',
    'mobile.unique' => 'Mobile already used.',
    'email.unique' => 'Email already used.',
]);

        $branch->update($request->all());

        return back()->with('success','Branch updated');
    }

    public function destroy($id)
    {
        Branch::findOrFail($id)->delete();
        return back()->with('success','Deleted successfully');
    }

    private function generateCode()
    {
        $last = Branch::latest()->first();
        $num = $last ? (int) substr($last->code, -3) + 1 : 1;

        return 'BR-' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    public function checkField(Request $request)
{
    $field = $request->field;
    $value = $request->value;
    $id = $request->id; // for edit ignore

    $exists = Branch::where($field, $value)
        ->when($id, fn($q) => $q->where('id', '!=', $id))
        ->exists();

    return response()->json([
        'exists' => $exists
    ]);
}
}

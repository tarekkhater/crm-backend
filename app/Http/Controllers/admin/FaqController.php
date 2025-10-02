<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::all();
        return view('admin.faqs.index', compact('faqs'));
    }


    public function store(Request $request)
    {
        $data = $this->getData($request);
        Faq::create($data);
        return redirect()->route('admin.faqs.index')->with('success', 'Faq was successfully added.');
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $currencies = Faq::findOrFail($id);
        $currencies->update($this->getData($request));
        return redirect()->route('admin.faqs.index');
    }
    protected function getData(Request $request)
    {
        $rules = [
            'title' => 'string|min:1|max:255|required',
            'details' => 'string|min:1|required',
        ];
        return $request->validate($rules);
    }
}

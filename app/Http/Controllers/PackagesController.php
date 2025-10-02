<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Exception;

class PackagesController extends Controller
{

    /**
     * Display a listing of the packages.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $packages = Package::paginate(25);

        return view('packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {


        return view('packages.create');
    }

    /**
     * Store a new package in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            Package::create($data);

            return redirect()->route('packages.package.index')
                ->with('success_message', 'Package was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified package.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $package = Package::findOrFail($id);

        return view('packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $package = Package::findOrFail($id);


        return view('packages.edit', compact('package'));
    }

    /**
     * Update the specified package in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $package = Package::findOrFail($id);
            $package->update($data);

            return redirect()->route('packages.package.index')
                ->with('success_message', 'Package was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Remove the specified package from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $package = Package::findOrFail($id);
            $package->delete();

            return redirect()->route('packages.package.index')
                ->with('success_message', 'Package was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }


    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
                'name' => 'string|min:1|max:255|nullable',
            'description' => 'string|min:1|max:1000|nullable',
            'percent_profit' => 'string|min:1|nullable',
            'period' => 'string|min:1|nullable',
            'price' => 'string|min:1|nullable',
            'min_unit' => 'string|min:1|nullable',
            'max_unit' => 'string|min:1|nullable',
            'status' => 'string|min:1|nullable',
        ];

        $data = $request->validate($rules);

        return $data;
    }

}

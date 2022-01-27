<?php

namespace App\Http\Controllers;

use App\Models\CV;
use Illuminate\Http\Request;

class CVController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cv = CV::all();

        $response = [
            'cv' => $cv,
        ];

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required',
            'age'  => 'required',
            'experience'  => 'required',
            'residence'  => 'nullable'
        ]);

        if($request->hasFile('file')){
            // Get filename with the extension
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('file')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('file')->storeAs('public/file', $fileNameToStore);
        }

        $cv = CV::create([
            'age' => $request->input('age'),
            'experience'  =>  $request->input('experience'),
            'residence'  => $request->input('residence'),
            'file'  => $fileNameToStore
        ]);

        $response   =   [
            'message' => 'Submitted Successfully'
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CV  $cV
     * @return \Illuminate\Http\Response
     */
    public function show(CV $c, $id)
    {
        $cv = CV::find($id);

        $response = [
            'cv' => $cv,
        ];

        return $response;
    }

    public function search(Request $request)
    {
        $age = $request->input('age');
        $residence = $request->input('residence');
        $experience = $request->input('experience');
        
        $cv = CV::where('age', '<=', '%'.$age.'%')
        ->orWhere('residence', 'ilike', '%'.$residence.'%')
        ->orWhere('experience', '>=', '%'.$experience.'%')
        ->get();

        if ($cv->count() >= 1) {
            $response = [
                'cv' => $cv,
            ];

            return $response;
        }

        return response([
            'message'=> 'No records found', 404
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CV  $cV
     * @return \Illuminate\Http\Response
     */
    public function edit(CV $cV)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CV  $cV
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CV $cV)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CV  $cV
     * @return \Illuminate\Http\Response
     */
    public function destroy(CV $cV)
    {
        //
    }
}

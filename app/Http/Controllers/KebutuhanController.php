<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Kebutuhan;

use Illuminate\Support\Facades\DB;

class KebutuhanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataKebutuhan = Kebutuhan::all();
        return view('layout.kebutuhan', compact('dataKebutuhan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('layout.kebutuhan');
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
            'Jenis' => 'required|string|max:255',
            'Deskripsi' => 'required|string|max:255',
            'Tentang' => 'required|string|max:255',
            'fotoVideo' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        // $imageName = time() . '_' . $request->file('fotoVideo')->getClientOriginalName();
        $imageName = time() . '.' . $request->fotoVideo->extension();

        // Public Folder
        $request->fotoVideo->move(public_path('fotoVideo'), $imageName);

        DB::table('kebutuhans')->insert([
            'jenis_kebutuhan' => $request->Jenis,
            'deskripsi' => $request->Deskripsi,
            'kebutuhan_tentang' => $request->Tentang,
            'foto_dan_vidio' => $imageName,
        ]);
        return redirect('kebutuhan');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            //delete old image
            Storage::delete('public/posts/'.$post->image);

            //update post with new image
            $post->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content
            ]);

        } else {

            //update post without image
            $post->update([
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model= Kebutuhan::find($id);
        $model->delete();
        return redirect('kebutuhan');
    }
}

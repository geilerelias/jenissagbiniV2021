<?php

namespace App\Http\Controllers;

use App\Models\PublishedArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublishedArticleController extends Controller
{
    public function index(Request $request)
    {
        return PublishedArticle::orderBy('id', 'DESC')->get();
        abort(401, 'This action is unauthorized');
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "title" => 'required',
            "year" => 'required',
            "country" => 'required',
            "isbn" => 'required',
            "ed" => 'required',
        ]);

        $path = "";

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Generate a file name with extension
            $fileName = 'article-' . time() . '.' . $file->getClientOriginalExtension();
            // Save the file
            $path = $file->storeAs('published-articles', $fileName);
        }

        $publishedArticle = new PublishedArticle();
        $publishedArticle->title = $request->title;
        $publishedArticle->year = $request->year;
        $publishedArticle->country = $request->country;
        $publishedArticle->isbn = $request->isbn;
        $publishedArticle->ed = $request->ed;
        $publishedArticle->description = $request->description;
        $publishedArticle->link = $request->link;
        $publishedArticle->file = $path;
        $publishedArticle->save();

        return $publishedArticle;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $publishedArticle = PublishedArticle::find($id);
        return $publishedArticle;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $publishedArticle = PublishedArticle::find($id);
        return $publishedArticle;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "title" => 'required',
            "year" => 'required',
            "country" => 'required',
            "isbn" => 'required',
            "ed" => 'required',
        ]);

        $path = "";

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Generate a file name with extension
            $fileName = 'article-' . time() . '.' . $file->getClientOriginalExtension();
            // Save the file
            $path = $file->storeAs('published-articles', $fileName);
        }

        $publishedArticle = PublishedArticle::find($id);

        if ($path === "") {
            Storage::delete($publishedArticle->file);
        }
        $publishedArticle->title = $request->title;
        $publishedArticle->year = $request->year;
        $publishedArticle->country = $request->country;
        $publishedArticle->isbn = $request->isbn;
        $publishedArticle->ed = $request->ed;
        $publishedArticle->description = $request->description;
        $publishedArticle->link = $request->link;

        $publishedArticle->save();
        return $publishedArticle;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $publishedArticle = PublishedArticle::find($id);
        Storage::delete($publishedArticle->file);
        $publishedArticle->delete();
    }
}

<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\CategoryTreeResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CategoryTreeResource::collection(Category::where('parent_id', null)->get());
    }


}

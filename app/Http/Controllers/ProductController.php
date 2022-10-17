<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use MongoDB\Driver\Session;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'filter.name' => 'string|max:255',
            'filter.category_id' => 'numeric',
            'filter.desc' => 'string|max:255',
            'filter.slug' => 'string|max:255',
            'filter.max_price' => 'numeric',
            'filter.min_price' => 'numeric',
        ]);

        if ($request->has('filter') && is_array($request->filter)){
            $filteredProducts = $this->getFilteredProducts($request->filter);
            return response()
                ->json(['data' => ProductResource::collection($filteredProducts)]);
        }

        $allProducts = Product::with('features')->with('category')->get();
        return response()
            ->json(['data' => ProductResource::collection($allProducts)]);
    }

    public function getBySlug($slug){
        return response()
            ->json(['data' => new ProductResource(Product::where('slug', $slug)->first())]);
    }

    public function getFilteredProducts($filter){
        $q = Product::query();
        foreach ($filter as $key => $value){
            if($key == 'max_price'){
                $q->where('price', '<=', $value);
            } elseif ($key == 'min_price'){
                $q->where('price', '>=', $value);
            } else if ($key == 'category_id'){
                $q->where($key, '=', $value);
                $childCategories = [];
                $childCategories = Category::find($value)->getAllChildren($childCategories);
                $q->orWhere(function($query) use ($key, $childCategories){
                    foreach ($childCategories as $childCat){
                        $query->orWhere($key, '=', $childCat);
                    }
                });
            } else {
                if (Schema::hasColumn(Product::getTableName(), $key)){
                    $q->where($key, 'like', '%'.$value.'%');
                }else{
                    $q->whereHas('features', function ($q) use($key, $value) {
                        $q->where('name', $key)->where('value', 'like', '%'.$value.'%');
                    });
                }
            }
        }
        return $q->with('features')->get();
    }
}

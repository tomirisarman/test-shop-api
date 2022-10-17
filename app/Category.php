<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function isRoot()
    {
        if ($this->parent_id === null) {
            return true;
        }
        return false;
    }

    public function getDirectChildren()
    {
        return Category::where('parent_id', $this->id)->get();
    }

    public function hasDirectChildren()
    {
        return Category::where('parent_id', $this->id)->count() > 0;
    }

    public function getAllParents()
    {
        $parents = [];

        $parentId = $this->parent_id;

        while ($parentId !== null) {
            $parents[] = $parentId;
            $parentId = Category::where('id', $parentId)->first()->parent_id;
        }

        return $parents;

    }

    public static function getFullTree(){
        $tree = [];
        foreach (Category::getAllRootCategories() as $category){
            Category::getCategoriesTree($tree, $category);
        }
        return $tree;
    }

    public static function getAllRootCategories()
    {
        return Category::where('parent_id', null)->get();
    }

    public static function getCategoriesTree(&$tree, Category $category)
    {
        $directChildren = $category->getDirectChildren();
        if (isset($directChildren)) {
            foreach ($directChildren as $child) {
                $tree[$category->name][$child->name] = [];
                Category::getCategoriesTree($tree[$category->name], $child);

            }
        }
    }

    public function getAllChildren(&$childrenResult){
        $parentId = $this->id;
        $categories = Category::where('parent_id', $parentId)->get();
        if (count($categories)>0){
            foreach ($categories as $cat){
                $childrenResult[] = $cat->id;
                $cat->getAllChildren($childrenResult);
            }
        }
        return $childrenResult;
    }




}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected static $product;
    protected static $productImage,$imageName,$imageDirectory,$imageUrl;


    public static function getImageUrl($request)
    {
        self::$productImage = $request->file('image');
        self::$imageName = rand(10,1000).self::$productImage->getClientOriginalName();
        self::$imageDirectory = 'backend/img/';
        self::$productImage->move(self::$imageDirectory, self::$imageName);
        self::$imageUrl = self::$imageDirectory.self::$imageName;
        return self::$imageUrl;
    }
    public static function createProduct($request)
    {
        self::$product = new Product();
        self::$product->category_id     = $request->category_id;
        self::$product->name            = $request->name;
        self::$product->brand_name      = $request ->brand_name;
        self::$product->price           = $request->price;
        self::$product->description     = $request->description;
        self::$product->image           =  self::getImageUrl($request);
        self::$product->status          = $request->status;
        self::$product->save();
    }

    public static function updatedproduct($request, $id)
    {
        self::$product = Product::find($id);
        if ($request->file('image'))
        {
            if (file_exists(self::$product->image))
            {
                unlink(self::$product->image);
            }
            self::$imageUrl = self::getImageUrl($request);
        }
        else
        {
            self::$imageUrl = self::$product->image;
        }

        self::$product->category_id     = $request->category_id;
        self::$product->name            = $request->name;
        self::$product->brand_name      = $request ->brand_name;
        self::$product->price           = $request->price;
        self::$product->description     = $request->description;
        self::$product->image           =  self::$imageUrl;
        self::$product->status          = $request->status;
        self::$product->save();
    }
    public  function  category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}



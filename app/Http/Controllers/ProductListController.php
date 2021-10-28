<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $products = new Product();

        /* Product filter */
        if(isset($request->title)){
            $products = $products->where('title', 'like', '%'.$request->title.'%');
        }

        if(isset($request->variant)){
            $productVariant = $request->variant;
            $products = $products->whereHas('productVariant',function($q) use($productVariant){
                $q->where('variant', $productVariant);
            });
        }

        if(isset($request->price_from) && isset($request->price_to)){
            $price['from']= $request->price_from;
            $price['to']= $request->price_to;
            $products = $products->whereHas('combos',function($q) use($price){
                $q->whereBetween('price',$price);
            });
        }

        if(isset($request->date)){
            $products = $products->whereDate('created_at', Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m-d'));
        }

        $products = $products->with('combos')->paginate(10);

        $variants = Variant::with('productItems')->get();

        return view('product-list.index',compact('products','variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $product =   $request->only('title','sku', 'description');
        $product = Product::create($product);


        $variantOne = $request->product_variant[0];
        $variantTwo = $request->product_variant[1];
        $variantThree = $request->product_variant[2];


        /* Input individual variant in product_variant*/
        foreach ($variantOne['tags'] as $vOne){
            $productVariantOne[] = $this->createProductVariant([
                'variant'=> $vOne,
                'variant_id'=> $variantOne['option'],
                'product_id'=>$product->id
            ]);
        }
        foreach ($variantTwo['tags'] as $vTwo){
            $productVariantTwo[] = $this->createProductVariant([
                'variant'=> $vTwo,
                'variant_id'=> $variantTwo['option'],
                'product_id'=>$product->id
            ]);
        }
        foreach ($variantThree['tags'] as $vThree){
            $productVariantThree[] = $this->createProductVariant([
                'variant'=> $vThree,
                'variant_id'=> $variantThree['option'],
                'product_id'=>$product->id
            ]);
        }

        /* Input individual variant in product_variant*/
        for($i=0 ; $i < count($productVariantOne); $i++){
            for($j=0 ; $j < count($productVariantTwo); $j++){
                for($k=0 ; $k < count($productVariantThree); $k++){
                    $comboName = $productVariantOne[$i]->variant.'/'.$productVariantTwo[$j]->variant.'/'.$productVariantThree[$k]->variant.'/';
                    $comboName = str_replace(' ', '', $comboName);
                    $combo=[];
                    foreach ($request->product_variant_prices as $variantPriceCombo){
                        if(str_replace(' ', '', $variantPriceCombo['title']) == $comboName){
                            $combo = $variantPriceCombo;
                        }
                    }
                    if(count($combo)){
                        ProductVariantPrice::create([
                            'product_variant_one' => $productVariantOne[$i]->id,
                            'product_variant_two' => $productVariantTwo[$j]->id,
                            'product_variant_three' => $productVariantThree[$k]->id,
                            'price'=> $combo['price'],
                            'stock'=> $combo['stock'],
                            'product_id'=> $product->id,
                        ]);
                    }
                }
            }
        }

        return 'success';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product_list)
    {
        $product = $product_list->load('combos','productVariant');
        $variants = Variant::all();
        return view('product-list.edit', compact('variants','product'));
    }

    public function update(Request $request, Product $product_list)
    {
        $productId = $product_list->id;

        $product =   $request->only('title','sku', 'description');
        $product_list->update($product);

        $product = Product::find($productId);


        $variantOne = $request->product_variant[0];
        $variantTwo = $request->product_variant[1];
        $variantThree = $request->product_variant[2];

        /* flash old date */
        $oldProductVariance = ProductVariant::where('product_id',$product->id)->get();
        foreach ($oldProductVariance as $oldVariant){
            $oldVariant->delete();
        }
        $oldProductCombo = ProductVariantPrice::where('product_id',$product->id)->get();
        foreach ($oldProductCombo as $oldCombo){
            $oldCombo->delete();
        }

//        return $variantOne['tags'];
        /* Input individual variant in product_variant*/
        foreach ($variantOne['tags'] as $vOne){
            $productVariantOne[] = $this->createProductVariant([
                'variant'=> $vOne,
                'variant_id'=> $variantOne['option'],
                'product_id'=>$product->id
            ]);
        }
        foreach ($variantTwo['tags'] as $vTwo){
            $productVariantTwo[] = $this->createProductVariant([
                'variant'=> $vTwo,
                'variant_id'=> $variantTwo['option'],
                'product_id'=>$product->id
            ]);
        }
        foreach ($variantThree['tags'] as $vThree){
            $productVariantThree[] = $this->createProductVariant([
                'variant'=> $vThree,
                'variant_id'=> $variantThree['option'],
                'product_id'=>$product->id
            ]);
        }



        /* Input individual variant in product_variant*/
        for($i=0 ; $i < count($productVariantOne); $i++){
            for($j=0 ; $j < count($productVariantTwo); $j++){
                for($k=0 ; $k < count($productVariantThree); $k++){
                    $comboName = $productVariantOne[$i]->variant.'/'.$productVariantTwo[$j]->variant.'/'.$productVariantThree[$k]->variant.'/';
                    $comboName = str_replace(' ', '', $comboName);
                    $combo=[];
                    foreach ($request->product_variant_prices as $variantPriceCombo){
                        if(str_replace(' ', '', $variantPriceCombo['title']) == $comboName){
                            $combo = $variantPriceCombo;
                        }
                    }
                    if(count($combo)){
                        ProductVariantPrice::create([
                            'product_variant_one' => $productVariantOne[$i]->id,
                            'product_variant_two' => $productVariantTwo[$j]->id,
                            'product_variant_three' => $productVariantThree[$k]->id,
                            'price'=> $combo['price'],
                            'stock'=> $combo['stock'],
                            'product_id'=> $product->id,
                        ]);
                    }
                }
            }
        }

        return 'success';
    }

    public function createProductVariant($data)
    {
        $product_variant  = ProductVariant::create($data);
        return $product_variant;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
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

    public function createProductVariant($data)
    {
        $product_variant  = ProductVariant::create($data);
        return $product_variant;
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants','product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

//        $product =   $request->only('title','sku', 'description');
//        $product = $product_list->update($product);
//
//
//        $variantOne = $request->product_variant[0];
//        $variantTwo = $request->product_variant[1];
//        $variantThree = $request->product_variant[2];
//
//        /* flash old date */
//        $oldProductVariance = ProductVariant::where('product_id',$product->id)->get();
//        foreach ($oldProductVariance as $oldVariant){
//            $oldVariant->delete();
//        }
//        $oldProductCombo = ProductVariantPrice::where('product_id',$product->id)->get();
//        foreach ($oldProductCombo as $oldCombo){
//            $oldCombo->delete();
//        }
//
//        /* Input individual variant in product_variant*/
//        foreach ($variantOne['tags'] as $vOne){
//            $productVariantOne[] = $this->createProductVariant([
//                'variant'=> $vOne,
//                'variant_id'=> $variantOne['option'],
//                'product_id'=>$product->id
//            ]);
//        }
//        foreach ($variantTwo['tags'] as $vTwo){
//            $productVariantTwo[] = $this->createProductVariant([
//                'variant'=> $vTwo,
//                'variant_id'=> $variantTwo['option'],
//                'product_id'=>$product->id
//            ]);
//        }
//        foreach ($variantThree['tags'] as $vThree){
//            $productVariantThree[] = $this->createProductVariant([
//                'variant'=> $vThree,
//                'variant_id'=> $variantThree['option'],
//                'product_id'=>$product->id
//            ]);
//        }
//
//        /* Input individual variant in product_variant*/
//        for($i=0 ; $i < count($productVariantOne); $i++){
//            for($j=0 ; $j < count($productVariantTwo); $j++){
//                for($k=0 ; $k < count($productVariantThree); $k++){
//                    $comboName = $productVariantOne[$i]->variant.'/'.$productVariantTwo[$j]->variant.'/'.$productVariantThree[$k]->variant.'/';
//                    $comboName = str_replace(' ', '', $comboName);
//                    $combo=[];
//                    foreach ($request->product_variant_prices as $variantPriceCombo){
//                        if(str_replace(' ', '', $variantPriceCombo['title']) == $comboName){
//                            $combo = $variantPriceCombo;
//                        }
//                    }
//                    if(count($combo)){
//                        ProductVariantPrice::create([
//                            'product_variant_one' => $productVariantOne[$i]->id,
//                            'product_variant_two' => $productVariantTwo[$j]->id,
//                            'product_variant_three' => $productVariantThree[$k]->id,
//                            'price'=> $combo['price'],
//                            'stock'=> $combo['stock'],
//                            'product_id'=> $product->id,
//                        ]);
//                    }
//                }
//            }
//        }

        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}

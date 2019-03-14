<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(config('products.paginate'));

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCreateRequest $request)
    {
        $data = $request->only([
            'category_id',
            'product_name',
            'price',
            'image',
            'quantity',
            'avg_rating',
        ]);

        $uploaded = $this->upload($data['image']);

        if (!$uploaded['status']) {
            return back()->with('status', $uploaded['msg']);
        }

        $data['image'] = $uploaded['file_name'];

        try {
            $product = Product::create($data);
        } catch (Exception $e) {
            return back()->with('status', 'Create fail!');
        }

        return redirect('products/' . $product->id)->with('status', 'Create success!');
    }

    private function upload($file)
    {
        $destinationFolder = public_path() . "/" . config('products.image_path');

        try {
            $fileName = $file->getClientOriginalName() . '_' . date('Ymd_His');
            $ext = $file->getClientOriginalExtension();

            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $result = [
                    'status' => false,
                    'msg' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.',
                ];
            }
            // $mimeType = $file->getMimeType();
            // $realPath = $file->getRealPath();
            $file->move($destinationFolder, $fileName);

            $result = [
                'status' => true,
                'file_name' => $fileName,
            ];
        } catch (Exception $e) {
            $msg = $e->getMessage();

            $result = [
                'status' => false,
                'msg' => $msg,
            ];
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return back()->with('status', 'Product not found!');
        }

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return back();
        }

        return view('products.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        $data = $request->only([
            'category_id',
            'product_name',
            'price',
            'image',
            'quantity',
            'avg_rating',
        ]);

        try {
            $product = Product::find($id);
            $product->update($data);
        } catch (Exception $e) {
            return back()->with('status', 'Update fail');
        }

        return redirect('products/' . $id)->with('status', 'Update success!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function search($key)
    {
        $products = Product::where('product_name', 'like', "%$key%")->get()->toArray();

        return response()->json($products);
    }
}

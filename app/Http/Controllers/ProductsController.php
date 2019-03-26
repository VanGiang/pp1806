<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $orderBy = isset($params['orderBy']) ? $params['orderBy'] : 'created_at';
        $type = isset($params['type']) ? $params['type'] : 'desc';
        $products = Product::orderBy($orderBy, $type)->paginate(config('products.paginate'));
        $selected = [
            1 => (($orderBy == 'created_at') && ($type == 'desc')),
            2 => (($orderBy == 'price') && ($type == 'asc')),
            3 => (($orderBy == 'price') && ($type == 'desc')),
        ];
        return view('products.index', ['products' => $products, 'selected' => $selected]);
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
    public function store(Request $request)
    {
         $data = $request->only([
            'category_id',
            'product_name',
            'price',
            'image',
            'quantity',
            'avg_rating',
            'description',
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $products = Product::find($id);
        if (!$products) {
            return back()->with('status', 'Product does not exist');
        }

        return view('products.show', ['product' => $products]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Product::find($id);
        if (!$products) {
            return back()->with('status', 'Product does not exist');
        }
        if ($products->id != auth()->id){
            return back()->with('status', 'You do not have permission');
        }

        return view('products.edit', ['product' => $products]);
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
        $data = $request->only(['category_id', 'product_name', 'price', 'quantity', 'image']);

        try {
            $products = User::find($id);
            $products->update($data);
        } catch (Exception $e) {
            return back()->with('status', 'Product update failed');
        }

        return redirect('products')->with('status', 'Product updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $products = User::find($id);
            $products->delete();
            $result = [
                'status' => true,
                'msg' => 'Delete success',
            ];
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'msg' => 'Delete fail',
            ];
        }

        return response()->json($result);
    }
}

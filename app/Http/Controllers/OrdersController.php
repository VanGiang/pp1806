<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderCreateRequest;
use App\Order;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //     $orders = Order::all();
    //     return view('orders.index', ['orders' => $orders]);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    }
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderCreateRequest $request)
    {
        $data = $request->only(['total_price', 'description']);
        $currentUserId = auth()->id();
        try {
            $data['user_id'] = $currentUserId;
            Order::create($data);
        } catch (Exception $e) {
            return back()->with('status', 'Create fail');
        }
        return redirect("users/$currentUserId")->with('status', 'Profile updated!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $order = Order::find($id);
        // if (!$order) {
        //     return back()->with('status', 'Order not exist');
        // }
        // return view('orders.show', compact('order'));
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
        // $validated = $request->validated();
        // $data = $request->only('user_id', 'total_price', 'description', 'status');
        // $order = Order::find($id);
        // try {
        //     $order ->update($data);
        // } catch (\Exception $e) {
        //     dd($e->getMessage());
        //     return back()->with('status', 'Update fail');
        // }
        // return redirect(route('orders.show', $order->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    
    {
        //  $order = Order::find($id);
        // if (!$order) {
        //     $result = [
        //         'status' => false,
        //         'message' => 'Order does not exist',
        //     ];
        // } else {
        //     try {
        //         $order->delete();
        //         $result = [
        //             'status' => true,
        //             'message' => ' Delete successfully',
        //         ];
        //     } catch (\Exception $e) {
        //         $result = [
        //             'status' => true,
        //             'message' => 'Failed to delete order',
        //             'error' => $e->getMessage()
        //         ];
        //     }
        // }
        // return response()->json($result);
    }
    public function cancelled(Request $request, $id)
    {
        $data = $request->only(['status']);
        try {
            $order = Order::find($id);
            $order->update($data);
            $result = [
                'status' => true,
                'msg' => 'Cancel success',
            ];
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'msg' => 'Cancel fail',
            ];
        }
        return response()->json($result);
    }
}

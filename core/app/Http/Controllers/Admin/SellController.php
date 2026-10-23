<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellController extends Controller {

    public function allSales() {
        $pageTitle = "All Sales";
        $perPage   = request()->get('per_page', 4);
        $allSales  = Order::latest('id')->paid()->dateFilter()->searchable(['trx', "user:username"])->withCount('orderItems')->with('product.author', 'user')->paginate($perPage)->withQueryString();
        return view('admin.sale.all', compact('allSales', 'pageTitle'));
    }

    public function refundedItems($authorId = 0) {
        $pageTitle = 'Refunded Orders & Course Access';
        $perPage   = request()->get('per_page', 4);
        $sales     = OrderItem::searchable(['purchase_code', 'product:title', 'product.author:username'])
            ->dateFilter()
            ->latest('id')
            ->with(['product.author', 'order', 'order.user', 'buyer', 'product'])
            ->whereHas('product', function ($query) {
                if (request()->author_id) {
                    $query->where('user_id', request()->author_id);
                }
            })->paginate($perPage)->withQueryString();

        return view('admin.sale.list', compact('sales', 'pageTitle'));
    }

    public function grantEntitlement(Request $request) {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            $user   = User::findOrFail($request->user_id);
            $course = Product::findOrFail($request->product_id);

            $order = new Order();
            $order->user_id = $user->id;
            $order->total_amount = 0;
            $order->trx = 'GRANT-' . strtoupper(Str::random(10));
            $order->status = Status::ORDER_PAID;
            $order->save();

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $course->id;
            $orderItem->buyer_id = $user->id;
            $orderItem->product_price = 0;
            $orderItem->purchase_code = getPurchaseCode();
            $orderItem->license = Status::PERSONAL_LICENSE;
            $orderItem->save();

            $notify[] = ['success', "Course access entitlement granted to {$user->fullname}"];
        } catch (\Throwable $e) {
            $notify[] = ['error', 'Failed to grant entitlement: ' . $e->getMessage()];
        }

        return back()->withNotify($notify);
    }

    public function revokeEntitlement($id) {
        try {
            $item = OrderItem::findOrFail($id);
            $item->delete();
            $notify[] = ['success', 'Student course access has been revoked'];
        } catch (\Throwable $e) {
            $notify[] = ['error', 'Failed to revoke entitlement: ' . $e->getMessage()];
        }
        return back()->withNotify($notify);
    }

    public function saleItems($authorId = 0) {
        $pageTitle = 'Orders & Purchased Courses';
        $perPage   = request()->get('per_page', 4);
        $sales     = OrderItem::searchable(['purchase_code', 'product:title', 'product.author:username'])
            ->dateFilter()
            ->latest('id')
            ->with(['product.author', 'order', 'order.user', 'buyer', 'product'])
            ->whereHas('product', function ($query) {
                if (request()->author_id) {
                    $query->where('user_id', request()->author_id);
                }
            });

        if ($authorId) {
            $sales = $sales->where('user_id', $authorId);
        }

        $sales = $sales->paginate($perPage)->withQueryString();
        return view('admin.sale.list', compact('sales', 'pageTitle'));
    }

    public function details($id) {
        $pageTitle = 'Order Items';
        $sales     = OrderItem::where('order_id', $id)->with('product.author', 'buyer', 'product')->paginate(getPaginate());
        return view('admin.sale.list', compact('pageTitle', 'sales'));
    }

    public function allDetails($id) {
        $pageTitle = 'Order Item Details';
        $product   = OrderItem::where('id', $id)->with('product.author', 'buyer', 'order')->firstOrFail();
        return view('admin.sale.details', compact('pageTitle', 'product'));
    }
}

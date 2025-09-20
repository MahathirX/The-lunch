<?php

namespace App\Http\Controllers;

use App\Events\NotificationBroadcast;
use App\Http\Requests\OrderRequest;
use App\Mail\AdminOrderMail;
use App\Mail\SendPasswordMail;
use App\Mail\UserOrderInvoiceMail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Role;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Product\App\Models\Product;

class OrderController extends Controller
{
    public function orderStore(OrderRequest $request)
    {
        if($request->product_id != null){
            $userpass       = rand(100000, 999999);
            $password       = Hash::make($userpass);
            $user           = User::where('email', $request->email)->orWhere('phone',$request->phone)->first();
            $role           = Role::where('slug', 'client_portal')->first();
            $invoiceid       = rand(100000, 999999);
            $cart           = session()->get('cart', []);
            $totall_price   = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
            $total_quantity = array_sum(array_map(fn($item) => $item['quantity'], $cart));
            if (!$user) {
                $user = User::create([
                    'fname'             => $request->name,
                    'lname'             => $request->lname ?? null,
                    'phone'             => $request->phone,
                    'email'             => $request->email,
                    'password'          => $password,
                    'role_id'           => $role->id,
                    'email_verified_at' => now(),
                ]);

                $mailuserinfo = ['fname' => $request->name, 'email' => $request->email, 'password' => $userpass];
                Mail::to($user->email)->send(new SendPasswordMail($mailuserinfo));
            }

            if ($request->shipping == config('settings.deliveryinsidedhake')) {
                $shippingtype = 1;
            } else {
                $shippingtype = 2;
            }
            $order = Order::create([
                'invoice_id'     => $invoiceid,
                'user_id'        => $user->id,
                'amount'         => $totall_price,
                'charge'         => $request->shipping,
                'quantity'       => $total_quantity,
                'payment_status' => 'cashone',
                'adress'         => $request->address,
                'phone'          => $request->phone,
                'customer'       => $request->name,
                'shippingtype'   => $shippingtype,
                'status'         => '1',
            ]);

            $orderDetails = [];
            $cart = session()->get('cart', []);
            $orderDetails = [];
            foreach ($cart as $id => $item) {
                $orderDetails[] =  OrderDetail::create([
                    'invoice_id'    => $invoiceid,
                    'order_id'      => $order->id,
                    'product_id'    => $item['product_id'],
                    'product_name'  => $item['name'],
                    'product_image' => $item['image'],
                    'quantity'      => $item['quantity'],
                    'amount'        => $item['price'],
                    'grandtotal'    => $item['quantity'] * $item['price'],
                    'status'        => '0',
                    'varient'       => $item['product_varient'] ?? null,
                ]);
            }
            session()->forget('cart');

            $admins = User::whereIn('role_id', [1, 2])->get();
            $request['full_name']    = $user->fname . ' ' . $user->lname;
            $request['email']        = $user->email;
            $request['phone']        = $user->phone;
            $request['order_id']     = $order->id;
            $request['order_date']   = $order->created_at->format('j M Y h:i A');
            $request['total_amount'] = $order->amount;
            $request['button_url']   = route('order.invoice.download', ['order_id' => $order->id ?? 0]);
            $request['button_title'] = 'Click Here To Download Invoice';
            $request['app_name']     = env('APP_NAME') ?? 'Momora';

            // User mail
            $subject = emailSubjectTemplate('DOWNLOAD_ORDER_INVOICE_MAIL', $request);
            $body    = emailBodyTemplate('DOWNLOAD_ORDER_INVOICE_MAIL', $request);
            $heading = emailHeadingTemplate('DOWNLOAD_ORDER_INVOICE_MAIL', $request);
            $userMail = ['subject' => $subject, 'body' => $body, 'heading' => $heading];

            Mail::to($user->email)->later(now()->addSeconds(15), new UserOrderInvoiceMail($userMail));
            if($admins && count($admins) > 0){
                foreach ($admins as $admin) {
                    $request['admin_name'] = $admin->fname . ' ' . $admin->lname;
                    // Mail
                    $subject = emailSubjectTemplate('NEW_ORDER_RECEIVED', $request);
                    $body    = emailBodyTemplate('NEW_ORDER_RECEIVED', $request);
                    $heading = emailHeadingTemplate('NEW_ORDER_RECEIVED', $request);
                    $adminMail = ['subject' => $subject, 'body' => $body, 'heading' => $heading];

                    Mail::to($admin->email)->later(now()->addSeconds(20), new AdminOrderMail($adminMail));
                    // Notification
                    $data = [
                        'type'     => 'new_order',
                        'title'    => 'New Order',
                        'message'  => 'Full Name: ' . ($user->fname ?? '') . ' ' . ($user->lname ?? '') . ' | Email: ' . ($user->email ?? '') . ' | Phone: ' . ($user->phone ?? ''),
                        'order_id' => $order->id,
                    ];
                    $admin->notify(new GenericNotification($data));

                    $message = [
                        'sender'  => $user->id,
                        'to'      => $admin->id,
                        'message' => 'New Order ',
                    ];
                    broadcast(new NotificationBroadcast($message));
                }
            }

            return redirect()->route('order.success.two',['order_id' => $order->id])->with('success', 'Your order is successfully store ! check you email inbox or spam !');
        }else{
            return back()->with('error','Select at last one product');
        }

    }

    public function landingPageOrder(OrderRequest $request)
    {
        $userpass       = rand(100000, 999999);
        $password       = Hash::make($userpass);
        $user           = User::where('email', $request->email)->first();
        $role           = Role::where('slug', 'client_portal')->first();
        $invoiceid      = rand(100000, 999999);
        $product        = Product::where('id', $request->product_id)->first();
        if (!$user) {
            $user = User::create([
                'fname'    => $request->name,
                'lname'    => $request->lname ?? null,
                'phone'    => $request->phone,
                'email'    => $request->email,
                'password' => $password,
                'role_id'  => $role->id,
            ]);

            $mailuserinfo = ['fname' => $request->name, 'email' => $request->email, 'password' => $userpass];
            Mail::to($user->email)->send(new SendPasswordMail($mailuserinfo));
        }

        if ($request->shipping == config('settings.deliveryinsidedhake')) {
            $shippingtype = 1;
        } else {
            $shippingtype = 2;
        }
        $productPrice   = $product->discount_price ?? $product->price;
        $totall_price   = $productPrice * (int) $request->product_qnt;
        $total_quantity = (int) $request->product_qnt;

        $order = Order::create([
            'invoice_id'     => $invoiceid,
            'user_id'        => $user->id,
            'amount'         => $totall_price,
            'charge'         => $request->shipping,
            'quantity'       => $total_quantity,
            'payment_status' => 'cashone',
            'adress'         => $request->address,
            'phone'          => $request->phone,
            'customer'       => $request->name,
            'shippingtype'   => $shippingtype,
            'status'         => '1',
        ]);

        OrderDetail::create([
            'invoice_id'    => $invoiceid,
            'order_id'      => $order->id,
            'product_id'    => $product->id ,
            'product_name'  => $product->name,
            'product_image' => $product->product_image ?? null,
            'quantity'      => $total_quantity,
            'amount'        => $productPrice,
            'grandtotal'    => $totall_price,
            'status'        => '0',
            'varient'       => $request->product_varient ?? null,
        ]);
        return response()->json([
            'status'  => 'success',
            'message' => 'Your order is successfully store ! check you email inbox or spam !',
        ]);
    }
}

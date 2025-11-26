<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartProduct;
use App\Models\CartService;
use Illuminate\Http\Request;
use App\Models\InstallmentPay;
use App\Models\CustomeDeleteItem;
use App\Models\CustomePackageItem;
use Illuminate\Support\Facades\Auth;

// Import CartInfo helper function
if (!function_exists('CartInfo')) {
    require_once app_path('Helper/helper.php');
}

class CartController extends Controller
{

    public function user_id()
    {
        return Auth::guard('mainUsers')->user()->id;
    }

    public function saveNewProductCart($service_id, $package_service_id, $cart_id)
    {
        // randome code
        $random_code = rand_Code(4);
        $random_code = 'B' . $random_code;
        $check_empty = CartService::where('cart_id', $cart_id)->where('service_id', $service_id)->where('package_service_id', $package_service_id)->first();
        if (!$check_empty) {
            $newProductCart = new CartService();
            $newProductCart->cart_id = $cart_id;
            $newProductCart->service_id = $service_id;
            $newProductCart->package_service_id = $package_service_id;
            $newProductCart->code = $random_code;
            $newProductCart->save();
            return $newProductCart;
        } else {
            return false;
        }
    }
    public function addToCart(Request $request)
    {
        // $service_id = $request->service_id;
        $service_id = $request->service_id;
        $package_service_id = $request->package_service_id;



        $check = Cart::where('user_id', $this->user_id())
            ->where('status', 0)->first();
        if (!$check) {
            $newCart = new Cart();
            $newCart->user_id = $this->user_id();
            $newCart->total = 0;
            $newCart->save();
            $this->saveNewProductCart($service_id, $package_service_id, $newCart->id);
            return response()->json(['success' => true]);
        } else {
            $this->saveNewProductCart($service_id, $package_service_id, $check->id);
            return response()->json(['success' => true]);
        }
    }
    public function payCart(Request $request)
    {
        $subtotal = $request->subtotal;

        $check = Cart::where('user_id', $this->user_id())
            ->where('status', 0)->first();

        if (!$check) {
            $check->total = $subtotal;
            $check->status = 1;
            $check->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function downloadPdf(Request $request)
    {
        // Get cart info
        $cartInfo = CartInfo();

        if (!$cartInfo) {
            return response()->json(['success' => false, 'message' => 'Cart not found']);
        }

        // For now, we'll use a simple approach
        // If you want to use dompdf, install: composer require barryvdh/laravel-dompdf
        // Then uncomment the code below and create a PDF view

        /*
        // Uncomment this when dompdf is installed:
        use Barryvdh\DomPDF\Facade\Pdf;
        
        $data = [
            'cartInfo' => $cartInfo,
            'packageServices' => $cartInfo->cartServices,
        ];
        
        $pdf = Pdf::loadView('pdf.cart', $data);
        return $pdf->download('cart-quote-' . date('Y-m-d') . '.pdf');
        */

        // Temporary: Return success to trigger print dialog
        return response()->json(['success' => true, 'print' => true]);
    }

    public function deleteCartItem(Request $request)
    {
        $id = $request->id;
        $cart_id = $request->cart_id;
        if ($id) {

            // check   cart_services length

            $cartServices = CartService::where('cart_id', $cart_id)->count();
            if ($cartServices === 1) {
                // empty cart
                Cart::where('id', $cart_id)->delete();
            }

            $cartItem = CartService::find($id);
            $cartItem->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function newOrder($cart_id, $total_payment, $TaxFee, $discount)
    {

        $tracking_code = rand_Code(6);
        $order = new Order();
        $order->user_id = $this->user_id();
        $order->cart_id = $cart_id;
        $order->tracking_code = $tracking_code;
        $order->total_payment = $total_payment;
        $order->tax_fee = $TaxFee;
        $order->discount = $discount;
        $order->invoice = 1;

        $order->save();
    }
    public function goPayAll(Request $request)
    {
        $Order_total = $request->subtotal;
        $TaxFee = $request->TaxFee;
        $discount = $request->discount;
        $cart_id = $request->cart_id;
        $amount = $request->amount ?? [];
        $date = $request->date ?? [];
        
        $ContactName = $request->ContactName;
        $BillingAddress = $request->BillingAddress;
        $ShipingAddress = $request->ShipingAddress;
        // Validate that amount and date are arrays
        if (!is_array($amount) || !is_array($date)) {
            return response()->json(['success' => false, 'message' => 'Amount and date must be arrays'], 400);
        }

        // Validate that arrays have the same length
        if (count($amount) !== count($date)) {
            return response()->json(['success' => false, 'message' => 'Amount and date arrays must have the same length'], 400);
        }

        // Validate cart exists
        $cart = Cart::where('id', $cart_id)->where('user_id', $this->user_id())->first();
        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], 404);
        }

        // notice amount and date is array []
        foreach ($amount as $key => $value) {
            // Skip empty values
            if (empty($value) || empty($date[$key])) {
                continue;
            }

            // Validate that date key exists
            if (!isset($date[$key])) {
                continue;
            }

            $installmentPay = new InstallmentPay();
            $installmentPay->user_id = $this->user_id();
            $installmentPay->cart_id = $cart_id;
            $installmentPay->amount = $value;
            $installmentPay->date = $date[$key];
            $installmentPay->save();
        }
        // new order
        $this->newOrder($cart_id, $Order_total, $TaxFee, $discount);
        // after change status cart to 1
        $cart->status = 1;
        $cart->save();

        return response()->json(['success' => true, 'amount' => $amount, 'date' => $date]);
    }


    public function createCustomDeleteItem(Request $request)
    {
        $itemId = $request->id;
        $cartId = $request->cart_id;
        // $serviceId = $request->service_id;
        // $packageServiceId = $request->package_service_id;

        $customeDeleteItem = new CustomeDeleteItem();
        $customeDeleteItem->package_service_item_id = $itemId;
        $customeDeleteItem->user_id = $this->user_id();
        $customeDeleteItem->cart_id = $cartId; 

        $customeDeleteItem->save();
        // $cartItem = CartService::find($itemId);
        // $cartItem->delete();
        return response()->json(['success' => true]);
    }



    public function getInvoiceDetails(Request $request)
    {
        $orderId = $request->order_id;
        $userId = Auth::guard('mainUsers')->user()->id;

        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->with([
                'cart.cartServices.packageServiceItems',
                'cart.cartServices.serviceInfo'
            ])
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        // Get user info
        $mainUser = Auth::guard('mainUsers')->user();
        $userInfo = UserInfoPublic();

        // Prepare data similar to cart page
        $cartInfo = $order->cart;
        $packageServices = $cartInfo ? $cartInfo->cartServices : collect();

        // Load orderItem for each packageServiceItem (similar to helper.php)
        if ($cartInfo && $packageServices) {
            foreach ($packageServices as $cartService) {
                // Make sure packageServiceItems are loaded
                if (!$cartService->relationLoaded('packageServiceItems')) {
                    $cartService->load('packageServiceItems');
                }

                if ($cartService->packageServiceItems && count($cartService->packageServiceItems) > 0) {
                    foreach ($cartService->packageServiceItems as $packageServiceItem) {
                        // Load orderItem if not already loaded
                        if (!$packageServiceItem->orderItem && $packageServiceItem->orderitem_id) {
                            $packageServiceItem->orderItem = OrderItem::where('id', $packageServiceItem->orderitem_id)->first();
                        }
                    }
                }
            }
        }

        // Calculate totals
        $grandSubtotal = 0;
        $grandTotalTax = 0;
        $grandTotalDiscount = 0;
        $grandGrandTotal = 0;

        if ($cartInfo && $packageServices) {
            foreach ($packageServices as $packageService) {
                if ($packageService->packageServiceItems) {
                    foreach ($packageService->packageServiceItems as $packageServiceItem) {
                        if ($packageServiceItem->orderItem) {
                            $itemTotal = $packageServiceItem->quantity * $packageServiceItem->orderItem->price;
                            $grandSubtotal += $itemTotal;

                            // Calculate discount
                            if ($packageServiceItem->orderItem->discount_type == '%') {
                                $discountAmount = ($itemTotal * $packageServiceItem->orderItem->discount) / 100;
                                $itemTotalWithDiscount = $itemTotal - $discountAmount;
                                if ($itemTotalWithDiscount < 0) {
                                    $itemTotalWithDiscount = 0;
                                }
                                $testttt = ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                $TotalLastColumnFinal = ($packageServiceItem->orderItem->tax * $itemTotal) / 100 + $itemTotalWithDiscount;
                            } else {
                                $discountAmount = $packageServiceItem->orderItem->discount;
                                $itemTotalWithDiscount = $itemTotal - $discountAmount;
                                if ($itemTotalWithDiscount < 0) {
                                    $itemTotalWithDiscount = 0;
                                }
                                $testttt = ($packageServiceItem->orderItem->tax * $itemTotal) / 100;
                                $TotalLastColumnFinal = $itemTotalWithDiscount + $testttt;
                            }

                            $grandTotalTax += $testttt;
                            $grandTotalDiscount += $discountAmount;
                            $grandGrandTotal += $TotalLastColumnFinal;
                        }
                    }
                }
            }
        }

        $html = view('livewire.panel.invoice.invoice-detail-modal', [
            'order' => $order,
            'mainUser' => $mainUser,
            'userInfo' => $userInfo,
            'cartInfo' => $cartInfo,
            'packageServices' => $packageServices,
            'grandSubtotal' => $grandSubtotal,
            'grandTotalTax' => $grandTotalTax,
            'grandTotalDiscount' => $grandTotalDiscount,
            'grandGrandTotal' => $grandGrandTotal,
        ])->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    public function getOrderItems()
    {
        $orderItems = OrderItem::select('id', 'name', 'code', 'price')->get();
        return response()->json(['success' => true, 'orderItems' => $orderItems]);
    }

    public function createCustomPackageItem(Request $request)
    {
        $orderItemId = $request->order_item_id;
        $cartId = $request->cart_id;
        $packageServiceId = $request->package_service_id;
        $cartServiceId = $request->cart_service_id;
        $customePackageItem = new CustomePackageItem();
        $customePackageItem->order_item_id = $orderItemId;
        $customePackageItem->cart_service_id = $cartServiceId;
        $customePackageItem->user_id = $this->user_id();
        $customePackageItem->cart_id = $cartId;
        $customePackageItem->package_service_id = $packageServiceId;
        $customePackageItem->save();

        // Get the order item details
        $orderItem = OrderItem::find($orderItemId);

        return response()->json([
            'success' => true,
            'item' => [
                'id' => $customePackageItem->id,
                'order_item_id' => $orderItemId,
                'name' => $orderItem->name,
                'code' => $orderItem->code,
                'price' => $orderItem->price,
            ]
        ]);
    }

    public function deleteCustomPackageItem(Request $request)
    {
        $itemId = $request->id;
        $customePackageItem = CustomePackageItem::where('id', $itemId)
            ->where('user_id', $this->user_id())
            ->first();

        if ($customePackageItem) {
            $customePackageItem->delete();
            return response()->json(['success' => true, 'message' => 'Item removed successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }
}

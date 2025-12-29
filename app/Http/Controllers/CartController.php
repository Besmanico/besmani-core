<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartProduct;
use App\Models\CartService;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\InstallmentPay;
use App\Models\CustomeDeleteItem;
use App\Models\CustomePackageItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Filament\Notifications\DatabaseNotification as FilamentDatabaseNotification;

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

    public function newOrder($cart_id, $total_payment, $TaxFee, $discount, $pay_method, $ContactName, $BillingAddress, $ShipingAddress, $signature_client, $signature_date)
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
        $order->pay_method = $pay_method;
        $order->contact_name = $ContactName;
        $order->billing_address = $BillingAddress;
        $order->shipping_address = $ShipingAddress;
        $order->signature_client = $signature_client;
        $order->signature_date = $signature_date;
        $order->save();
        return $order;
    }
    public function goPayAll(Request $request)
    {
        $Order_total = $request->subtotal;
        $TaxFee = $request->TaxFee;
        $discount = $request->discount;
        $cart_id = $request->cart_id;
        $amount = $request->amount ?? [];
        $date = $request->date ?? [];
        $payment_method = $request->payment_method;


        $ContactName = $request->ContactName;
        $BillingAddress = $request->BillingAddress;
        $ShipingAddress = $request->ShipingAddress;
        $signature_client = $request->signature_client;
        $signature_date = $request->signature_date;
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
        $resOrder = $this->newOrder($cart_id, $Order_total, $TaxFee, $discount, $payment_method, $ContactName, $BillingAddress, $ShipingAddress, $signature_client, $signature_date);
        $tracking_code = $resOrder->tracking_code;
        // after change status cart to 1
        $cart->status = 1;
        $cart->save();
       
        // Send Filament notification to all admin users
        // Note: OrderObserver is already registered in AppServiceProvider, so it will automatically fire when order is created
        // No need to call it manually here to avoid duplicate notifications
        
        // $this->sendNewOrderNotification($resOrder, $tracking_code); 

        return response()->json(['success' => true, 'amount' => $amount, 'date' => $date,'tracking_code'=>$tracking_code]);
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
        $orderItems = OrderItem::select('id', 'name', 'code', 'price')->where('visible', 1)->get();
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

    /**
     * Send Filament notification to all admin users about new order
     */
    private function sendNewOrderNotification($order, $tracking_code)
    {
        try {
            // Get all admin users (users with status = 1 who can access admin panel)
            $adminUsers = \App\Models\User::where('status', 1)->get();
            
            if ($adminUsers->isEmpty()) {
                Log::info('No admin users found to send notification');
                return;
            }
            
            // Get user info for the order
            $mainUser = $order->user;
            $userName = $mainUser ? ($mainUser->fl_name . ' ' . $mainUser->last_name) : 'Unknown User';
            $orderTotal = number_format($order->total_payment, 2);
            
            // Get order edit URL
            $orderUrl = \App\Filament\Resources\OrderResource::getUrl('edit', ['record' => $order->id]);
            
            // Send notification to each admin user using Filament's DatabaseNotification
            foreach ($adminUsers as $adminUser) {
                try {
                    // Create Filament notification
                    $notification = \Filament\Notifications\Notification::make()
                        ->title('New Order Received')
                        ->body("New order #{$tracking_code} from {$userName}. Total: \${$orderTotal}")
                        ->success()
                        ->icon('heroicon-o-shopping-cart')
                        ->actions([
                            \Filament\Notifications\Actions\Action::make('view')
                                ->label('View Order')
                                ->url($orderUrl)
                                ->button(),
                        ]);
                    
                    // Try to send notification using Filament's sendToDatabase method
                    try {
                        $dbNotification = $notification->sendToDatabase($adminUser);
                        if ($dbNotification) {
                            $notificationId = is_object($dbNotification) && method_exists($dbNotification, 'getKey') 
                                ? $dbNotification->getKey() 
                                : 'unknown';
                            Log::info("Notification sent via sendToDatabase for user ID: {$adminUser->id}, Notification ID: {$notificationId}");
                        } else {
                            // Fallback to direct database insert if sendToDatabase returns null
                            Log::warning("sendToDatabase returned null, trying alternative method for user ID: {$adminUser->id}");
                            $this->createDatabaseNotificationDirectly($adminUser, $tracking_code, $userName, $orderTotal, $orderUrl);
                        }
                    } catch (\Exception $e) {
                        Log::error("sendToDatabase failed, trying alternative method. Error: " . $e->getMessage());
                        // Fallback method
                        $this->createDatabaseNotificationDirectly($adminUser, $tracking_code, $userName, $orderTotal, $orderUrl);
                    }
                    
                    Log::info("Notification processed for admin user ID: {$adminUser->id} (Email: {$adminUser->email}) for order #{$tracking_code}");
                } catch (\Exception $e) {
                    Log::error("Failed to send notification to admin user ID: {$adminUser->id}. Error: " . $e->getMessage());
                    Log::error("Stack trace: " . $e->getTraceAsString());
                }
            }
            
            // Verify notifications were saved (check after all are sent)
            foreach ($adminUsers as $adminUser) {
                $adminUser->refresh();
                $notificationCount = $adminUser->notifications()->count();
                $latestNotification = $adminUser->notifications()->latest()->first();
                 
                if ($latestNotification) {
                    Log::info("Admin user ID: {$adminUser->id} has {$notificationCount} total notifications. Latest: ID={$latestNotification->id}, Type={$latestNotification->type}");
                } else {
                    Log::warning("Admin user ID: {$adminUser->id} has {$notificationCount} total notifications. No notifications found in database!");
                }
            }
            
            Log::info('Notifications sent to ' . $adminUsers->count() . ' admin users for order #' . $tracking_code);
        } catch (\Exception $e) {
            // Log error but don't break the order creation
            Log::error('Failed to send new order notification: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Alternative method to create database notification directly
     */
    private function createDatabaseNotificationDirectly($adminUser, $tracking_code, $userName, $orderTotal, $orderUrl)
    {
        try {
            // Create notification data array (Laravel will automatically JSON encode this)
            $notificationData = [
                'title' => 'New Order Received',
                'body' => "New order #{$tracking_code} from {$userName}. Total: \${$orderTotal}",
                'icon' => 'heroicon-o-shopping-cart',
                'color' => 'success',
                'duration' => null,
                'actions' => [
                    [
                        'name' => 'view',
                        'label' => 'View Order',
                        'url' => $orderUrl,
                        'button' => true,
                    ],
                ],
            ];

            // Use the notifications() relationship which properly handles the morphable relationship
            // and automatically generates UUID for the ID field
            $dbNotification = $adminUser->notifications()->create([
                'type' => \Filament\Notifications\DatabaseNotification::class,
                'data' => $notificationData, // Laravel will automatically JSON encode this
            ]);

            $notificationId = $dbNotification->getKey();
            Log::info("Database notification created via relationship for user ID: {$adminUser->id}, Notification ID: {$notificationId}");
            return $dbNotification;
        } catch (\Exception $e) {
            Log::error("Failed to create database notification via relationship: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            
            // Last resort: try direct database insert
            try {
                $notificationId = \Illuminate\Support\Str::uuid()->toString();
                $notificationData = [
                    'title' => 'New Order Received',
                    'body' => "New order #{$tracking_code} from {$userName}. Total: \${$orderTotal}",
                    'icon' => 'heroicon-o-shopping-cart',
                    'color' => 'success',
                    'duration' => null,
                    'actions' => [
                        [
                            'name' => 'view',
                            'label' => 'View Order',
                            'url' => $orderUrl,
                            'button' => true,
                        ],
                    ],
                ];

                DB::table('notifications')->insert([
                    'id' => $notificationId,
                    'type' => \Filament\Notifications\DatabaseNotification::class,
                    'notifiable_type' => get_class($adminUser),
                    'notifiable_id' => $adminUser->id,
                    'data' => json_encode($notificationData),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info("Database notification created via direct DB insert for user ID: {$adminUser->id}, Notification ID: {$notificationId}");
                return true;
            } catch (\Exception $e2) {
                Log::error("Failed to create database notification via direct DB insert: " . $e2->getMessage());
                return null;
            }
        }
    }
}

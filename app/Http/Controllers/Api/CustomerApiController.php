<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{Customer,Cart,Product};
use Exception;
use DB;
use URL;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerApiController extends ApiController
{
    public function getCustomerHomePage(Request $request)
    {
        try {   

            $requestData = Validator::make($this->request->all(), [
                'customer_id' => 'required',
            ]);

            if ($requestData->fails()) {
                throw new Exception($requestData->messages()->first(), 1);
            }

            $customer_id = $request->customer_id;
            $user_data = Customer::with('customerUser')->where('id', $customer_id)->first();           

            $user_status = $user_data->is_active ?? 0;
            $customer_status = $user_data->customerUser->is_active ?? 0;

            $this->response_json['is_active'] = (strtolower($user_status) == 'yes' && strtolower($customer_status) == 'yes') ? 1 : 0;
            $this->response_json['setting_info'] = $this->getSettingData(); 
            $this->response_json['cart_summary'] = $this->getCartSummary($customer_id);           
            $this->response_json['banner'] = $this->getBanner();
            $this->response_json['category'] = $this->getCategory();
            $this->response_json['product_data'] = $this->getProduct($customer_id);
            $this->response_json['total_cart'] = Cart::where('customer_id', $customer_id)->count();
            return $this->responseSuccessWithoutObject();
        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }
        return $this->responseSuccessWithoutObject();

    }

    public function getCartSummary($customer_id)
    {
        $cart_result = Cart::where('customer_id',$customer_id)->select('qty','price',DB::raw("(CASE WHEN qty !='' THEN qty * price ELSE 0 END) as total_price"))->get()->toArray();

        return array(
            'total_item' => array_sum(array_column($cart_result, 'qty')),
            'total_price' => array_sum(array_column($cart_result, 'total_price')),
        );
    }

    public function getCustomerProduct(Request $request)
    {
        try {

            $requestData = Validator::make($this->request->all(), [
                'page' => 'required',
            ]);
            if ($requestData->fails()) {
                throw new Exception($requestData->messages()->first(), 1);
            }

            $product_path = URL::asset('');

            $page = $request->page ?? 0;
            $perPage = config('global.pagination_records');
            $category_id = $request->category_id ?? 0;
            $customer_id = $request->customer_id ?? 0;
            $search = $request->search ?? null;

            $priseListId = DB::table('customers')->where('id',$customer_id)->first()->price_list_id ?? '';

            $result = Product::where('products.is_active','Yes')
                        ->select([
                            'products.id',
                            'products.product_name',
                            'products.image',
                            'products.category_id',
                            'products.hsncode_id',
                            'hsn_codes.gst'
                            // 'minimum_stock_qty',
                            // 'pouch_qty',                
                        ])
                        ->leftJoin('hsn_codes','hsn_codes.id','products.hsncode_id')
                        ->when($search, function ($query, $search) {
                              return $query->where('products.product_name','LIKE', "%{$search}%");
                        });
                        // dd($result->get());
            if($category_id > 0){
                $result = $result->where('category_id', $category_id);
            }
            $result = $result->orderByRaw('RAND()')->get();

            $toReturn = array();
            if(!empty($result)){
                $index = 0;

                foreach ($result as $key => $row) 
                {   
                    $productShow = DB::table('price_list_items as PI')
                                        ->where('PI.price_list_id',$priseListId)
                                        ->where('PI.product_id',$row['id'])
                                        ->first();
                    if($productShow == null){
                        continue;
                    }

                    $catName = DB::table('categories')->where('id',$row['category_id'])->first()->name ?? '';

                    $prodId = $row->id;

                    $productVariantData = DB::table('product_variants as PV')
                                            ->select([
                                                'PV.product_id as product_id',
                                                'PV.variant_id as variant_id',
                                                'PV.price as price',
                                                'PV.id as product_variant_id',
                                                'PV.bandha_pouch_qty as bandha_pouch_qty',
                                                'PV.min_stock_qty',
                                                'PV.weight',
                                                'V.name as variant_name',
                                                'V.description as description',
                                                'C.customer_id as cart_customer_id',
                                                'C.id as cart_id',
                                                'C.qty as qty',
                                            ])
                                            ->leftJoin('carts as C', function ($join) {
                                                $join->on('PV.variant_id', '=', 'C.variant_id')
                                                     ->on('PV.product_id', '=', 'C.product_id');
                                            })
                                            ->leftJoin('variants as V','V.id','PV.variant_id')
                                            ->where('PV.product_id',$prodId)
                                            ->groupBy(['PV.product_id','PV.variant_id'])
                                            ->get();
                    
                    $is_cart_data = array_filter($productVariantData->toArray(), function($data) use($customer_id) {
                        return (!empty($data) && $data->cart_customer_id == $customer_id);
                    });
                    $is_cart = (!empty($is_cart_data)) ? 1 : 0;
                    $wishData = DB::table('wishlists')->where('product_id',$prodId)->where('customer_id',$customer_id)->where('is_wishlist',1)->first();
                    $is_wishlist = (!empty($wishData)) ? 1 : 0;

                    $toReturn[$index]['id'] = $row['id'];
                    $toReturn[$index]['product_name'] = $row['product_name'];
                    $toReturn[$index]['hsncode_id'] = $row->hsncode_id ?? '';
                    $toReturn[$index]['gst'] = $row->gst ?? '';
                    $toReturn[$index]['category_name'] = $catName;
                    $toReturn[$index]['image'] = (!empty($row['image'])) ? $product_path.'/'.$row['image'] : $product_path.'/product/product_default.jpg';
                    $toReturn[$index]['category_id'] = $row['category_id'];
                    $toReturn[$index]['is_wishlist'] = $is_wishlist;
                    $toReturn[$index]['is_cart'] = $is_cart;
                    
                    

                    $variants = array();
                    $vInde = 0;
                    if(!empty($productVariantData)){
                        foreach ($productVariantData as $vkey => $variant_row)
                        {
                            $priceFinal = 0;
                            $priceFinalData = DB::table('price_list_items as PI')
                                        ->join('price_lists as P','P.id','PI.price_list_id')
                                        ->where('P.is_active','Yes')
                                        ->where('PI.price_list_id',$priseListId)
                                        ->where('PI.product_id',$row['id'])
                                        ->where('PI.variant_id',$variant_row->variant_id)
                                        ->first();
                            if($priceFinalData){
                                $priceFinal = $priceFinalData->rate ?? 0;
                            }else{
                                continue;
                            }

                            $is_variant_cart = ($is_cart == 1 && (!empty($variant_cart))) ? 1 : 0;
                            
                            if($variant_row->cart_customer_id == $customer_id)
                            {   
                                $qty = $variant_row->qty;
                            }else{
                                $qty = '0';
                            }

                            
                            $variants[$vInde]=array(
                                'product_id' => $variant_row->product_id ?? '', 
                                'product_variant_id' => $variant_row->product_variant_id ?? '', 
                                'variant_id' => $variant_row->variant_id ?? '',
                                'variant_name' => $variant_row->variant_name,
                                'bandha_pouch_qty' => $variant_row->bandha_pouch_qty, 
                                'qty' => $qty ?? "0", 
                                'price' => (string)$variant_row->price ?? "0", 
                                'final_price' => (string)$priceFinal ?? "0",
                                'customer_id' => $customer_id, 
                                'is_variant_cart' => $is_variant_cart,
                                'minimum_stock_qty' => $variant_row->min_stock_qty, 
                                'weight' => $variant_row->weight,
                                'opening_stock_qty' => 0,
                                'description' => $variant_row->description,
                            );
                            $vInde = $vInde + 1;
                        }
                    }

                    $toReturn[$index]['variants'] = $variants;
                    $index = $index + 1;
                }
            }

            $data = collect($toReturn);

            $dataPerPage = $data->forPage($page, $perPage);
            $dataPerPage = array_values($dataPerPage->toArray());

            $result = new LengthAwarePaginator(
                $dataPerPage,
                $data->count(),
                $perPage, //length
                $page
            );
            $this->data = $result;

        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccessWithoutObject();
    }

    public function editCustomer(Request $request)
    {
        try {

            $requestData = Validator::make($this->request->all(), [
                'customer_id' => 'required',
            ]);
            if ($requestData->fails()) {
                throw new Exception($requestData->messages()->first(), 1);
            }
            $customerId = $request->customer_id;

            // $path = URL::asset('');

            $result = DB::table("customers as C")
                    ->select([
                        'C.id',
                        DB::raw("(CASE WHEN C.company_name IS NOT NULL THEN  C.company_name ELSE '' END) as company_name"),
                        DB::raw("(CASE WHEN C.person_name IS NOT NULL THEN  C.person_name ELSE '' END) as person_name"),
                        DB::raw("(CASE WHEN C.mobile IS NOT NULL THEN  C.mobile ELSE '' END) as mobile"),
                        DB::raw("(CASE WHEN C.email IS NOT NULL THEN  C.email ELSE '' END) as email"),
                        DB::raw("(CASE WHEN C.gst_no IS NOT NULL THEN  C.gst_no ELSE '' END) as gst_no"),
                        DB::raw("(CASE WHEN CA.address_line1 IS NOT NULL THEN  CA.address_line1 ELSE '' END) as address_line1"),
                        DB::raw("(CASE WHEN CA.address_line2 IS NOT NULL THEN  CA.address_line2 ELSE '' END) as address_line2"),
                        DB::raw("(CASE WHEN CA.pincode IS NOT NULL THEN  CA.pincode ELSE '' END) as pincode"),
                        DB::raw("(CASE WHEN CA.city_id IS NOT NULL THEN  CA.city_id ELSE '' END) as city_id"),
                        DB::raw("(CASE WHEN CA.state_id IS NOT NULL THEN  CA.state_id ELSE '' END) as state_id"),
                        DB::raw("(CASE WHEN CA.country_id IS NOT NULL THEN  CA.country_id ELSE '' END) as country_id"),
                        DB::raw("(CASE WHEN CI.name IS NOT NULL THEN  CI.name ELSE '' END) as city_name"),
                        DB::raw("(CASE WHEN ST.name IS NOT NULL THEN  ST.name ELSE '' END) as state_name"),
                        DB::raw("(CASE WHEN CC.name IS NOT NULL THEN  CC.name ELSE '' END) as country_name"),
                        DB::raw("'-' as photo"),
                    ])
                    ->leftjoin("customer_addresses as CA", 'CA.customer_id', '=', 'C.id')
                    ->leftjoin('cities as CI','CI.id', '=', 'CA.city_id')
                    ->leftjoin('states as ST','ST.id', '=', 'CA.state_id')
                    ->leftjoin('countries as CC','CC.id', '=', 'CA.country_id')
                    ->where('C.id',$customerId)
                    ->whereNull('C.deleted_at')
                    ->first();

            

            $this->data = $result;

        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccessWithoutObject();
    }
}

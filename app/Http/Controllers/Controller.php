<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\{Location,Customer,Employee,Setting,Category,Product,Banner,Designation};
use Carbon\Carbon;
use Sentinel;
use View;
use DB;
use URL;
use AppHelper;
use Session;
use Config;
use DateTimeImmutable;
use Illuminate\Support\Facades\Auth;

// use Imagick;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    protected $user;

    public function __construct($arguments = null)
    {
        $this->middleware(function ($request, $next) {
            $this->user = Sentinel::getUser();
            if ($this->user != null) {
                view()->share('current_user', $this->user);
                view()->share('current_user_name', $this->user->first_name . ' ' . $this->user->last_name);
            }
            if ($this->user != null) {
                $current_time = Carbon::now();
                $last_login = Carbon::parse($this->user->last_login);
                if ($last_login->diffInSeconds($current_time) <= 10) {
                    $dataTableBuilders[] = "DataTables_dataTableBuilder_/production-planning/new-index";
                    $dataTableBuilders[] = "DataTables_dataTableBuilder_/raw-production-planning";
                    $dataTableBuilders[] = "DataTables_dataTableBuilder_/purchase_order";
                    $dataTableBuilders[] = "DataTables_dataTableBuilder_/quotation_rawmaterial";
                    $dataTableBuilders[] = "DataTables_dataTableBuilder_/quotation_new";
                    $dataTableBuilders[] = "DataTables_dataTableBuilder_/pos";
                    view()->share('dataTableBuilders', $dataTableBuilders);
                }
            }
            return $next($request);
        });
        view()->share('theme', 'app');
        if (request()->input('download', false)) {
            View::share('theme', 'limitless.ajax');
        }
        $form_submit_seconds = Config::get('srtpl.settings.form_submit_seconds', 5);
        // Setting::where('name', 'form_submit_seconds')->pluck('value')->first()
        View::share('form_submit_seconds', $form_submit_seconds);

        $months =  [
            "1" => "January", "February", "March", "April",
            "May", "June", "July", "August",
            "September", "October", "November", "December"
        ];
        $today = Carbon::now();
        $today->useMonthsOverflow(false);
        $yestarday = Carbon::now()->subDay('1');
        $currMonth = $today->format('m');
        $currMonthName = $today->format('F');
        $currYear = $today->format('Y');
        $day_range = [];
        // this month will from date [first day of month], to date [last day of month]
        $from_date = new Carbon('first day of ' . $currMonthName . ' ' . $currYear);
        $to_date = new Carbon('last day of ' . $currMonthName . ' ' . $currYear);
        $from_date->useMonthsOverflow(false);
        $to_date->useMonthsOverflow(false);
        $first_day_of_month = $from_date->format('d-m-Y');
        $last_day_of_month = $today->format('d-m-Y');
        $day_range['this_month'] = ['from_date' => $first_day_of_month, 'to_date' => $last_day_of_month];

        // last month will from date [first day of month-1month], to date [last day of month1month]
        $lastMonth = $currMonth;
        $lastYear = $currYear;
        if ($currMonth > 1) {
            $lastMonth = $currMonth - 1;
        } else {
            $lastMonth = 12;
            $lastYear = $currYear - 1;
        }
        $lastMonthName = $months[$lastMonth];
        $last_month_from_date = new Carbon('first day of ' . $lastMonthName . ' ' . $lastYear);
        $last_month_to_date = new Carbon('last day of ' . $lastMonthName . ' ' . $lastYear);


        $last_month_first_day = $last_month_from_date->format('d-m-Y');
        $last_month_last_day = $last_month_to_date->format('d-m-Y');
        $day_range['last_month'] = ['from_date' => $last_month_first_day, 'to_date' => $last_month_last_day];

        // last 3 month
        $lastMonthName = $months[$lastMonth];
        $last_month_from_date = new Carbon('first day of ' . $lastMonthName . ' ' . $lastYear);
        $last_month_to_date = new Carbon('last day of ' . $lastMonthName . ' ' . $lastYear);
        $last_3_month_from_date = $from_date->subMonth(3)->format('d-m-Y');
        $last_3_month_to_date = $last_month_to_date->format('d-m-Y');
        $day_range['last_3_month'] = ['from_date' => $last_3_month_from_date, 'to_date' => $last_3_month_to_date];

        // this week will from date [first day of week], to date [last day of week] Wednesday as last day
        $today->setWeekStartsAt(Carbon::THURSDAY);
        $today->setWeekEndsAt(Carbon::WEDNESDAY);
        $week = clone $today;
        $thisWeekFromDate = $week->startOfWeek(Carbon::THURSDAY)->format('d-m-Y');
        $thisWeekToDate = $week->endOfWeek(Carbon::THURSDAY)->format('d-m-Y');
        $day_range['this_week'] = ['from_date' => $thisWeekFromDate, 'to_date' => $thisWeekToDate];

        // last week will from date [first day of week-1week], to date [last day of week-1week] Wednesday as last day
        $lastWeek = clone $today;
        $last_week = $lastWeek->subWeek(1);
        $lastWeekFromDate = $last_week->startOfWeek(Carbon::THURSDAY)->format('d-m-Y');
        $lastWeekToDate = $last_week->endOfWeek(Carbon::THURSDAY)->format('d-m-Y');

        // this year will be from date[current year finacial date], to date [current year finacial end date]
        /*
        $current_year = Session::get('default_year');
        $prev_year = Year::where('id','<', $current_year->id)->orderBy('id', 'desc')->first();
        $day_range['last_week'] = ['from_date'=>$lastWeekFromDate,'to_date'=>$lastWeekToDate];
        $day_range['this_year'] = ['from_date'=>$current_year->from_date,'to_date'=>$current_year->to_date];
        if($prev_year){
            $day_range['prev_year'] = ['from_date'=>$prev_year->from_date,'to_date'=>$prev_year->to_date];
        }
        */
        // half year
        $todayFormat = $today->format('d-m-Y');
        /*
        $currYearStartDate = Carbon::parse($current_year->from_date);
        $currYearStartDateYear = $currYearStartDate->format('Y');
        if($currMonth >= 10 || $currMonth <= 3){
            $firstDayHalfYear = new Carbon('first day October '.$currYearStartDateYear);
            $day_range['half_year'] = ['from_date'=>$firstDayHalfYear->format('d-m-Y'),'to_date'=>$todayFormat];
        }else{
            $day_range['half_year'] = ['from_date'=>$current_year->from_date,'to_date'=>$todayFormat];
        }
        */

        // today will be from and to date will be current date

        $day_range['today'] = ['from_date' => $todayFormat, 'to_date' => $todayFormat];

        // yestarday will be from yesterday date, to date=yesterday date
        $day_range['yestarday'] = ['from_date' => $yestarday->format('d-m-Y'), 'to_date' => $yestarday->format('d-m-Y')];
        view()->share('day_range', $day_range);

        // view()->share('all_year', $all_year);

        // Location Code
        // $all_locations = Location::where('status', 'Active')->pluck('name','id')->toArray();
        // view()->share('all_locations', $all_locations);

        // $rmQC = QualityCheck::select()->where('type','RawMaterial')->where('form_type', 'Main')->first();
        // view()->share('rmQC', $rmQC);

        // AppHelper::setDefaultImage('uploads/default/default.jpg');
        DB::connection()->enableQueryLog();
        setlocale(LC_MONETARY, 'en_IN');

        // Remove wkhtmltopdf-0-12-5 as wkhtml updated to 0-12-6l
        $qc_routes = [
            'qualitycheck', 'qc_rawmaterial',
            'qc_printing', 'qc_ecl', 'qc_slitting', 'qc_pouching', 'qc_certificate',
            'quotation_new', 'account_ledger'
        ];
        $segment = request()->segment(1);
        if (in_array($segment, $qc_routes)) {
            Config::set('snappy.pdf.binary', env('WKHTMLTOPDF', '/usr/local/bin/wkhtmltopdf'));
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess()
    {
        $this->response_json['data'] = (object)$this->data;
        $this->response_json['status'] = 1;
        return response()->json($this->response_json, 200);
    }
    public function responseSuccessWithoutObject()
    {
        $this->response_json['data'] = $this->data;
        $this->response_json['status'] = 1;
        return response()->json($this->response_json, 200);
    }
    public function responseSuccessPagination()
    {
        $this->response_json = $this->data;
        $this->response_json['status'] = 1;
        return response()->json($this->response_json, 200);
    }
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccessWithoutDataObject()
    {
        $this->response_json['status'] = 1;
        return response()->json($this->response_json, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError()
    {
        if (count($this->data)) {
            $this->response_json['data'] = $this->data;
        }
        $this->response_json['status'] = 0;
        return response()->json($this->response_json, 200);
    }

    public function responseSuccesswithMessage()
    {
        if (count($this->data)) {
            $this->response_json['data'] = $this->data;
        }
        if (!isset($this->response_json['status']))
            $this->response_json['status'] = 1;

        // Log::alert('response' , $this->data);
        return response()->json($this->response_json, 200);
    }
    public function currentuser()
    {
        if (Auth::check()) {
            $user = Auth::user();
            // dd($user);

            $user->id = $user->id;
            $user->mobile_no_1 = $user->mobile ?? '';
            if ($user->emp_type == 'customer') {
                $customer = Customer::select('id','person_name','mobile')->where('id', $user->customer_id)->first();
                $user->login_id = (!empty($customer)) ? $customer->id : '';
                $user->person_name = (!empty($customer)) ? $customer->person_name : '';                  
            }
            if ($user->emp_type == 'employee') {
                // $salesmen = Salesmans::select('id','first_name','middle_name','last_name','mobile_1')->where('user_id', $user->id)->first();
                $employee = Employee::select('id','person_name','mobile')->where('id', $user->emp_id)->first();
                $user->login_id = (!empty($employee)) ? $employee->id : '';
                // $user->first_name = (!empty($employee)) ? $employee->first_name : '';
                // $user->middle_name = (!empty($employee)) ? $employee->middle_name : '';
                // $user->last_name = (!empty($employee)) ? $employee->last_name : '';
            }
            return $user;
        } else {
            return false;
        }
    }
    public function userCollection($user)
    {

        $userType = $user->emp_type ?? '';
        if($userType == 'customer'){
            $userTypeId = 1;
            $salesmanAsm = 'No';
        }else if($userType == 'employee'){
            $userTypeId = 2;
            $empDesId = Employee::where('id', $user->emp_id)->first()->designation_id; 
            $desgId = Designation::where('slug', 'area_sales_manager')->first()->id;
            if($empDesId == $desgId)
            {
                $salesmanAsm = 'Yes';
            }else{
                $salesmanAsm = 'No';
            }
            // $is_asm = Employee::where('id', $user->emp_id)->first()->is_asm;
        }else{
            $userTypeId = 3;
            $salesmanAsm = 'No';
        }
        return collect([
            'id' => $user->id ?? '',
            'name' => $user->full_name ?? '',
            'mobile' => $user->mobile ?? '',
            'login_id' => isset($user->emp_id) ? ($user->emp_id ?? '') : ($user->customer_id ?? ''),
            'user_type' => $userTypeId,
            'user_type_name' => $user->emp_type ?? '',
            'email' => $user->email ?? '',
            'is_active' => $user->is_active ?? '',
            'salesman_asm' => $salesmanAsm,
            'access_token' => $user->createToken('MNS Mahalaxmi Token for customer and salesmen login with secure')->accessToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function responseSuccessWithoutObjectNew()
    {
        $this->response_json['data'] = $this->data;
        $this->response_json['status'] = 99;
        return response()->json($this->response_json, 200);
    }


    public function getSettingData()
    {
        $settings = Setting::whereIn('name', ['android_version','ios_version','company_brochure'])->get()->toArray();
    
        $android_version = array_reduce(array_filter($settings, function($val, $key){
            return ($val['name'] == 'android_version');
        },ARRAY_FILTER_USE_BOTH), 'array_merge', array());

        $ios_version = array_reduce(array_filter($settings, function($val, $key){
            return ($val['name'] == 'ios_version');
        },ARRAY_FILTER_USE_BOTH), 'array_merge', array());

        $catalog = array_reduce(array_filter($settings, function($val, $key){
            return ($val['name'] == 'company_brochure');
        },ARRAY_FILTER_USE_BOTH), 'array_merge', array());

        return [
                'android_version' => (!empty($android_version)) ? (int)$android_version['value'] : 0,
                'ios_version' => (!empty($ios_version)) ? (int)$ios_version['value'] : 0,
                'catalog' => (!empty($catalog)) ? URL::asset('').'/'.$catalog['value'] : '',
            ];
    }

    public function getBanner(){

        try {
            $img_path = URL::asset('');
            $banner_data = Banner::select('id','title', DB::raw("CONCAT('".$img_path."/', image) as banner_image"))->where('is_active','Yes')->get();
        } catch (Exception $e) {
            $banner_data = [];
        }

        return $banner_data;
    }

    public function getCategory()
    {
        try {
            
            $category_path = URL::asset('');
            $result = Category::where(['c_type' => 'product_category','is_active' => 'Yes'])->select(['id','name','category_image'])->get();
            $category_data = [];
            if(!empty($result)){

                foreach ($result as $key => $row) {
                    $category_data[$key]['id'] = $row['id'];
                    $category_data[$key]['index'] = $key;
                    $category_data[$key]['name'] = $row['name'];
                    // $category_data[$key]['parent_id'] = $row['parent_id'];
                    $category_data[$key]['category_image'] = (!empty($row['category_image'])) ? $category_path.''.$row['category_image'] : '';
                }

            }
        } catch (Exception $e) {
            $category_data = [];
        }
        return $category_data;
    }

    public function getProduct($customer_id)
    {
        try {

            $product_path = URL::asset('');

            $start = 0;
            $limit = config('global.pagination_records'); 
                       
            $sorting = '';
            
            
            $result = Product::where('products.is_active','Yes')
                        ->select([
                            'products.id',
                            'products.product_name',
                            'products.image',
                            'products.category_id',
                            'products.hsncode_id',
                            'hsn_codes.gst'
                        ])
                        ->leftJoin('hsn_codes','hsn_codes.id','products.hsncode_id')
                        // ->where('products.id',60)
                        ->orderByRaw('RAND()')
                        ->get();
            $priseListId = DB::table('customers')->where('id',$customer_id)->first()->price_list_id ?? '';

            $product_data = array();
            if(!empty($result)){
                $index = 0;

                foreach ($result as $key => $row) 
                {
                    $productShow = DB::table('price_list_items as PI')
                                        ->where('PI.price_list_id',$priseListId)
                                        ->where('PI.product_id',$row->id)
                                        ->first();
                    if($productShow == null){
                        continue;
                    }
                    $catName = DB::table('categories')->where('id',$row->category_id)->first() ?? '';
                    $prodId = $row->id;

                    $productVariantData = DB::table('product_variants as PV')
                                            ->select([
                                                'PV.product_id as product_id',
                                                'PV.variant_id as variant_id',
                                                'PV.price as price',
                                                'PV.id as product_variant_id',
                                                'PV.bandha_pouch_qty as bandha_pouch_qty',
                                                'V.name as variant_name',
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


                    $product_data[$index]['id'] = $row->id ?? '';
                    $product_data[$index]['product_name'] = $row->product_name ?? '';
                    $product_data[$index]['hsncode_id'] = $row->hsncode_id ?? '';
                    $product_data[$index]['gst'] = $row->gst ?? '';
                    $product_data[$index]['image'] = (!empty($row->image)) ? $product_path.''.$row->image : '';
                    $product_data[$index]['category_id'] = $row->category_id ?? '';
                    $product_data[$index]['category_name'] = $catName->name ?? '';
                    $product_data[$index]['is_wishlist'] = $is_wishlist;
                    $product_data[$index]['is_cart'] = $is_cart;
                    
                    
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
                                        ->where('PI.product_id',$row->id)
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
                                // 'is_variant_cart' => $is_variant_cart, 
                                'qty' => $qty ?? "0", 
                                'price' => (string)$variant_row->price ?? "0", 
                                'final_price' => (string)$priceFinal ?? "0",
                                'customer_id' => $customer_id, 
                            );

                            $vInde = $vInde + 1;
                        }
                    }

                    $product_data[$index]['variants'] = $variants;
                    $index = $index + 1;
                }
            }            
            // $this->response_json['total_page'] = ceil(($total_row > $limit) ? $total_row / $limit : 1);
        } catch (Exception $e) {
            $product_data = [];
        }
        return collect($product_data);
    }

    public function reportDecimalValues()
    {
        // use for report decimal values
        $decimalQty = 1;
        $defaultQty = '0.0';
        $decimalAmount = 2;
        $defaultAmount = '0.00';
        return [
            'decimalQty' => $decimalQty,
            'defaultQty' => $defaultQty,
            'decimalAmount' => $decimalAmount,
            'defaultAmount' => $defaultAmount,
        ];
    }
}

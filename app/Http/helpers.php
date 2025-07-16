<?php

use Carbon\Carbon;
use App\Models\Country;
use App\Models\{Year, Transfer,SalesOrderItem,Branch,BranchTransfer,Setting,Employee,
    Series,
    User};
use Carbon\CarbonPeriod;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

// use Config;
/**
 *
 * @param type $change_dropdown
 * @param type $replace_dropdown
 * @param type $url
 * @param type $empty
 * @return string
 */
if (!function_exists('ajax_fill_dropdown')) {
    function ajax_fill_dropdown($change_dropdown, $replace_dropdown, $url, $empty = [], $first_remove = null)
    {
        $html = '<script type="text/javascript">';

        $html .= 'jQuery(document).ready(function($) {';
        $html .= 'jQuery("select[name=\'' . $change_dropdown . '\']").change(function(e){';
        $html .= 'addLoadSpiner(jQuery("select[name=\'' . $replace_dropdown . '\']"));';
        $html .= 'jQuery.ajax({';
        $html .= 'type: "POST",';
        $html .= 'url: "' . $url . '",';
        $html .= 'dataType:"json",';
        $html .= 'data: jQuery(this).parents("form").find("input,select[name=\'' . $change_dropdown . '\']").serialize(),';
        $html .= 'success:function(data){';
        if (empty($first_remove)) {
            $html .= '    jQuery("select[name=\'' . $replace_dropdown . '\']").find("option:not(:first)").remove();';
        } else {
            $html .= '    jQuery("select[name=\'' . $replace_dropdown . '\']").find("option").remove();';
        }
        if (!empty($empty)) {
            foreach ($empty as $key => $emt) {
                $html .= '    jQuery("select[name=\'' . $emt . '\']").find("option:not(:first)").remove();';
            }
        }
        $html .= 'var sortedData = Object.entries(data).sort(function(a, b) {';
        $html .= 'return a[1].localeCompare(b[1]);';
        $html .= '});';
    
        $html .= 'jQuery.each(sortedData, function(index, entry){';
        $html .= 'jQuery("select[name=\'' . $replace_dropdown . '\']").append(\'<option value="\'+entry[0]+\'">\'+entry[1]+\'</option>\');';
        $html .= '});';
        $html .= 'hideLoadSpinner(jQuery("select[name=\'' . $replace_dropdown . '\']"));';
        $html .= '}';
        $html .= '});';
        $html .= '});';
        $html .= '});';
        $html .= '</script>';
        return $html;
    }
}

if (!function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        return Arr::get($array, $key, $default);
    }
}

/**
 *
 * @param type $type
 * @param type $base64
 * @param type $alt
 * @param array $attributes
 * @return type
 */
if (!function_exists('imgBase64')) {
    function imgBase64($type, $base64, $alt = null, $attributes = [])
    {
        $attributes['alt'] = $alt;
        $attrib = '';
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $attrib .= ' ' . $key . '="' . $value . '"';
            }
        }
        return '<img src="' . $type . ';base64,' . $base64 . '"' . $attrib . '>';
    }
}

/**
 *
 * @param type $code
 * @param type $density
 * @param type $top_txt
 * @param type $is_bottom_code
 * @return type
 */

/*
 * @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @author  Simon Franz
 * @author  Deadfish
 * @author  SK83RJOSH
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
 * @link      http://kevin.vanzonneveld.net/
 *
 * @param mixed   $in     String or long input to translate
 * @param boolean $to_num  Reverses translation when true
 * @param mixed   $pad_up  Number or boolean padds the result up to a specified length
 * @param string  $pass_key Supplying a password makes it harder to calculate the original ID
 *
 * @return mixed string or long
 */
function alphaID($in, $to_num = false, $pad_up = false, $pass_key = null)
{
    $out = '';
    //$index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $index = 'm7t5xmkoa6yjr5phn2i29xb47a60vp8lk4xd';
    $base = strlen($index);

    if ($pass_key !== null) {
        // Although this function's purpose is to just make the
        // ID short - and not so much secure,
        // with this patch by Simon Franz (http://blog.snaky.org/)
        // you can optionally supply a password to make it harder
        // to calculate the corresponding numeric ID

        for ($n = 0; $n < strlen($index); $n++) {
            $i[] = substr($index, $n, 1);
        }

        $pass_hash = hash('sha256', $pass_key);
        $pass_hash = (strlen($pass_hash) < strlen($index) ? hash('sha512', $pass_key) : $pass_hash);

        for ($n = 0; $n < strlen($index); $n++) {
            $p[] = substr($pass_hash, $n, 1);
        }

        array_multisort($p, SORT_DESC, $i);
        $index = implode($i);
    }

    if ($to_num) {
        // Digital number  <<--  alphabet letter code
        $len = strlen($in) - 1;

        for ($t = $len; $t >= 0; $t--) {
            $bcp = pow($base, $len - $t);
            $out = $out + strpos($index, substr($in, $t, 1)) * $bcp;
        }

        if (is_numeric($pad_up)) {
            $pad_up--;

            if ($pad_up > 0) {
                $out -= pow($base, $pad_up);
            }
        }
    } else {
        // Digital number  -->>  alphabet letter code
        if (is_numeric($pad_up)) {
            $pad_up--;

            if ($pad_up > 0) {
                $in += pow($base, $pad_up);
            }
        }

        for ($t = ($in != 0 ? floor(log($in, $base)) : 0); $t >= 0; $t--) {
            $bcp = pow($base, $t);
            $a = floor($in / $bcp) % $base;
            $out = $out . substr($index, $a, 1);
            $in = $in - ($a * $bcp);
        }
    }

    return $out;
}
if (!function_exists('timetoseconds')) {
    function timetoseconds($str_time)
    {
        $time = Carbon::createFromFormat('H:i', $str_time);
        $time_seconds = $time->secondsSinceMidnight();
        return $time_seconds;
        //$time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hour;
    }
}
function format_amount($value, $precision = null)
{
    setlocale(LC_MONETARY, 'en_IN');
    if (is_null($precision)) {
        $precision = '0';
    } else if ($precision == 1) {
        $precision = '0.0';
    } else if ($precision == 2) {
        $precision = '0.00';
    } else if ($precision == 3) {
        $precision = '0.000';
    } else {
        $precision = '0';
    }
    if (!empty($value)) {
        $fmt = new NumberFormatter('en_IN', NumberFormatter::DECIMAL);
        $fmt->setPattern("#,##,##" . $precision);
        return $fmt->format($value);
    }
    return number_format(0, $precision);
}

if (!function_exists('money_format')) {
    function money_format($str, $value, $precision = 2)
    {
        return number_format($value, $precision);
    }
}

if (!function_exists('CheckHoursDiff')) {
    /**
     * Check houtres Diffrence
     *
     * @param  date   $created_at
     * @return mixed
     */
    function CheckHoursDiff($created_at, $status = null)
    {
        $user = Sentinel::getUser();
        if (Carbon::now()->diffInHours($created_at) < Config::get('srtpl.settings.edit_past_entry_time', 12) || $user->hasAnyAccess(['settings.allow_past_entry_edit', 'users.superadmin'])) {
            return true;
        }
        if ($status == "Pending") {
            return true;
        }
        return false;
    }
}
function compareFloatNumbers($float1, $float2, $operator = '=')
{
    // Check numbers to 5 digits of precision
    $epsilon = 0.00001;
    $float1 = (float) $float1;
    $float2 = (float) $float2;
    $operator = trim($operator);
    switch ($operator) {
            // equal
        case '=':
        case 'eq': {
                if (abs($float1 - $float2) < $epsilon) {
                    return true;
                }
                break;
            }
            // less than
        case '<':
        case 'lt': {
                if (abs($float1 - $float2) < $epsilon) {
                    return false;
                } else {
                    if ($float1 < $float2) {
                        return true;
                    }
                }
                break;
            }
            // less than or equal
        case '<=':
        case 'lte': {
                if (compareFloatNumbers($float1, $float2, '<') || compareFloatNumbers($float1, $float2, '=')) {
                    return true;
                }
                break;
            }
            // greater than
        case '>':
        case 'gt': {
                if (abs($float1 - $float2) < $epsilon) {
                    return false;
                } else {
                    if ($float1 > $float2) {
                        return true;
                    }
                }
                break;
            }
            // greater than or equal
        case '>=':
        case 'gte': {
                if (compareFloatNumbers($float1, $float2, '>') || compareFloatNumbers($float1, $float2, '=')) {
                    return true;
                }
                break;
            }
        case '<>':
        case '!=':
        case 'ne': {
                if (abs($float1 - $float2) > $epsilon) {
                    return true;
                }
                break;
            }
        default: {
                die("Unknown operator '" . $operator . "' in compareFloatNumbers()");
            }
    }
    return false;
}
if (!function_exists('emptyMethod')) {
    /**
     * Check houtres Diffrence
     *
     * @param  date   $created_at
     * @return mixed
     */
    function emptyMethod($created_at)
    {
        return true;
    }
}
if (!function_exists('calculateDays')) {
    /**
     * Check houtres Diffrence
     *
     * @param  date   $created_at
     * @return mixed
     */
    function calculateDays($data)
    {
        $attendance_types = config('srtpl.attendance_types');
        $days = ['H' => 0, 'P' => 0, 'A' => 0, 'L' => 0, 'HP' => 0];
        $dataArr = json_decode($data, true);
        if ($dataArr && is_array($dataArr) && count($dataArr)) {
            foreach ($dataArr as $key => $value) {
                if (!isset($attendance_types[$value])) continue;
                $days['H'] += $attendance_types[$value]['H'];
                $days['P'] += $attendance_types[$value]['P'];
                $days['A'] += $attendance_types[$value]['A'];
                $days['L'] += $attendance_types[$value]['L'];
                if ($value == "HP") {
                    $days['HP'] += 1;
                }
            }
        }
        return $days;;
    }
}
if (!function_exists('calculateWeekOff')) {
    /**
     * Check houtres Diffrence
     *
     * @param  date   $created_at
     * @return mixed
     */
    function calculateWeekOff($month, $year, $day = 'Wednesday')
    {
        $months = $month;
        $years = $year;
        $monthName = date("F", mktime(0, 0, 0, $months));
        $fromdt = date('Y-m-01 ', strtotime("First Day Of  $monthName $years"));
        $todt = date('Y-m-d ', strtotime("Last Day of $monthName $years"));

        $num_weekoff = 0;
        for ($i = 0; $i < ((strtotime($todt) - strtotime($fromdt)) / 86400); $i++) {
            if (date('l', strtotime($fromdt) + ($i * 86400)) == $day) {
                $num_weekoff++;
            }
        }
        return $num_weekoff;;
    }
}
function convertToIndianCurrency($number = 0)
{
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety',
        100 => 'Hundred',
    );
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str[] = null;
        }
    }
    $Rupees = implode(' ', array_reverse($str));
    $paise = ($decimal) ? "And " . ($words[$decimal - $decimal % 10]) . " " . ($words[$decimal % 10]) . " Paise"  : '';
    if ($paise == "And Hundred  Paise") {
        $paise = "";
    }
    return ($Rupees ? $Rupees . " Rupees " : '') . $paise . " Only";
}
if (!function_exists('timeToFloat')) {
    function timeToFloat($str_time = '', $working_hours = 8)
    {
        $time = Carbon::createFromFormat('H:i', $str_time);
        $timeToFloat = $time->hour + round($time->minute / 60, 2);
        $work_hours = round(($timeToFloat / $working_hours), 2);
        if ($work_hours && $work_hours <= 0.25) {
            $work_hours = 0.25;
        } else {
            $work_hours = round($work_hours * 2) / 2;
        }
        return $work_hours;
    }
    function statusHelper($status = 'default')
    {
        switch ($status) {
            case 'success':
                $result = '<i class="color-success fa fa-check-circle" aria-hidden="true"></i>';
                return $result;
                break;
            case 'default':
                $result = '<i class="color-default fa fa-meh-o" aria-hidden="true"></i>';
                return $result;
                break;
            case 'notice':
                $result = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
                return $result;
                break;
            case 'error':
                $result = '<i class="color-error fa fa-exclamation-circle" aria-hidden="true"></i>';
                return $result;
                break;
            case 'info':
                $result = '<i class="color-info fa fa-info-circle" aria-hidden="true"></i>';
                return $result;
                break;
            default:
                $result = '<i class="color-success fa fa-smile-o" aria-hidden="true"></i>';
                return $result;
                break;
        }
    }
}
function countrySelector($defaultCountry = "", $id = "", $name = "", $classes = "")
{
    // $countryArray = config('project.country_codes');
    $countries = Country::select('id', 'country', 'code', 'phone_code')->get();
    $output = "<select id='" . $id . "' name='" . $name . "' class='" . $classes . "'>";
    $output .= "<option value=''>Select Country</option>";
    foreach ($countries as $country) {
        $countryName = ucwords(strtolower($country->country)); // Making it look good
        // $country->code." - ".
        $output .= "<option value='" . $country->id . "' " . (($country->id == $defaultCountry) ? "selected" : "") . ">" . $countryName . " (+" . $country->phone_code . ")</option>";
    }
    $output .= "</select>";
    return $output; // or echo $output; to print directly
}

if (!function_exists('getStatusHtml')) {
    function getStatusHtml($row, $permission = null)
    {
        $user = Sentinel::getUser();

        if (isset($permission) && !$user->hasAnyAccess([$permission, 'users.superadmin'])) {
            return $row->is_active == "Yes" ? "Active" : "Inactive";
        }

        $statusHtml = "";
        $disabled = "";
        $url = route('common.change-status', [$row->id]);
        $table =  $row->getTable();
        if($table == 'users'){
            $type = $row->emp_type ?? '';
            if($type == 'customer' || $type == 'employee'){
                $disabled = 'disabled';
            }
        }
        $checked = '';
        if (strtoupper($row->is_active) == 'YES' && $row->is_active !== NULL) {
            $checked = "checked";
        }
        $statusHtml = '<div class="text-center">
            <span class="switch switch-icon switch-md">
                <label>
                    <input type="checkbox" ' . $disabled . ' class="change-status" id="status_' . $row->id . '" name="status_' . $row->id . '" data-url="' . $url . '" data-table="' . $table . '" value="' . $row->id . '" ' . $checked . '>
                    <span></span>
                </label>
            </span>
            </div>';
        return $statusHtml;
    }
}

if (!function_exists('getImageHtml')) {
    function getImageHtml($row)
    {
        $imageHtml = "";
        $imageHtml = '<div class="left-center">
            <img class="" src="' . asset('') . '' . $row->rmcategory_image . '" style="max-width:100%;width:100px;min-height:auto;" />
            </div>';
        return $imageHtml;
    }
}
if (!function_exists('getSubImageHtml')) {
    function getSubImageHtml($row)
    {
        $imageHtml = "";
        $imageHtml = '<div class="left-center">
            <img class="" src="' . asset('') . '' . $row->rmsubcategory_image . '" style="max-width:100%;width:100px;min-height:auto;" />
            </div>';
        return $imageHtml;
    }
}
if (!function_exists('editHtmlCategoryName')) {
    function editHtmlCategoryName($row)
    {
        $childName = $row->ascendants->reverse()->pluck('name')->toArray();
        $categorynameHtml = "";
        if (($row->parent_id == 0)) {
            $categorynameHtml .= "<p> " . $row->name . " </p>";
        } else {
            $childName = implode(" ", $childName);
            $categorynameHtml .= "<p> " . $row->name . "( " . $childName . " ) </p>";
        }
        return $categorynameHtml;
    }
}
if (!function_exists('getEmployeeStatusHtml')) {
    function getEmployeeStatusHtml($row, $permission = null)
    {
        $user = Sentinel::getUser();

        if (isset($permission) && !$user->hasAnyAccess([$permission, 'users.superadmin'])) {
            return $row->is_active == "Yes" ? "Active" : "Inactive";
        }

        $statusHtml = "";
        $url = route('common.change-status', [$row->id]);
        $table =  $row->getTable();
        $checked = '';
        $disabled = '';
        if (strtoupper($row->is_active) == 'YES' && $row->is_active !== NULL) {
            $checked = "checked";
        }
        if ((isset($row->left_date) && $row->left_date != '00-00-0000')) {
            $disabled = "disabled";
        }
        $statusHtml = '<div class="text-center">
            <span class="switch switch-icon switch-md">
                <label>
                    <input type="checkbox" class="change-employee-status" id="status_' . $row->id . '" name="status_' . $row->id . '" data-url="' . $url . '" data-table="' . $table . '" value="' . $row->id . '" ' . $checked . ' ' . $disabled . '>
                    <span></span>
                </label>
            </span>
            </div>';
        return $statusHtml;
    }
}

if (!function_exists('getCustomerStatusHtml')) {
    function getCustomerStatusHtml($row, $permission = null)
    {
        dd($row->getTable());
        $user = Sentinel::getUser();

        if (isset($permission) && !$user->hasAnyAccess([$permission, 'users.superadmin'])) {
            return $row->is_active == "Yes" ? "Active" : "Inactive";
        }

        $statusHtml = "";
        $url = route('common.change-status', [$row->id]);
        $table =  $row->getTable();
        $checked = '';
        $disabled = '';
        if (strtoupper($row->is_active) == 'YES' && $row->is_active !== NULL) {
            $checked = "checked";
        }
        if ((isset($row->left_date) && $row->left_date != '00-00-0000')) {
            $disabled = "disabled";
        }
        $statusHtml = '<div class="text-center">
            <span class="switch switch-icon switch-md">
                <label>
                    <input type="checkbox" class="change-customer-status" id="status_' . $row->id . '" name="status_' . $row->id . '" data-url="' . $url . '" data-table="' . $table . '" value="' . $row->id . '" ' . $checked . ' ' . $disabled . '>
                    <span></span>
                </label>
            </span>
            </div>';
        return $statusHtml;
    }
}

if (!function_exists('getDynamicStatusHtml')) {
    function getDynamicStatusHtml($row, $url = '', $classname = '', $permission = null)
    {
        $user = Sentinel::getUser();

        if (isset($permission) && !$user->hasAnyAccess([$permission, 'users.superadmin'])) {
            return $row->is_active == "Yes" ? "Active" : "Inactive";
        }
        $statusHtml = "";
        $table =  $row->getTable();
        $checked = '';
        if (strtoupper($row->is_active) == 'YES' && $row->is_active !== NULL) {
            $checked = "checked";
        }
        $statusHtml = '<div class="text-center">
            <span class="switch switch-icon switch-md">
                <label>
                    <input type="checkbox" class="' . $classname . '" id="status_' . $row->id . '" name="status_' . $row->id . '" data-url="' . $url . '" data-table="' . $table . '" value="' . $row->id . '" ' . $checked . '>
                    <span></span>
                </label>
            </span>
            </div>';
        return $statusHtml;
    }
}


if (!function_exists('getDefaultHtml')) {
    function getDefaultHtml($row, $permission = null)
    {
        $user = Sentinel::getUser();

        if (isset($permission) && !$user->hasAnyAccess([$permission, 'users.superadmin'])) {
            return $row->is_default == "Yes" ? "Yes" : "No";
        }

        $defaultHtml = "";
        $url = route('common.change-default', [$row->id]);
        $table =  $row->getTable();
        $checked = '';
        if (strtoupper($row->is_default) == 'YES' && $row->is_default !== NULL) {
            $checked = "checked";
        }
        $defaultHtml = '<div class="text-center">
            <span class="switch switch-icon switch-md">
                <label>
                    <input type="checkbox" class="change-default" id="is_default_' . $row->id . '" name="is_default_' . $row->id . '" data-url="' . $url . '" data-table="' . $table . '" value="' . $row->id . '" ' . $checked . '>
                    <span></span>
                </label>
            </span>
            </div>';
        return $defaultHtml;
    }
}

if (!function_exists('getInfoHtml')) {
    function getInfoHtml($row)
    {
        $table_name =  $row->getTable();
        return '<li class="navi-item">
            <a href="#" class="navi-link show-info" data-toggle="modal" data-target="#AddModelInfo"
                data-table="' . $table_name . '" data-id="' . $row->id . '" data-url="' . route('get-info') . '">
                <span class="navi-icon">
                    <i class="fas fa-info-circle"></i>
                </span>
                <span class="navi-text">Info</span>
            </a></li>';
    }
}

if (!function_exists('getInfo')) {
    function getInfo($row, $table)
    {
        return '<a href="#" class="btn btn-text-dark-50 font-weight-bold btn-hover-bg-light show-info" data-toggle="modal" data-target="#AddModelInfo"
                data-table="' . $table . '" data-id="' . $row->id . '" data-url="' . route('get-info') . '">
                <span class="navi-icon">
                    <i class="fas fa-info-circle"></i>
                </span>
                <span class="navi-text">info</span>
            </a>';
    }
}

if (!function_exists('IDGenerator')) {
    function IDGenerator($model, $trow, $length = 4, $prefix, $colname = null, $colid = null)
    {
        $data = $model::orderBy('id', 'desc')->when($colname, function ($query) use ($colname, $colid) {
            $query->where($colname, $colid);
        })->first();
        if (!$data) {
            $og_length = $length - 1;
            $last_number = '1';
        } else {
            $code = substr($data->$trow, strlen($prefix) + 1);
            $actial_last_number = ($code / 1) * 1;
            $increment_last_number = ((int)$actial_last_number) + 1;
            $last_number_length = strlen($increment_last_number);
            $og_length = $length - $last_number_length;
            $last_number = $increment_last_number;
        }
        $zeros = "";
        for ($i = 0; $i < $og_length; $i++) {
            $zeros .= "0";
        }
        return $prefix . '-' . $zeros . $last_number;
    }
}

if (!function_exists('codeGenerator')) {
    function codeGenerator($table, $length = 4, $prefix)
    {
        $tableDesc = DB::table($table)->orderBy('id', 'desc')->first() ?? null;
        $id = (!empty($tableDesc)) ? $tableDesc->id + 1 : 1;

        $idNumber = $prefix . "-" . str_pad($id, $length, '0', STR_PAD_LEFT);
        return $idNumber;
    }
}



function getDefaultYear()
{
    if (Session::has('default_year')) {
        $year = Session::get('default_year');
    } else {
        $year = Year::where(['is_default' => 'Yes'])->first();
    }

    return $year;
}

function checkNumberSign($number)
{
    return ($number > 0) ? 1 : (($number < 0) ? -1 : 0);
}

if (!function_exists('isActive')) {

    function isActive($routePattern = null, $class = 'active', $prfix = 'web.')
    {
        $name = Route::currentRouteName();

        if (!is_array($routePattern) && $routePattern != null) {
            $routePattern = explode(' ', $routePattern);
        }

        foreach ((array)$routePattern as $i) {
            if (Str::is($prfix . $i, $name)) {
                return $class;
            }
        }

        foreach ((array)$routePattern as $i) {
            if (Str::is($i, $name)) {
                return $class;
            }
        }
    }
}

if (!function_exists('update_smtp')) {
    function update_smtp($mail_data)
    {
        Config::set('mail.mailers.smtp.transport', $mail_data['driver']);
        Config::set('mail.mailers.smtp.host', $mail_data['host']);
        Config::set('mail.mailers.smtp.port', $mail_data['port']);
        Config::set('mail.from.address', $mail_data['username']);
        Config::set('mail.mailers.smtp.encryption', $mail_data['encryption']);
        Config::set('mail.mailers.smtp.username', $mail_data['username']);
        Config::set('mail.mailers.smtp.password', $mail_data['password']);
    }
}

if (!function_exists('get_smtp_details')) {

    function get_smtp_details($module_name)
    {
        $fields = [
            'SC.from_name',
            'SC.host_name',
            'SC.username',
            'SC.port',
            'SC.password',
            'SC.driver',
            'SC.encryption',
            'MT.module_name',
            'MT.subject',
            'MT.message_body',
            'MT.is_active',
            'MT.attachment',
        ];

        $result = DB::table('mail_templates as MT')->select($fields)->join('smtp_configurations as SC', function ($join) {
            $join->on('SC.id', '=', 'MT.smtp_id');
        })->where([
            ['MT.module_name', $module_name],
            ['MT.is_active', 'YES'],
            ['SC.is_active', 'YES'],
        ])->whereNull('MT.deleted_at')->whereNull('SC.deleted_at')->first();

        return (!empty($result)) ? $result : [];
    }
}

if (!function_exists('uploadFile')) {

    function uploadFile($request, $folder_name, $input_name, $unlink = null)
    {
        if ($request->hasFile($input_name)) {
            $file = $request->file($input_name);
            $fileName = time() . '_' . rand(0, 500) . '_' . $file->getClientOriginalName();
            $fileName = str_replace(' ', '_', $fileName);
            $request->{$input_name}->move(public_path('uploads/' . $folder_name), $fileName);
            $image_path = 'uploads/' . $folder_name . $fileName;

            /*if ($unlink) {
                Storage::delete($unlink);
            }*/
            return $image_path;
        }
        return  $unlink ? $unlink : NULL;
    }
}

if (!function_exists('cutNum')) {
    function cutNum($num, $precision = 2)
    {
        return floor($num) . substr(str_replace(floor($num), '', $num), 0, $precision + 1);
    }
}

if (!function_exists('uploadAttachment')) {

    function uploadAttachment($request, $field, $folder_name, $unlink = '')
    {
        $attachment = '';
        if ($request->hasFile($field)) {

            $current = Carbon::now();
            $storepath = 'uploads/'.$folder_name . '/' . $current->format('Y') . '/' . $current->format('m'). '/';
            $file[$field] = AppHelper::getUniqueFilename($request->file($field), AppHelper::getImagePath('/'.$storepath));
            $request->file($field)->move(AppHelper::getImagePath('/'.$storepath), $file[$field]);
            $attachment = $storepath . $file[$field];
            if ($unlink) {
                unlinkFile($unlink);
            }
        }
        return $attachment;
    }
}

if (!function_exists('unlinkFile')) {
    function unlinkFile($file)
    {
        if (File::exists($file)) {
            unlink(base_path('public/' . $file));
        }
    }
}

if (!function_exists('get_size')) {
    function get_size($file_path)
    {
        $fileSize = File::size(public_path($file_path));
        return number_format($fileSize / 1048576, 2);
    }
}

if (!function_exists('image_compress')) {

    function image_compress($image_path = '')
    {

        if ($image_path != '') {
            $img = Image::make($image_path);
            $img->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
            })->save($image_path);
        }
    }
}

if (!function_exists('monthsByYears')) {

    function monthsByYears($format = null)
    {
        $selectedYear = Year::where('is_default', 'Yes')->first();
        if (isset($selectedYear)) {
            $from_date = Carbon::parse($selectedYear->from_date)->format('Y-m-d');
            //$to_date = Carbon::parse($selectedYear->to_date)->format('Y-m-d');
            $to_date = Carbon::now()->format('Y-m-d');
            $period = CarbonPeriod::create($from_date, $to_date)->month();
            $now = Carbon::now();
            $currentMonth = $now->format('m');

            //dd($from_date,$to_date);
            $monthsArr = [];
            foreach ($period as $dt) {
                $month = $dt->format('m');
                //if($month<=$currentMonth){
                $monthsArr[$dt->format('m')] = $dt->format($format);
                //}
            }

            return $monthsArr;
        }
    }
}

if (!function_exists('stringToReplace')) {
    function stringToReplace($string, $text = ',', $replace = '.')
    {
        return str_replace($text, $replace, $string);
    }
}

if (!function_exists('multiUploadFile')) {

    function multiUploadFile($file, $folder_name)
    {
        $fileName = time() . '_' . rand(0, 500) . '_' . $file->getClientOriginalName();
        $fileName = str_replace(' ', '_', $fileName);
        $folder_name = Carbon::now()->format('Y/m/') . '/' . $folder_name . '/';
        $file->move(public_path('uploads/' . $folder_name), $fileName);
        return 'uploads/' . $folder_name . $fileName;
    }
}

if (!function_exists('bytesToMb')) {

    function bytesToMb($bytes)
    {
        $si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
        $base = 1024;
        $class = min((int)log($bytes, $base), count($si_prefix) - 1);
        return sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class];
    }
}

if (!function_exists('generateRandomPassword')) {
    function generateRandomPassword($min_length = 8)
    {
        $randomPassword = '';
        $numbers = 0;
        $letters = 0;
        $length = 0;
        $min_numbers = 1; //Minimum of numbers AND special characters
        $min_letters = 1; //Minimum of letters

        while ($length <= $min_length || $numbers <= $min_numbers || $letters <= $min_letters) {
            $length += 1;
            $type = rand(1, 3);
            if ($type == 1) {
                $randomPassword .= chr(rand(33, 64)); //Numbers and special characters
                $numbers += 1;
            } elseif ($type == 2) {
                $randomPassword .= chr(rand(65, 90)); //A->Z
                $letters += 1;
            } else {
                $randomPassword .= chr(rand(97, 122)); //a->z
                $letters += 1;
            }
        }

        return $randomPassword;
    }
}
if (!function_exists('convertStrToNum')) {
    function convertStrToNum($number)
    {
        if (!is_null($number)) {
            return floatval(preg_replace('/[^\d\.]/', '', $number));
        } else {
            return NULL;
        }
    }
}

function getOS() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform  = "Unknown OS Platform";
    $os_array = array(
      '/windows nt 10/i'      =>  'Windows 10',
      '/windows nt 6.3/i'     =>  'Windows 8.1',
      '/windows nt 6.2/i'     =>  'Windows 8',
      '/windows nt 6.1/i'     =>  'Windows 7',
      '/windows nt 6.0/i'     =>  'Windows Vista',
      '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
      '/windows nt 5.1/i'     =>  'Windows XP',
      '/windows xp/i'         =>  'Windows XP',
      '/windows nt 5.0/i'     =>  'Windows 2000',
      '/windows me/i'         =>  'Windows ME',
      '/win98/i'              =>  'Windows 98',
      '/win95/i'              =>  'Windows 95',
      '/win16/i'              =>  'Windows 3.11',
      '/macintosh|mac os x/i' =>  'Mac OS X',
      '/mac_powerpc/i'        =>  'Mac OS 9',
      '/linux/i'              =>  'Linux',
      '/ubuntu/i'             =>  'Ubuntu',
      '/iphone/i'             =>  'iPhone',
      '/ipod/i'               =>  'iPod',
      '/ipad/i'               =>  'iPad',
      '/android/i'            =>  'Android',
      '/blackberry/i'         =>  'BlackBerry',
      '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value){
        if (preg_match($regex, $user_agent)){
            $os_platform = $value;
        }
    }
    return $os_platform;
}

function getBrowser() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "Unknown Browser";
    $browser_array = array(
            '/msie/i'      => 'Internet Explorer',
            '/firefox/i'   => 'Firefox',
            '/safari/i'    => 'Safari',
            '/chrome/i'    => 'Chrome',
            '/edge/i'      => 'Edge',
            '/opera/i'     => 'Opera',
            '/netscape/i'  => 'Netscape',
            '/maxthon/i'   => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i'    => 'Handheld Browser'
     );
    foreach ($browser_array as $regex => $value){
        if (preg_match($regex, $user_agent)){
            $browser = $value;
        }
    }
    return $browser;
}

if (!function_exists('numberExcelFormatPrecision')) {
    function numberExcelFormatPrecision($number, $precision = 2)
    {
        $response = format_excel_amount((float)$number, $precision);
        return $response;
    }
}

function format_excel_amount($value, $precision = null)
{
    setlocale(LC_MONETARY, 'en_IN');
    if (is_null($precision)) {
        $precision = '0';
    } else if ($precision == 1) {
        $precision = '0.0';
    } else if ($precision == 2) {
        $precision = '0.00';
    } else if ($precision == 3) {
        $precision = '0.000';
    } else {
        $precision = '0';
    }
    if (!empty($value)) {
        $fmt = new NumberFormatter('en_IN', NumberFormatter::DECIMAL);
        $fmt->setPattern("#####" . $precision);
        return $fmt->format($value);
    }
    return number_format(0, $precision);
}

//Get login user detail.
if (!function_exists('loginUserDetail')) {

    function loginUserDetail($apiUser = null, $api = false)
    {
        $user = ($api) ? $apiUser : auth()->user();
        // if ($user->user_type == 1 || $user->user_type == 3) {
        //     $employeeId = Employee::select('id')->where('user_id', $user->id)->first();
        //     $user->employee_id = $employeeId->id;
        // }
        return $user;
    }
}

//Series No

function generateSeriesNumber($type)
{
    $tranType = Series::where('type', $type)->first();

    $no = getNextNumber($type);
    $yearPrefix = $monthYearPrefix = $mYPrefix = "";
    $year = getDefaultYear();
    if ($year) {
        $yearPrefix = date('y', strtotime($year->from_date)) . date('y', strtotime($year->to_date));
        $y = "";
        if (in_array(date('m'), ['04', '05', '06', '07', '08', '09', '10', '11', '12'])) {
            $y = date('y', strtotime($year->from_date));
        } else {
            $y = date('y', strtotime($year->to_date));
        }
        $monthYearPrefix = date('m') . $y;
        $mYPrefix = Carbon::now()->format('my');
    }
    switch ($tranType->type) {
        case 'PO':
            $seriesNumber = $tranType->prefix . $yearPrefix . "/" . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        case 'IC':
            $seriesNumber = $tranType->prefix . $yearPrefix . "/" . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        case 'SO':
            $seriesNumber = $tranType->prefix . $yearPrefix . "/" . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        case 'OC':
            $seriesNumber = $tranType->prefix . $yearPrefix . "/" . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        case 'GWIP':
            $prefix = $tranType->prefix;
            $prefix = str_replace('-', '', $prefix);
            $seriesNumber = $prefix . $mYPrefix . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        case 'RM':
            $prefix = $tranType->prefix;
            $prefix = str_replace('-', '', $prefix);
            $seriesNumber = $prefix . $mYPrefix . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        case 'PWIP':
            $prefix = $tranType->prefix;
            $prefix = str_replace('-', '', $prefix);
            $seriesNumber = $prefix . $mYPrefix . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        case 'FG':
            $prefix = $tranType->prefix;
            $prefix = str_replace('-', '', $prefix);
            $seriesNumber = $prefix . $mYPrefix . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        case 'OFG':
            $prefix = $tranType->prefix;
            $prefix = str_replace('-', '', $prefix);
            $seriesNumber = $prefix . $mYPrefix . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        case 'GP':
            $seriesNumber = $tranType->prefix . $yearPrefix . "/" . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        case 'SHO':
            $seriesNumber = $tranType->prefix . $yearPrefix . "/" . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        case 'SR':
            $seriesNumber = $tranType->prefix . $yearPrefix . "/" . str_pad($no, 4, '0', STR_PAD_LEFT) . $tranType->suffix;
            break;
        default:
            # code...
            break;
    }

    $data = [
        'number' => $seriesNumber ?? null,
        'next_number' => (!empty($seriesNumber)) ?  $no + 1 : null
    ];

    return $data;
}
function getNextNumber($type)
{
    $no = 1;
    $yearPrefix = "";
    $mYPrefix = "";
    $defaultYear = getDefaultYear();
    if ($defaultYear) {
        $yearPrefix = date('y', strtotime($defaultYear->from_date)) . date('y', strtotime($defaultYear->to_date));
        $mYPrefix = Carbon::now()->format('my');
    }

    switch ($type) {
        case 'PO':
            $purchseOrderCount = PurchaseOrder::withTrashed()->where('purchase_order_code', 'like', '%' . $yearPrefix . '%')
                ->count();
            if ($purchseOrderCount > 0) {
                $no = $purchseOrderCount + 1;
            }
            break;
        case 'IC':
            $inwardChallanCount = InwardChallan::withTrashed()->where('inward_challan_code', 'like', '%' . $yearPrefix . '%')
                ->count();
            if ($inwardChallanCount > 0) {
                $no = $inwardChallanCount + 1;
            }
            break;
        case 'SO':
            $floorCount = DB::table('sales_orders')->where('code', 'like', '%' . $yearPrefix . '%')->count();
            if ($floorCount > 0) {
                $no = $floorCount + 1;
            }
            break;
        case 'OC':
            $outwardChallanCount = DB::table('outward_challans')->where('code', 'like', '%' . $yearPrefix . '%')->count();
            if ($outwardChallanCount > 0) {
                $no = $outwardChallanCount + 1;
            }
            break;
        case 'GWIP':
            $grindingCount = Grinding::withTrashed()->where('barcode', 'like', '%' . $mYPrefix . '%')->count();
            if ($grindingCount > 0) {
                $no = $grindingCount + 1;
            }
            break;
        case 'RM':
            $rawMaterialCount = Barcode::withTrashed()->where('type', 'RM')->where('barcode_number', 'like', '%' . $mYPrefix . '%')->count();
            if ($rawMaterialCount > 0) {
                $no = $rawMaterialCount + 1;
            }
            break;
        case 'PWIP':
            $printingCount = Printing::withTrashed()->where('wip_barcode', 'like', '%' . $mYPrefix . '%')->count();
            if ($printingCount > 0) {
                $no = $printingCount + 1;
            }
            break;
        case 'FG':
            $packingCount = barcode::withTrashed()->where('type', 'FG')->where('barcode_number', 'like', '%' . $mYPrefix . '%')->count();
            if ($packingCount > 0) {
                $no = $packingCount + 1;
            }
            break;
        case 'OFG':
            $OFGBarcodeCount = DB::table('barcodes')->where('type', 'OFG')->where('barcode_number', 'like', '%' . $mYPrefix . '%')->count();
            if ($OFGBarcodeCount > 0) {
                $no = $OFGBarcodeCount + 1;
            }
            break;
        case 'GP':
            $gatePassCount = DB::table('gate_passes')->where('voucher_no', 'like', '%' . $yearPrefix . '%')->count();
            if ($gatePassCount > 0) {
                $no = $gatePassCount + 1;
            }
            break;
        case 'SHO':
            $shopOrderCount = ShopOrder::withTrashed()->where('shop_order_no', 'like', '%' . $yearPrefix . '%')->count();
            if ($shopOrderCount > 0) {
                $no = $shopOrderCount + 1;
            }
            break;
        case 'SR':
            $shopOrderCount = SalesReturn::withTrashed()->where('code', 'like', '%' . $yearPrefix . '%')->count();
            if ($shopOrderCount > 0) {
                $no = $shopOrderCount + 1;
            }
            break;
        default:
            # code...
            break;
    }

    return $no;
}

if (!function_exists('generatePrefixSeriesNumber')) {
    function generatePrefixSeriesNumber($transactionType)
    {
        $transactionprefix = strtolower($transactionType) . '_prefix';
        $transactionNextNumber = strtolower($transactionType) . '_next_number';
        $transactionNextSuffix = strtolower($transactionType) . '_suffix';
        $transactionprefixData = Setting::where(['name' => $transactionprefix])->first();
        // dd($transactionprefixData);
        $transactionNextNumberData = Setting::where(['name' => $transactionNextNumber])->first();
        $transactionNextSuffixData = Setting::where(['name' => $transactionNextSuffix])->first();
        $no = getNextNumber($transactionType);
        $monthYearPrefix = getMonthYearPrefix();
        $yearPrefix = getYearPrefix();

        switch ($transactionType) {
            case 'PO':
                $seriesNumber = $transactionprefixData->value . $yearPrefix . "/" . str_pad($no, 3, '0', STR_PAD_LEFT) . $transactionNextSuffixData->value;
                break;
            case 'SO':
                $seriesNumber = $transactionprefixData->value . $yearPrefix . "/" . str_pad($no, 3, '0', STR_PAD_LEFT) . $transactionNextSuffixData->value;
                break;
            case 'IC':
                $seriesNumber = $transactionprefixData->value . $yearPrefix . "/" . str_pad($no, 3, '0', STR_PAD_LEFT) . $transactionNextSuffixData->value;
                break;
            case 'OC':
                $seriesNumber = $transactionprefixData->value . $yearPrefix . "/" . str_pad($no, 3, '0', STR_PAD_LEFT) . $transactionNextSuffixData->value;
                break;
            case 'RM':
                $seriesNumber = $transactionprefixData->value . $monthYearPrefix . str_pad($no, 3, '0', STR_PAD_LEFT) . $transactionNextSuffixData->value;
                break;
            case 'FG':
                $seriesNumber = $transactionprefixData->value . $monthYearPrefix . str_pad($no, 3, '0', STR_PAD_LEFT) . $transactionNextSuffixData->value;
                break;
            case 'OFG':
                $seriesNumber = $transactionprefixData->value . $monthYearPrefix . str_pad($no, 3, '0', STR_PAD_LEFT) . $transactionNextSuffixData->value;
                break;
            case 'GWIP':
                $seriesNumber = $transactionprefixData->value . $monthYearPrefix . str_pad($no, 3, '0', STR_PAD_LEFT) . $transactionNextSuffixData->value;
                break;
            case 'PWIP':
                $seriesNumber = $transactionprefixData->value . $monthYearPrefix . str_pad($no, 3, '0', STR_PAD_LEFT) . $transactionNextSuffixData->value;
                break;
            case 'SHO':
                $seriesNumber = $transactionprefixData->value . $monthYearPrefix . str_pad($no, 3, '0', STR_PAD_LEFT) . $transactionNextSuffixData->value;
                break;

            default:
                # code...
                break;
        }

        $data = [
            's_number' => $seriesNumber ?? null,
            'next_number' => (!empty($seriesNumber)) ?  $no + 1 : null
        ];

        return $data;
    }
}

if (!function_exists('nextNumberStore')) {
    function nextNumberStore($type, $no)
    {
        $series = Series::where('transaction_type', $type)->first();
        if ($series && !empty($no)) {
            $series->update(['next_number' => $no]);
        }
    }
}

if (!function_exists('format_qty')) {
    function format_qty($value, $precision = null)
    {
        setlocale(LC_MONETARY, 'en_IN');
        if (is_null($precision)) {
            $precision = '0';
        } else if ($precision == 1) {
            $precision = '0.0';
        } else if ($precision == 2) {
            $precision = '0.00';
        } else if ($precision == 3) {
            $precision = '0.000';
        } else {
            $precision = '0';
        }
        if (!empty($value)) {
            $fmt = new NumberFormatter('en_IN', NumberFormatter::DECIMAL);
            $fmt->setPattern("#####" . $precision);
            return $fmt->format($value);
        }
        return number_format(0, $precision);
    }
}

if (!function_exists('getSetting')) {

    function getSetting($key)
    {
        $setting = Setting::where('name', $key)->first();
        return ($setting) ? $setting->value : "";
    }
}

if (!function_exists('custom_date_format')) {
    function custom_date_format($date, $format = 'Y-m-d')
    {
        if ($date != '') {
            $date = str_replace('/', '-', $date);
            return date($format, strtotime($date));
        } else {
            return '';
        }
    }
}

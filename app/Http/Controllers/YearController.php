<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\YearDataTable;
use App\Models\Year;
use App\Http\Requests\YearRequest;
use DB;
use Session;


class YearController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->middleware('permission:years.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:years.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:years.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:years.delete', ['only' => ['destroy']]);
        $this->title = trans("years.years");
        view()->share('title', $this->title);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(YearDataTable $dataTable)
    {
        return $dataTable->render('years.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'html' =>  view('years.create')->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(YearRequest $request)
    {
        $input = $request->all();
        if ($input['is_default'] == 'Yes') {
            $a = Year::where('is_default', 'Yes')->get();
            foreach ($a as $key => $value) {
                $value->is_default = 'No';
                $value->save();
            }
            $input['is_default'] = 'Yes';
        } else {
            $input['is_default'] = 'No';
        }

        if ($input['is_displayed'] == 'Yes') {
            $a = Year::where('is_displayed', 'Yes')->get();
            foreach ($a as $key => $value) {
                $value->is_displayed = 'No';
                $value->save();
            }
            $input['is_displayed'] = 'Yes';
        } else {
            $input['is_displayed'] = 'No';
        }
        $model = Year::create($input);

        return redirect()->route('year.index')->with('success', __('year.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $year = Year::find($id);
        $this->data['year'] = $year;
        return response()->json(['html' => view('years.edit', $this->data)->render()]);
    }

    /**
     * [changeStatus description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */

    public function changeDisplay(Request $request, $id)
    {
        $table = $request->table;
        $is_displayed  = $request->status == 'true' ? 'Yes' : 'No';
        $tableRes = DB::table('years')->where('id', $request->id)->update(['is_displayed' => $is_displayed]);
        if ($tableRes) {
            $statuscode = 200;
        }
        $message = $request->status == 'true' ? __('common.active') : __('common.deactivate');

        return response()->json([
            'success' => true,
            'message' => $message
        ], $statuscode);
    }

    public function changeYear($id, Request $request)
    {
        $year = Year::find($id);
        if (is_null($year)) {
            return redirect()->to($request['_url']);
        }
        $fromDate = date('y', strtotime($year->from_date));
        $toDate = date('y', strtotime($year->to_date));
        $yearName = $fromDate . '-' . $toDate;
        Session::put('default_year', $year);
        Session::put('default_year_id', $year->id);
        Session::put('default_year_name', $yearName);
        /* if ($from) {
            return $year;
        } */
        return redirect()->to($request['_url']);
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
        $year = Year::findOrFail($id);
        $input = $request->all();
        if ($input['is_default'] == 'Yes') {
            $yearData =  Year::whereNotIn('id', [$id])->get();
            foreach ($yearData as $key => $value) {

                $value->is_default = 'No';
                $value->save();
            }
            $input['is_default'] = 'Yes';
        } else {
            $input['is_default'] = 'No';
        }

        if ($input['is_displayed'] == 'Yes') {
            $yearData =  Year::whereNotIn('id', [$id])->get();
            foreach ($yearData as $key => $value) {

                $value->is_displayed = 'No';
                $value->save();
            }
            $input['is_displayed'] = 'Yes';
        } else {
            $input['is_displayed'] = 'No';
        }
        $year->update($input);
        return redirect()->route('year.index')->with('success', __('year.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $year = Year::findOrFail($id);
        if ($year) {
            $dependency = $year->deleteValidate($id);
            if (!$dependency) {
                $year->delete();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('year.dependency_error', ['dependency' => $dependency]),
                ], 200);
            }
        }
        return response()->json([
            'success' => true,
            'message' => __('common.delete_success'),
        ], 200);
    }
}

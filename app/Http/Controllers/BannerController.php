<?php

namespace App\Http\Controllers;

use App\DataTables\BannerDataTable;
use App\Http\Requests\BannerRequest;
use App\Models\Banner;
use Sentinel;
use Exception;
use DB;

class BannerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->middleware('permission:banner.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:banner.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:banner.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:banner.delete', ['only' => ['destroy']]);
        $this->common = new CommonController();
        $this->title = trans("banner.title");
        view()->share('title', $this->title);
    }

    public function index(BannerDataTable $dataTable)
    {
        return $dataTable->render('banner.index');
    }

    public function create()
    {
        return response()->json(['html' =>  view('banner.create')->render()]);
    }

    public function store(BannerRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->except(['_token']);
            $image = null;
            if ($request->hasFile('image')) {
                $image = uploadAttachment($request, 'image', 'banner');
            }
            $input['image'] = $image;
            Banner::create($input);
            DB::commit();
            return redirect()->route('banner.index')->with('success', __('banner.create_success'));
        } catch (Exception $e) {
            DB::rollBack();
            info($e);
            return back()->withError("Something went wrong please try again.");
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $banner = Banner::find($id);
        $this->data['banner'] = $banner;
        return response()->json(['html' => view('banner.edit', $this->data)->render()]);
    }

    public function update(BannerRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $input = $request->except(['_token']);
            $banner = Banner::find($id);
            $image = $banner->image;
            if ($request->hasFile('image')) {
                $image = uploadAttachment($request, 'image', 'banner', $banner->image);
            }
            $input['image'] = $image;
            $banner->update($input);
            DB::commit();
            return redirect()->route('banner.index')->with('success', __('banner.update_success'));
        } catch (Exception $e) {
            DB::rollBack();
            info($e);
            return back()->withError("Something went wrong please try again.");
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $banner = Banner::find($id);
            $banner->delete();
            unlinkFile($banner->image);
            $deleteArray = [
                'module' => 'banner',
                'table_name' => $banner->getTable() ?? '',
                'table_id' => $id ?? 0,
            ];
            $this->common->getCreateDeleteLog($deleteArray);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => __('banner.delete_success'),
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            info($e);
            return false;
        }
    }
}

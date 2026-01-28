<?php

namespace App\Http\Controllers;

use App\Http\Requests\vehicleModelAddEditFormRequest;
use App\tbl_model_names;
use App\Vehiclebrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicalmodelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // vehiclemodel list
    public function listvehicalmodel()
    {
        $vehicalmodel = tbl_model_names::where('soft_delete', '=', 0)->orderBy('id', 'DESC')->get();

        return view('vehiclemodel.list', compact('vehicalmodel'));
    }

    // vehiclemodel add form
    public function index()
    {
        $vehicalbrand = Vehiclebrand::where('soft_delete', '=', 0)->get();

        return view('vehiclemodel.add', compact('vehicalbrand'));
    }

    // vehiclemodel store
    public function store(vehicleModelAddEditFormRequest $request)
    {
        $model_name = $request->model_name;
        $brand_id = $request->brand_id;

        // dd($model_name);

        $count = DB::table('tbl_model_names')->where('model_name', '=', $model_name)->count();
        if ($count == 0) {
            $tbl_model_names = new tbl_model_names;
            $tbl_model_names->model_name = $model_name;
            $tbl_model_names->brand_id = $brand_id;
            $tbl_model_names->save();

            return redirect('vehicalmodel/list')->with('message', 'Vehicle Model Added Successfully');
        } else {
            $vehicleModelNameRecord = DB::table('tbl_model_names')->where([['soft_delete', '!=', 1], ['model_name', '=', $model_name]])->first();
            if (! empty($vehicleModelNameRecord)) {
                return redirect('vehicalmodel/list')->with('message', 'Duplicate Data');
            } else {
                $tbl_model_names = new tbl_model_names;
                $tbl_model_names->model_name = $model_name;
                $tbl_model_names->brand_id = $brand_id;
                $tbl_model_names->save();

                return redirect('vehicalmodel/list')->with('message', 'Vehicle Model Added Successfully');
            }
        }
    }

    // vehiclemodel edit form
    public function editmodel($id)
    {
        $editid = $id;
        $vehicalbrand = Vehiclebrand::where('soft_delete', '=', 0)->get();
        $vehical_model = DB::table('tbl_model_names')->where('id', '=', $id)->first();

        return view('vehiclemodel/edit', compact('vehicalbrand', 'vehical_model', 'editid'));
    }

    // vehiclemodel update
    public function modelupdate(vehicleModelAddEditFormRequest $request, $id)
    {
        $model_name = $request->model_name;
        $brand_id = $request->brand_id;

        $count = DB::table('tbl_model_names')->where([['model_name', '=', $model_name], ['brand_id', '=', $brand_id], ['id', '!=', $id]])->count();
        if ($count == 0) {
            $vehicalbrands = tbl_model_names::find($id);
            $vehicalbrands->model_name = $model_name;
            $vehicalbrands->brand_id = $brand_id;
            $vehicalbrands->save();

            return redirect('vehicalmodel/list')->with('message', 'Vehicle Model Updated Successfully');
        } else {
            return redirect('vehicalmodel/list/edit/'.$id)->with('message', 'Duplicate Data');
        }
    }

    // vehiclemodel delete
    public function destory($id)
    {
        DB::table('tbl_model_names')->where('id', '=', $id)->update(['soft_delete' => 1]);

        return redirect('vehicalmodel/list')->with('message', 'Vehicle Model Deleted Successfully');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $this->destory($id);
        }

        return redirect('vehicalmodel/list')->with('message', 'Vehicle Model Deleted Successfully');
    }
}

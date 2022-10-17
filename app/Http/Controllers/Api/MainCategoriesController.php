<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MainCategoriesController extends Controller
{
    //
    use GeneralTrait;
    public function index()
    {
        $categories=Category::selection()->get();
        return response()->json($categories);
    }
    public function getCategoryByID(Request $request)
    
    {
        $category=Category::selection()->find($request->id);
        if(!$category)
        return $this->returnError('001', 'هذا القسم غير موجود');

        return $this->returnData('categroy', $category,"هذا القسم  موجود");
    }
    public function ChangeStatus(Request $request)
    {
       
        $validation = Validator::make($request->all(),[ 
          'active'=>'required|in:1,2',
        //   'id'=>'required',
        ]);
        if($validation->fails()){
            return $validation->errors();

        } 
        Category::where('id',$request -> id) -> update(['active' =>$request ->  active]);

        return $this -> returnSuccessMessage('تم تغيير الحاله بنجاح');
    }
    public function show(Request $request)
    {
        $category=Category::find($request->id);
        if($category)
        {
            return $this->returnData('categroy', $category,"هذا القسم  موجود");
        }
        else
        return $this->returnError('001', 'هذا القسم غير موجود');
        
    }
    public function addCategory(Request $request)
    { 
        $id = Auth()->user()->id;
        return $id;
       
        // validation
        $validation = Validator::make($request->all(),[ 
            'name_ar'=>'required',
            'name_en'=>'required',
     
          ]);
          if($validation->fails()){
            return $validation->errors();

        } 
        
        try
        {
         DB::beginTransaction();
         Category::create(['name_ar'=>$request->name_ar,'name_en'=>$request->name_en,'active'=>$request->active]);
         DB::commit();
         return $this->returnSuccessMessage('category added successfully');

        }
        catch (\Exception $ex) {

            DB::rollback();
            return $this->returnError('001', 'هذا القسم غير موجود');
        }
       
    }
}

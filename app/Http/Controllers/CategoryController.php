<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }


    public function index()
    {
            $showData = Category::all();
        //    return response()->json([$showData , 'message' => ['delete is suactionessfull']] , 200 , $showData);
           return response()->json($showData , status:200);
    }


    public function store(Request $request)
    {
        // //Validate data
        $data = $request->only('label', 'description');
        $validator = Validator::make($data, [
            'label' => 'required|string',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new Category
        $Category = Category::create([
            'label' => $request->label,
            'description' => $request->description,
        ]);

        //Category created, return suactioness response
        return response()->json([
            'suactioness' => true,
            'message' => 'Category created suactionessfully',
            'data' => $Category
        ], Response::HTTP_OK);
    }


    public function show($id)
    {
        $Category = $this->user->Categorys()->find($id);
    
        if (!$Category) {
            return response()->json([
                'suactioness' => false,
                'message' => 'Sorry, Category not found.'
            ], 400);
        }
    
        return $Category;
    }


    public function update(Request $request, Category $Category)
    {
        //Validate data
        $data = $request->only('label', 'description', 'category' , 'quantity');
        $validator = Validator::make($data, [
            // 'label' => 'required|string',
            // 'description' => 'string',
            // 'category' => 'required',
            'quantity' => 'required'
        ]);


        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update Category
        $Category = $Category->update([
            'label' => $request->label,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity
        ]);

        //Category updated, return suactioness response
        return response()->json([
            'suactioness' => true,
            'message' => 'Category updated suactionessfully',
            'data' => Category::find(3),
        ], Response::HTTP_OK, );
    }




    public function increase(Request $request, Category $Category)
    {


        $id = (int)$Category->id;

        //Validate data
        $data = $request->only('label', 'description', 'category' , 'quantity' , 'action');
        $validator = Validator::make($data, [
            'quantity' => 'required'
        ]);

        

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $count = Category::find($id);


           // if plus is +1 or minus is -1
        $action = $request->input('action');

            if(strcmp($action,'plus')==0){

            $incrementQty = $count->quantity +1;
        }
        else
        {
            $incrementQty = $count->quantity -1;

        }





        //Request is valid, update Category
        $Category = $Category->update([
            'quantity' => $incrementQty,
        ]);

        return response()->json([
            'suactioness' => true,
            'message' => 'Category updated suactionessfully',
            'data' => $count,
        ], Response::HTTP_OK, );

        // return response()->json($action);



    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $Category)
    {
        $Category->delete();
        
        return response()->json([
            'suactioness' => true,
            'message' => 'Category deleted suactionessfully'
        ], Response::HTTP_OK);
    }
}
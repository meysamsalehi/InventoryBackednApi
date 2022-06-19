<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

            $showData = Product::all();

        //    return response()->json([$showData , 'message' => ['delete is suactionessfull']] , 200 , $showData);
           return response()->json($showData , status:200);



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // //Validate data
        $data = $request->only('title', 'description', 'category', 'quantity');
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'category' => 'required',
            'quantity' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new product
        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity
        ]);

        //Product created, return suactioness response
        return response()->json([
            'suactioness' => true,
            'message' => 'Product created suactionessfully',
            'data' => $product
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->user->products()->find($id);
    
        if (!$product) {
            return response()->json([
                'suactioness' => false,
                'message' => 'Sorry, product not found.'
            ], 400);
        }
    
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //Validate data
        $data = $request->only('title', 'description', 'category' , 'quantity');
        $validator = Validator::make($data, [
            // 'title' => 'required|string',
            // 'description' => 'string',
            // 'category' => 'required',
            'quantity' => 'required'
        ]);

        


        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $product = $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity
        ]);

        //Product updated, return suactioness response
        return response()->json([
            'suactioness' => true,
            'message' => 'Product updated suactionessfully',
            'data' => Product::find(3),
        ], Response::HTTP_OK, );
    }




    public function increase(Request $request, Product $product)
    {


        $id = (int)$product->id;

        //Validate data
        $data = $request->only('title', 'description', 'category' , 'quantity' , 'action');
        $validator = Validator::make($data, [
            'quantity' => 'required'
        ]);

        

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $count = Product::find($id);


           // if plus is +1 or minus is -1
        $action = $request->input('action');

            if(strcmp($action,'plus')==0){

            $incrementQty = $count->quantity +1;
        }
        else
        {
            $incrementQty = $count->quantity -1;

        }





        //Request is valid, update product
        $product = $product->update([
            'quantity' => $incrementQty,
        ]);

        return response()->json([
            'suactioness' => true,
            'message' => 'Product updated suactionessfully',
            'data' => $count,
        ], Response::HTTP_OK, );

        // return response()->json($action);



    }





    public function destroy(Product $product)
    {



        $productId = Product::findOrFail($product->id);
        if($productId){
            $productId->delete(); 
        }
        else{
            return response()->json("errror");

        }
        return response()->json([
            'error' => true,
            'message' => 'Product deleted fail',
            'ppp' => $product->id
        ]);








    }

}
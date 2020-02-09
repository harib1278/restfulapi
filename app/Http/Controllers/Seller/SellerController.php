<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // Return only the sellers who have at least 1 product
      $buyers = Seller::has('products')->get();

      return response()->json(['data' => $buyers], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      // Return only the Sellers who exist
      $seller = Seller::has('products')->findOrFail($id);

      return response()->json(['data' => $seller], 200);
    }

}

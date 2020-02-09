<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Return only the buyers who have transactions
        $buyers = Buyer::has('transactions')->get();

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
      // Return only the buyers who exist
      $buyers = Buyer::has('transactions')->findOrFail($id);

      return response()->json(['data' => $buyers], 200);
    }

}

<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerController extends ApiController
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

        return $this->showAll($buyers);
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
      $buyer = Buyer::has('transactions')->findOrFail($id);

      return $this->showOne($buyers);
    }

}

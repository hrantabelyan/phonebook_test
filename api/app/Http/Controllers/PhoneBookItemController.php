<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoneBookItemRequest;
use App\Http\Requests\UpdatePhoneBookItemRequest;
use App\Models\PhoneBookItem;

class PhoneBookItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhoneBookItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PhoneBookItem $phoneBookItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhoneBookItemRequest $request, PhoneBookItem $phoneBookItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PhoneBookItem $phoneBookItem)
    {
        //
    }
}

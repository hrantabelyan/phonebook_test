<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoneBookItemRequest;
use App\Http\Requests\UpdatePhoneBookItemRequest;
use App\Http\Resources\PhoneBookItemResource;
use App\Models\PhoneBookItem;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {
            $phoneBookItem = PhoneBookItem::create($request->only([
                'first_name',
                'last_name',
                'country_code',
            ]));
        } catch (\Throwable $e) {
            DB::rollBack();
            logger($e);
            return $this->respondError(__('Could not create the item'));
        }

        try {
            foreach ($request->phone_numbers as $phoneNumber) {
                $phoneBookItem->numbers()->create([
                    'number' => $phoneNumber,
                ]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            logger($e);
            return $this->respondError(__('Could not add the phone number'));
        }

        try {
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            logger($e);
            return $this->respondError(__('Could not insert to database'));
        }

        return $this->respondCreated(new PhoneBookItemResource($phoneBookItem));
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

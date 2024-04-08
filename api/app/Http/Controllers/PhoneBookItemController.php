<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoneBookItemRequest;
use App\Http\Requests\UpdatePhoneBookItemRequest;
use App\Http\Resources\PhoneBookItemCollection;
use App\Http\Resources\PhoneBookItemResource;
use App\Models\PhoneBookItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PhoneBookItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->has('per_page') ? (int) request()->input('per_page') : 10;
        $orderArray = ['id', 'desc'];

        if (request()->has('sort_by')) {
            $tmpOrderArray = Str::of(request()->input('sort_by'))->explode('.');

            if (count($tmpOrderArray) == 2) {
                $orderArray = $tmpOrderArray;
            }
        }

        $phoneBookItems = new PhoneBookItem();

        if (request()->has('search') && is_string(request()->input('search'))) {
            $searchTerm = request()->input('search');
            $phoneBookItems = $phoneBookItems->where(function($query) use ($searchTerm) {
                $query->where('id', $searchTerm);
                $query->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%');
                $query->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
                $query->orWhere('country_code', 'LIKE', '%' . $searchTerm . '%');
                $query->orWhere('timezone', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $phoneBookItems = $phoneBookItems->orderBy($orderArray[0], $orderArray[1]);
        // logger($phoneBookItems->toSql(), $phoneBookItems->getBindings());
        $phoneBookItems = $phoneBookItems->paginate($perPage);
        return new PhoneBookItemCollection($phoneBookItems);
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
        return $this->respondWithSuccess(new PhoneBookItemResource($phoneBookItem));
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
        try {
            $phoneBookItem->delete();
        } catch (\Throwable $e) {
            return $this->respondError(__('Could not delete the phonebook item, please try again later'));
        }

        return $this->respondOk();
    }
}

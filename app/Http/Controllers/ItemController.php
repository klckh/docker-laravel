<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'length' => 'integer',
        ]);

        $paginator = Item::query()->paginate($request->input('length', 25));

        return $this->buildResponse(self::CODE_SUCCESS, $paginator);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|unique:items,sku',
            'name' => 'required|string',
            'qty' => 'nullable|numeric|integer',
        ]);

        $item = Item::create([
            'sku' => $request->sku,
            'name' => $request->name,
            'qty' => $request->input('qty', 0),
        ]);

        return $this->buildResponse(self::CODE_SUCCESS, $item, "{$item->sku} successfully created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return $this->buildResponse(self::CODE_SUCCESS, $item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $attrs = $request->validate([
            'sku' => ['string', Rule::unique('items')->ignore($item->id)],
            'name' => 'string',
            'qty' => 'numeric|integer',
        ]);

        $item->update($attrs);
        return $this->buildResponse(self::CODE_SUCCESS, $item, "{$item->sku} successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return $this->buildResponse(self::CODE_SUCCESS, message: "Successfully deleted {$item->sku}");
    }
}

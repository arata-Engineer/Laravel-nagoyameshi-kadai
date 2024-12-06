<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = Restaurant::query();
    
        if ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
    
        $restaurants = $query->paginate(10);
        $total = $restaurants->total();
    
        return view('admin.restaurants.index', compact('restaurants', 'keyword', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.restaurants.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
        'description' => 'required',
        'lowest_price' => 'required|numeric|min:0|lte:highest_price',
        'highest_price' => 'required|numeric|min:0|gte:lowest_price',
        'postal_code' => 'required|digits:7',
        'address' => 'required',
        'opening_time' => 'required|before:closing_time',
        'closing_time' => 'required|after:opening_time',
        'seating_capacity' => 'required|numeric|min:0',
    ]);

    $imagePath = $request->file('image') 
        ? $request->file('image')->store('restaurants', 'public') 
        : '';

    Restaurant::create(array_merge($validated, ['image' => $imagePath]));

    return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を登録しました。');
}
    /**
     * Display the specified resource.
     */

     public function show(Restaurant $restaurant)
     {
         return view('admin.restaurants.show', compact('restaurant'));
     }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
{
    return view('admin.restaurants.edit', compact('restaurant'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
            'description' => 'required',
            'lowest_price' => 'required|numeric|min:0|lte:highest_price',
            'highest_price' => 'required|numeric|min:0|gte:lowest_price',
            'postal_code' => 'required|digits:7',
            'address' => 'required',
            'opening_time' => 'required|date_format:H:i|before:closing_time',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'seating_capacity' => 'required|numeric|min:0',
        ]);
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('restaurants', 'public');
            $validated['image'] = $imagePath;
        }
    
        $restaurant->update($validated);
    
        return redirect()->route('admin.restaurants.show', $restaurant)->with('flash_message', '店舗を編集しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
{
    $restaurant->delete();

    return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を削除しました。');
}
}

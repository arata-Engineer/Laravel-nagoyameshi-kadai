<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
  

    // indexアクション（店舗一覧ページ）
    public function test_guest_cannot_access_index(){
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.index', $restaurant));

        $response->assertRedirect(route('admin.login'));
    }
    public function test_user_cannot_access_index(){
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.index', $restaurant));
        
        $response->assertRedirect(route('admin.login'));

    }

    public function test_admin_can_access_index(){
        $restaurant = Restaurant::factory()->create();

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin,'admin')->get(route('admin.restaurants.index', $restaurant));
        
        $response->assertStatus(200);

    }

    // showアクション（店舗詳細ページ）

    public function test_guest_cannot_access_show()
{
    $restaurant = Restaurant::factory()->create();

    $response = $this->get(route('admin.restaurants.show', $restaurant));

    $response->assertRedirect(route('admin.login'));
}

public function test_user_cannot_access_show()
{
    $user = User::factory()->create();
    $restaurant = Restaurant::factory()->create();

    $response = $this->actingAs($user)->get(route('admin.restaurants.show', $restaurant));

    $response->assertRedirect(route('admin.login'));
}

public function test_admin_can_access_show()
{
    $admin = Admin::factory()->create();
    $restaurant = Restaurant::factory()->create();

    $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.show', $restaurant));

    $response->assertStatus(200);
}

    // store アクション（店舗登録機能）

    public function test_guest_cannot_store_restaurant()
{
    $restaurantData = Restaurant::factory()->make()->toArray();

    $response = $this->post(route('admin.restaurants.store'), $restaurantData);

    $response->assertRedirect(route('admin.login'));
}

public function test_user_cannot_store_restaurant()
{
    $user = User::factory()->create();
    $restaurantData = Restaurant::factory()->make()->toArray();

    $response = $this->actingAs($user)->post(route('admin.restaurants.store'), $restaurantData);

    $response->assertRedirect(route('admin.login'));
}

public function test_admin_can_store_restaurant()
{
    $admin = Admin::factory()->create();
    $restaurantData = Restaurant::factory()->make()->toArray();

    //$response = $this->actingAs($admin, 'admin')->post(route('admin.restaurants.store'), $restaurantData);
    $response = $this->from('admin/restaurants')->actingAs($admin, 'admin')->post(route('admin.restaurants.store'), $restaurantData);

    $response->assertRedirect(route('admin.restaurants.index')); // 成功後にリダイレクトされることを確認
    $this->assertDatabaseHas('restaurants', [
        'name' => $restaurantData['name']
    ]);
}

    //create アクション（店舗登録ページ）

    public function test_guest_cannot_access_create()
{
    $response = $this->get(route('admin.restaurants.create'));

    $response->assertRedirect(route('admin.login'));
}

public function test_user_cannot_access_create()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('admin.restaurants.create'));

    $response->assertRedirect(route('admin.login'));
}

public function test_admin_can_access_create()
{
    $admin = Admin::factory()->create();

    $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.create'));

    $response->assertStatus(200);
}


    // edit アクション（店舗編集ページ）
    public function test_guest_cannot_access_edit()
    {
        $restaurant = Restaurant::factory()->create();
    
        $response = $this->get(route('admin.restaurants.edit', $restaurant));
    
        $response->assertRedirect(route('admin.login'));
    }
    
    public function test_user_cannot_access_edit()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
    
        $response = $this->actingAs($user)->get(route('admin.restaurants.edit', $restaurant));
    
        $response->assertRedirect(route('admin.login'));
    }
    
    public function test_admin_can_access_edit()
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();
    
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.edit', $restaurant));
    
        $response->assertStatus(200);
    }

    // destroy アクション（店舗削除機能）

    public function test_guest_cannot_destroy_restaurant()
{
    $restaurant = Restaurant::factory()->create();

    $response = $this->delete(route('admin.restaurants.destroy', $restaurant));

    $response->assertRedirect(route('admin.login'));
}

public function test_user_cannot_destroy_restaurant()
{
    $user = User::factory()->create();
    $restaurant = Restaurant::factory()->create();

    $response = $this->actingAs($user)->delete(route('admin.restaurants.destroy', $restaurant));

    $response->assertRedirect(route('admin.login'));
}

public function test_admin_can_destroy_restaurant()
{
    $admin = Admin::factory()->create();
    $restaurant = Restaurant::factory()->create();

    $response = $this->actingAs($admin, 'admin')->delete(route('admin.restaurants.destroy', $restaurant));

    $response->assertRedirect(route('admin.restaurants.index')); // 成功後のリダイレクト確認
    $this->assertDatabaseMissing('restaurants', [
        'id' => $restaurant->id,
    ]);
}
}


<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

test('dashboard showing', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(200);
});

test('export started', function () {
    Customer::factory(10)->create();
    $response = $this->get('/download');
    $response->assertStatus(200);
});

test('file created', function () {
    $this->assertFileExists(public_path('storage/customer_export.csv'));
});

test('download success', function () {
   $response = $this->get('/download/customer_export.csv');
   $response->assertOK();
   $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
   $response->assertHeader('Content-Disposition', 'attachment; filename=customer_export.csv');
   Storage::delete('customer_export.csv');
});

test('file not found', function () {
    $response = $this->get('/download/customer_export.csv');
    $response->assertStatus(404);
    $response->assertHeader('Content-Type', 'application/json');
});

//test('has emails', function (string $email) {
//    $this->assertNotEmpty($email);
//})->with('emails');
//
//test('opening welcome page', function () {
//    $response = $this->get('/');
//    $response->assertStatus(200);
//    $response->assertSee('Welcome page');
//});

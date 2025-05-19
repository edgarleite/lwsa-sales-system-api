<?php

namespace Tests\Unit\Services;

use App\Models\Seller;
use App\Services\Implementation\SellerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class SellerServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SellerService $sellerService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sellerService = new SellerService();
    }

    /** @test */
    public function it_can_paginate_sellers()
    {
        // Arrange
        Seller::factory()->count(15)->create();

        // Act
        $result = $this->sellerService->paginateSellers(10);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(15, $result->total());
    }

    /** @test */
    public function it_can_create_a_seller()
    {
        // Arrange
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        // Act
        $seller = $this->sellerService->createSeller($data);

        // Assert
        $this->assertInstanceOf(Seller::class, $seller);
        $this->assertEquals('John Doe', $seller->name);
        $this->assertEquals('john@example.com', $seller->email);
        $this->assertDatabaseHas('sellers', $data);
    }

    /** @test */
    public function it_can_get_seller_by_id()
    {
        // Arrange
        $seller = Seller::factory()->create();

        // Act
        $result = $this->sellerService->getSellerById($seller->id);

        // Assert
        $this->assertInstanceOf(Seller::class, $result);
        $this->assertEquals($seller->id, $result->id);
    }

    /** @test */
    public function it_can_update_a_seller()
    {
        // Arrange
        $seller = Seller::factory()->create();
        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        // Act
        $updatedSeller = $this->sellerService->updateSeller($seller->id, $data);

        // Assert
        $this->assertInstanceOf(Seller::class, $updatedSeller);
        $this->assertEquals('Updated Name', $updatedSeller->name);
        $this->assertEquals('updated@example.com', $updatedSeller->email);
        $this->assertDatabaseHas('sellers', $data);
    }

    /** @test */
    public function it_can_delete_a_seller()
    {
        // Arrange
        $seller = Seller::factory()->create();

        // Act
        $result = $this->sellerService->deleteSeller($seller->id);

        // Assert
        $this->assertTrue($result);
        $this->assertSoftDeleted('sellers', ['id' => $seller->id]);
    }

    /** @test */
    public function it_can_get_seller_with_sales()
    {
        // Arrange
        $seller = Seller::factory()->create();
        
        // Act
        $result = $this->sellerService->getSellerWithSales($seller->id);

        // Assert
        $this->assertInstanceOf(Seller::class, $result);
        $this->assertEquals($seller->id, $result->id);
        $this->assertTrue($result->relationLoaded('sales'));
    }
}

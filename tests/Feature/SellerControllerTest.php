<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Api\SellerController;
use App\Http\Resources\SellerResource;
use App\Models\Seller;
use App\Services\Contracts\SellerServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mockery;
use Tests\TestCase;

class SellerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $mockSellerService;
    protected $sellerController;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar mock do SellerService
        $this->mockSellerService = Mockery::mock(SellerServiceInterface::class);
        
        // Injetar o mock no SellerController
        $this->sellerController = new SellerController($this->mockSellerService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function index_returns_paginated_sellers()
    {
        // Arrange
        $sellers = Seller::factory()->count(3)->make();
        $paginatedSellers = new \Illuminate\Pagination\LengthAwarePaginator(
            $sellers,
            count($sellers),
            15
        );
        
        $request = new Request(['per_page' => 15]);
        
        $this->mockSellerService->shouldReceive('paginateSellers')
            ->once()
            ->with(15)
            ->andReturn($paginatedSellers);

        // Act
        $result = $this->sellerController->index($request);

        // Assert
        $this->assertInstanceOf(AnonymousResourceCollection::class, $result);
        $this->assertEquals(3, $result->count());
    }

    /** @test */
    public function store_creates_new_seller()
    {
        // Arrange
        $sellerData = [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];
        
        $seller = Seller::factory()->make($sellerData);
        
        // Criar mock explícito para o SellerRequest
        $request = Mockery::mock('App\Http\Requests\SellerRequest');
        
        // Configurar o método validated() para retornar os dados
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($sellerData);
        
        $this->mockSellerService->shouldReceive('createSeller')
            ->once()
            ->with($sellerData)
            ->andReturn($seller);

        // Act
        $result = $this->sellerController->store($request);

        // Assert
        $this->assertInstanceOf(SellerResource::class, $result);
        $this->assertEquals($seller->name, $result->resource->name);
        $this->assertEquals($seller->email, $result->resource->email);
    }

    /** @test */
    public function show_returns_seller_by_id()
    {
        // Arrange
        $seller = Seller::factory()->make(['id' => 1]);
        
        $this->mockSellerService->shouldReceive('getSellerById')
            ->once()
            ->with(1)
            ->andReturn($seller);

        // Act
        $result = $this->sellerController->show(1);

        // Assert
        $this->assertInstanceOf(SellerResource::class, $result);
        $this->assertEquals($seller->id, $result->resource->id);
    }

    /** @test */
    public function update_modifies_existing_seller()
    {
        // Arrange
        $sellerData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];
        
        $seller = Seller::factory()->make([
            'id' => 1,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
        
        // Criar mock explícito para o SellerRequest
        $request = Mockery::mock('App\Http\Requests\SellerRequest');
        
        // Configurar o método validated() para retornar os dados
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($sellerData);
        
        $this->mockSellerService->shouldReceive('updateSeller')
            ->once()
            ->with(1, $sellerData)
            ->andReturn($seller);

        // Act
        $result = $this->sellerController->update($request, 1);

        // Assert
        $this->assertInstanceOf(SellerResource::class, $result);
        $this->assertEquals('Updated Name', $result->resource->name);
        $this->assertEquals('updated@example.com', $result->resource->email);
    }

    /** @test */
    public function destroy_deletes_seller()
    {
        // Arrange
        $this->mockSellerService->shouldReceive('deleteSeller')
            ->once()
            ->with(1)
            ->andReturn(true);

        // Act
        $result = $this->sellerController->destroy(1);

        // Assert
        $this->assertEquals(204, $result->getStatusCode());
    }

    /** @test */
    public function sales_returns_seller_with_sales()
    {
        // Arrange
        $seller = Seller::factory()->make(['id' => 1]);
        $seller->setRelation('sales', collect([]));
        
        $this->mockSellerService->shouldReceive('getSellerWithSales')
            ->once()
            ->with(1)
            ->andReturn($seller);

        // Act
        $result = $this->sellerController->sales(1);

        // Assert
        $this->assertInstanceOf(AnonymousResourceCollection::class, $result);
    }
}

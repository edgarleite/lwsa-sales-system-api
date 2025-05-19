<?php

namespace Tests\Unit\Services;

use App\Models\Sale;
use App\Models\Seller;
use App\Services\Implementation\SaleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class SaleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SaleService $saleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->saleService = new SaleService();
    }

    /** @test */
    public function it_can_paginate_sales()
    {
        // Arrange
        Sale::factory()->count(15)->create();

        // Act
        $result = $this->saleService->paginateSales(10);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(15, $result->total());
    }

    /** @test */
    public function it_can_create_a_sale()
    {
        // Arrange
        $seller = Seller::factory()->create();
        $data = [
            'seller_id' => $seller->id,
            'amount' => 1000.50,
            'sale_date' => now()->format('Y-m-d'),
        ];

        // Act
        $sale = $this->saleService->createSale($data);

        // Assert
        $this->assertInstanceOf(Sale::class, $sale);
        $this->assertEquals($seller->id, $sale->seller_id);
        $this->assertEquals(1000.50, $sale->amount);
        // Usar assertEqualsWithDelta para comparação de floats
        $this->assertEqualsWithDelta(85.04, $sale->commission, 0.01); // 8.5% de 1000.50
        $this->assertDatabaseHas('sales', [
            'seller_id' => $seller->id,
            'amount' => 1000.50,
        ]);
    }

    /** @test */
    public function it_can_get_sale_by_id()
    {
        // Arrange
        $sale = Sale::factory()->create();

        // Act
        $result = $this->saleService->getSaleById($sale->id);

        // Assert
        $this->assertInstanceOf(Sale::class, $result);
        $this->assertEquals($sale->id, $result->id);
    }

    /** @test */
    public function it_can_update_a_sale()
    {
        // Arrange
        $sale = Sale::factory()->create(['amount' => 1000]);
        $data = [
            'amount' => 2000,
            'sale_date' => now()->format('Y-m-d'),
        ];

        // Act
        $updatedSale = $this->saleService->updateSale($sale->id, $data);

        // Assert
        $this->assertInstanceOf(Sale::class, $updatedSale);
        $this->assertEquals(2000, $updatedSale->amount);
        // Usar assertEqualsWithDelta para comparação de floats
        $this->assertEqualsWithDelta(170, $updatedSale->commission, 0.01); // 8.5% de 2000
        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'amount' => 2000,
        ]);
    }

    /** @test */
    public function it_can_delete_a_sale()
    {
        // Arrange
        $sale = Sale::factory()->create();

        // Act
        $result = $this->saleService->deleteSale($sale->id);

        // Assert
        $this->assertTrue($result);
        $this->assertSoftDeleted('sales', ['id' => $sale->id]);
    }

    /** @test */
    public function it_can_get_sales_by_seller()
    {
        // Arrange
        $seller = Seller::factory()->create();
        Sale::factory()->count(5)->create(['seller_id' => $seller->id]);
        Sale::factory()->count(3)->create(); // Outras vendas de outros vendedores

        // Act
        $result = $this->saleService->getSalesBySeller($seller->id);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(5, $result);
        $this->assertEquals($seller->id, $result->first()->seller_id);
    }
}

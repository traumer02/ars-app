<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    public function created(Product $product): void
    {
        log::info('Product created: ', [
            'id' =>$product->id,
            'name' => $product->name,
            'detail' => $product->detail
        ]);
    }

    public function updated(Product $product): void
    {
        log::info('Product updated: ', [
            'id' => $product->id,
            'name' => $product->name,
            'detail' => $product->detail
        ]);
    }

    public function deleted(Product $product): void
    {
        log::info('Product deleted: ', [
            'id' => $product->id,
            'name' => $product->name,
            'detail' => $product->detail
        ]);
    }


}

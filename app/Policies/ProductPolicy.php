<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Umkm;

class ProductPolicy
{
    public function view(Umkm $umkm, Product $product): bool
    {
        return $umkm->id === $product->umkm_id;
    }

    public function update(Umkm $umkm, Product $product): bool
    {
        return $umkm->id === $product->umkm_id;
    }

    public function delete(Umkm $umkm, Product $product): bool
    {
        return $umkm->id === $product->umkm_id;
    }
}

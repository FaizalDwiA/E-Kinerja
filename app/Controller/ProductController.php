<?php

namespace BerkahSoloWeb\EKinerja\Controller;

class ProductController
{
    function categories(string $producId, string $categoryId): void
    {
        echo "PRODUCT $producId, CATEGORY $categoryId";
    }
}

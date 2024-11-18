<?php

namespace BerkahSoloWeb\EKinerja\App;

use PHPUnit\Fremework\TestCase;

class ViewTest extends TestCase
{
    public function testRender() {
        View::render('index', []);
    }
}
<?php

namespace BerkahSoloWeb\EKinerja\App;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{ 
    public function testRender() {
        View::render('index', []);

        $this->expectOutputRegex('[html]');
    }
}
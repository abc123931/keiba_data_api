<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\DataController;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;

class DataTest extends TestCase
{
    /**
     * DataControllerのテスト
     */
    private $target;

    public function setUp()
    {
        $this->target = new DataController();
    }

    public function test_getHorseNameで正常終了()
    {
        $this->assertTrue(true);
    }

}

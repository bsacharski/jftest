<?php

namespace Cementownia\ElGordo;

use Cementownia\Exception;
use Cementownia\Lotto\EurojackpotResult;
use PHPUnit\Framework\TestCase;

class EurojacpotResultTest extends TestCase
{
    public function testHappyPath()
    {
        // given
        $outOf50 = [1, 2, 3, 4, 5];
        $outOf10 = [1, 2];

        // when
        $sut = new EurojackpotResult($outOf50, $outOf10);

        // then
        $this->assertSame([
            '5/50' => $outOf50,
            '2/10' => $outOf10,
        ], $sut->getData());
    }

    public function testFloatInOutOf50()
    {
        // given
        $outOf50 = [1, 2.5, 3, 4, 5];
        $outOf10 = [1, 2];

        // then
        $this->expectException(Exception::class);

        // when
        $sut = new EurojackpotResult($outOf50, $outOf10);
    }

    public function testFloatInOutOf10()
    {
        // given
        $outOf50 = [1, 2, 3, 4, 5];
        $outOf10 = [1, 2.5];

        // then
        $this->expectException(Exception::class);

        // when
        $sut = new EurojackpotResult($outOf50, $outOf10);
    }

    public function testStringInOutOf50()
    {
        // given
        $outOf50 = [1, '+', 3, 4, 5];
        $outOf10 = [1, 2];

        $this->expectException(Exception::class);

        // when
        $sut = new EurojackpotResult($outOf50, $outOf10);
    }

    public function testStringInOutOf10()
    {
        // given
        $outOf50 = [1, 2, 3, 4, 5];
        $outOf10 = [1, 'a'];

        $this->expectException(Exception::class);

        // when
        $sut = new EurojackpotResult($outOf50, $outOf10);
    }

    public function testWrongNumberOfElementsInOutOf50()
    {
        // given
        $outOf50 = [1, 2, 3, 4, 5, 6];
        $outOf10 = [1, 2];

        $this->expectException(Exception::class);

        // when
        $sut = new EurojackpotResult($outOf50, $outOf10);
    }

    public function testWrongNumberOfElementsInOutOf10()
    {
        // given
        $outOf50 = [1, 2, 3, 4, 5];
        $outOf10 = [1, 2, 3];

        $this->expectException(Exception::class);

        // when
        $sut = new EurojackpotResult($outOf50, $outOf10);
    }
}

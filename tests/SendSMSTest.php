<?php

use App\Events\SendSMSEvent;
use App\Handlers\Events\SendSMS;
use App\Sms\Mitake_SMS;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendSMSTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
            ->see('Send SMS');
    }

    /**
     * @test
     */
    public function it_should_send_sms()
    {
        // Arrange

        // 挑戰 2：在修改最少的情況下，讓這個 Mitake_SMS 類別可以被 Mock 取代，進而測試 handle 方法。
        // Solution 1:
        $fakeCourier = \Mockery::mock(Mitake_SMS::class);
        $fakeCourier->shouldReceive('sendTextMessage')->once()->with([
            'to'      => '0988123456',
            'message' => 'my test message here'
        ]);

        $fakeEvent = \Mockery::mock(SendSMSEvent::class);
        $fakeEvent->shouldReceive('getData')->once()->andReturn([
            'phone'   => '0988123456',
            'message' => 'my test message here',
            'user'    => [
                'id' => 1
            ]
        ]);
        $fakeEvent->shouldReceive('getCourier')->once()->andReturn($fakeCourier);

        $handler = new SendSMS();

        // Act & Assert
        $handler->handle($fakeEvent);
    }
}

<?php

use App\Events\SendSMSEvent;
use App\Handlers\Events\SendSMS;
use App\Sms\SmsCourierInterface;
use App\User;
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
        $data = [
            'phone' => '0988123456',
            'message' => 'my test message here',
            'user'  => [
                'id' => 1
            ]
        ];

        // 挑戰 2：在修改最少的情況下，讓這個 Mitake_SMS 類別可以被 Mock 取代，進而測試 handle 方法。
        // Solution 1:
        $fakeEvent = \Mockery::mock(SendSMSEvent::class);
        $fakeCourier = \Mockery::mock(SmsCourierInterface::class);

        $fakeEvent->shouldReceive('getData')->once()->andReturn($data);
        $fakeEvent->shouldReceive('getCourier')->once()->andReturn($fakeCourier);
        $fakeCourier->shouldReceive('sendTextMessage')->once()->with([
            'to'      => $data['phone'],
            'message' => $data['message']
        ]);

        // 挑戰 3：如何在不觸及資料庫操作的前提下，寫測試驗證 handle 方法內的處理邏輯？
        // Solution 1:
        $users = \Mockery::mock(User::class);
        $fakeUser = \Mockery::mock(User::class);
        $relation = \Mockery::mock('stdClass');

        $users->shouldReceive('find')->once()->andReturn($fakeUser);
        $fakeUser->shouldReceive('messages')->once()->andReturn($relation);
        $relation->shouldReceive('create')->once()->with([
            'to'      => $data['phone'],
            'message' => $data['message']
        ]);

        // Act & Assert
        (new SendSMS($users))->handle($fakeEvent);
    }
}

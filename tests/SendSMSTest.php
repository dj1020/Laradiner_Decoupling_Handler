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
            'phone'   => '0988123456',
            'message' => 'my test message here',
        ];

        $user = \Mockery::mock('App\User[sms]');
        $courier = \Mockery::mock(SmsCourierInterface::class);
        $relation = \Mockery::mock('stdClass');

        $courier->shouldReceive('sendTextMessage')->once()->with([
            'to'      => $data['phone'],
            'message' => $data['message']
        ]);

        $user->shouldReceive('sms')->once()->andReturn($relation);

        $relation->shouldReceive('create')->once()->with([
            'to'      => $data['phone'],
            'message' => $data['message']
        ]);

        // Act & Assert
        $user->sendSms($courier, $data['phone'], $data['message']);
    }
}

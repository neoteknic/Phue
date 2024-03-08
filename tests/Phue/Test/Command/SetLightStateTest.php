<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Command;

use PHPUnit\Framework\TestCase;
use Phue\Command\SetLightState;
use Phue\Helper\ColorConversion;
use Phue\Light;
use Phue\Transport\TransportInterface;
use Phue\Client;

/**
 * Tests for Phue\Command\SetLightState
 */
class SetLightStateTest extends TestCase
{
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock(Client::class);
        
        // Mock transport
        $this->mockTransport = $this->createMock(TransportInterface::class);
        
        // Mock light
        $this->mockLight = $this->createMock(
            Light::class,
            null,
            array(
                3,
                new \stdClass(),
                $this->mockClient
            )
        );
        
        // Stub client's getUsername method
        $this->mockClient->expects($this->any())
            ->method('getUsername')
            ->willReturn('abcdefabcdef01234567890123456789');
        
        // Stub client's getTransport method
        $this->mockClient->expects($this->any())
            ->method('getTransport')
            ->willReturn($this->mockTransport);
    }

    /**
     * Test: Set light on
     *
     * @dataProvider providerOnState
     *
     * @covers \Phue\Command\SetLightState::on
     * @covers \Phue\Command\SetLightState::send
     */
    public function testOnSend($state): void
    {
        // Build command
        $command = new SetLightState($this->mockLight);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) [
                'on' => $state
            ]
        );
        
        // Ensure instance is returned
        $this->assertEquals($command, $command->on($state));
        
        // Send
        $command->send($this->mockClient);
    }

    /**
     * Test: Invalid brightness
     *
     * @dataProvider providerInvalidBrightness
     *
     * @covers \Phue\Command\SetLightState::brightness
     *
     *
     */
    public function testInvalidBrightness($brightness): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $x = new SetLightState($this->mockLight);
        $x->brightness($brightness);
    }

    /**
     * Test: Set brightness
     *
     * @dataProvider providerBrightness
     *
     * @covers \Phue\Command\SetLightState::brightness
     * @covers \Phue\Command\SetLightState::send
     */
    public function testBrightnessSend($brightness): void
    {
        // Build command
        $command = new SetLightState($this->mockLight);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'bri' => $brightness
            )
        );
        
        // Ensure instance is returned
        $this->assertEquals($command, $command->brightness($brightness));
        
        // Send
        $command->send($this->mockClient);
    }

    /**
     * Test: Invalid hue value
     *
     * @covers \Phue\Command\SetLightState::hue
     *
     *
     */
    public function testInvalidHueValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $x = new SetLightState($this->mockLight);
        $x->hue(70000);
    }

    /**
     * Test: Set hue
     *
     * @dataProvider providerHue
     *
     * @covers \Phue\Command\SetLightState::hue
     * @covers \Phue\Command\SetLightState::send
     */
    public function testHueSend($value): void
    {
        // Build command
        $command = new SetLightState($this->mockLight);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'hue' => $value
            )
        );
        
        // Ensure instance is returned
        $this->assertEquals($command, $command->hue($value));
        
        // Send
        $command->send($this->mockClient);
    }

    /**
     * Test: Invalid saturation value
     *
     * @covers \Phue\Command\SetLightState::saturation
     *
     *
     */
    public function testInvalidSaturationValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $x = new SetLightState($this->mockLight);
        $x->saturation(300);
    }

    /**
     * Test: Set alert mode
     *
     * @dataProvider providerSaturation
     *
     * @covers \Phue\Command\SetLightState::saturation
     * @covers \Phue\Command\SetLightState::send
     */
    public function testSaturationSend($value): void
    {
        // Build command
        $command = new SetLightState($this->mockLight);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'sat' => $value
            )
        );
        
        // Ensure instance is returned
        $this->assertEquals($command, $command->saturation($value));
        
        // Send
        $command->send($this->mockClient);
    }

    /**
     * Test: Invalid xy value
     *
     * @dataProvider providerInvalidXY
     *
     * @covers \Phue\Command\SetLightState::xy
     *
     *
     */
    public function testInvalidXYValue($x, $y): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $_x = new SetLightState($this->mockLight);
        $_x->xy($x, $y);
    }

    /**
     * Test: Set XY
     *
     * @dataProvider providerXY
     *
     * @covers \Phue\Command\SetLightState::xy
     * @covers \Phue\Command\SetLightState::send
     */
    public function testXYSend($x, $y): void
    {
        // Build command
        $command = new SetLightState($this->mockLight);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'xy' => array(
                    $x,
                    $y
                )
            )
        );
        
        // Ensure instance is returned
        $this->assertEquals($command, $command->xy($x, $y));
        
        // Send
        $command->send($this->mockClient);
    }

    /**
     * Test: invalid RGB value
     *
     * @dataProvider providerInvalidRGB
     *
     * @covers \Phue\Command\SetLightState::rgb
     *
     *
     */
    public function testInvalidRGBValue($red, $green, $blue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $_x = new SetLightState($this->mockLight);
        $_x->rgb($red, $green, $blue);
    }

    /**
     * Test: set XY and brightness via RGB
     *
     * @dataProvider providerRGB
     *
     * @covers \Phue\Command\SetLightState::rgb
     * @covers \Phue\Command\SetLightState::send
     */
    public function testRGBSend($red, $green, $blue): void
    {
        // Build command
        $command = new SetLightState($this->mockLight);

        // Set expected payload
        $xy = ColorConversion::convertRGBToXY($red, $green, $blue);
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'xy' => array(
                    $xy['x'],
                    $xy['y']
                ),
                'bri' => $xy['bri']
            )
        );

        // Ensure instance is returned
        $this->assertEquals($command, $command->rgb($red, $green, $blue));

        // Send
        $command->send($this->mockClient);
    }

    /**
     * Test: Invalid color temp value
     *
     * @dataProvider providerInvalidColorTemp
     *
     * @covers \Phue\Command\SetLightState::colorTemp
     *
     *
     */
    public function testInvalidColorTempValue($temp): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $x = new SetLightState($this->mockLight);
        $x->colorTemp($temp);
    }

    /**
     * Test: Set Color temp
     *
     * @dataProvider providerColorTemp
     *
     * @covers \Phue\Command\SetLightState::colorTemp
     * @covers \Phue\Command\SetLightState::send
     */
    public function testColorTempSend($temp): void
    {
        // Build command
        $command = new SetLightState($this->mockLight);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'ct' => $temp
            )
        );
        
        // Ensure instance is returned
        $this->assertEquals($command, $command->colorTemp($temp));
        
        // Send
        $command->send($this->mockClient);
    }

    /**
     * Test: Get alert modes
     *
     * @covers \Phue\Command\SetLightState::getAlertModes
     */
    public function testGetAlertModes(): void
    {
        $this->assertNotEmpty(SetLightState::getAlertModes());
        
        $this->assertTrue(
            in_array(SetLightState::ALERT_SELECT, SetLightState::getAlertModes())
        );
    }

    /**
     * Test: Invalid alert mode
     *
     * @covers \Phue\Command\SetLightState::alert
     *
     *
     */
    public function testInvalidAlertMode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $x = new SetLightState($this->mockLight);
        $x->alert('invalidmode');
    }

    /**
     * Test: Set alert mode
     *
     * @dataProvider providerAlert
     *
     * @covers \Phue\Command\SetLightState::alert
     * @covers \Phue\Command\SetLightState::send
     */
    public function testAlertSend($mode): void
    {
        // Build command
        $command = new SetLightState($this->mockLight);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'alert' => $mode
            )
        );
        
        // Ensure instance is returned
        $this->assertEquals($command, $command->alert($mode));
        
        // Send
        $command->send($this->mockClient);
    }

    /**
     * Test: Get effect modes
     *
     * @covers \Phue\Command\SetLightState::getEffectModes
     */
    public function testGetEffectModes(): void
    {
        $this->assertNotEmpty(SetLightState::getEffectModes());
        
        $this->assertTrue(
            in_array(SetLightState::EFFECT_NONE, SetLightState::getEffectModes())
        );
    }

    /**
     * Test: Invalid effect mode
     *
     * @covers \Phue\Command\SetLightState::effect
     *
     *
     */
    public function testInvalidEffectMode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $x = new SetLightState($this->mockLight);
        $x->effect('invalidmode');
    }

    /**
     * Test: Set effect mode
     *
     * @dataProvider providerEffect
     *
     * @covers \Phue\Command\SetLightState::effect
     * @covers \Phue\Command\SetLightState::send
     */
    public function testEffectSend($mode): void
    {
        // Build command
        $command = new SetLightState($this->mockLight);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'effect' => $mode
            )
        );
        
        // Ensure instance is returned
        $this->assertEquals($command, $command->effect($mode));
        
        // Send
        $command->send($this->mockClient);
    }

    /**
     * Test: Invalid transition time
     *
     * @covers \Phue\Command\SetLightState::transitionTime
     *
     *
     */
    public function testInvalidTransitionTime(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $x = new SetLightState($this->mockLight);
        $x->transitionTime(- 10);
    }

    /**
     * Test: Set transition time
     *
     * @dataProvider providerTransitionTime
     *
     * @covers \Phue\Command\SetLightState::transitionTime
     * @covers \Phue\Command\SetLightState::send
     */
    public function testTransitionTimeSend($time): void
    {
        // Build command
        $command = new SetLightState($this->mockLight);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'transitiontime' => $time * 10
            )
        );
        
        // Ensure instance is returned
        $this->assertEquals($command, $command->transitionTime($time));
        
        // Send
        $command->send($this->mockClient);
    }

    /**
     * Test: Send command
     *
     * @covers \Phue\Command\SetLightState::__construct
     * @covers \Phue\Command\SetLightState::send
     */
    public function testSend(): void
    {
        // Build command
        $setLightStateCmd = new SetLightState($this->mockLight);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) [
                'alert' => 'select'
            ]
        );
        
        // Change alert and set state
        $setLightStateCmd->alert('select')->send($this->mockClient);
    }

    /**
     * Test: Get actionable params
     *
     * @covers \Phue\Command\SetLightState::getActionableParams
     */
    public function testGetActionableParams(): void
    {
        // Build command
        $setLightStateCmd = new SetLightState($this->mockLight);
        
        // Change alert
        $setLightStateCmd->alert('select');
        
        // Ensure actionable params are expected
        $this->assertEquals(
            [
                'address' => "/lights/{$this->mockLight->getId()}/state",
                'method' => 'PUT',
                'body' => (object) [
                    'alert' => 'select'
                ]
            ],
            $setLightStateCmd->getActionableParams($this->mockClient)
        );
    }

    /**
     * Stub transport's sendRequest with an expected payload
     */
    protected function stubTransportSendRequestWithPayload(\stdClass $payload): void
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo(
                    "/api/{$this->mockClient->getUsername()}/lights/{$this->mockLight->getId()}/state"
                ),
                $this->equalTo('PUT'),
                $payload
            );
    }

    /**
     * Provider: On state
     *
     * @return array
     */
    public static function providerOnState(): array
    {
        return [
            [
                true
            ],
            [
                false
            ]
        ];
    }

    /**
     * Provider: Invalid brightness
     *
     * @return array
     */
    public static function providerInvalidBrightness(): array
    {
        return [
            [- 1],
            [256]
        ];
    }

    /**
     * Provider: Valid brightness
     *
     * @return array
     */
    public static function providerBrightness(): array
    {
        return [
            [0],
            [128],
            [255]
        ];
    }

    /**
     * Provider: Hue
     *
     * @return array
     */
    public static function providerHue(): array
    {
        return [
            [10000],
            [35000],
            [42]
        ];
    }

    /**
     * Provider: Saturation
     *
     * @return array
     */
    public static function providerSaturation(): array
    {
        return [
            [0],
            [128],
            [255]
        ];
    }

    /**
     * Provider: Invalid XY
     *
     * @return array
     */
    public static function providerInvalidXY(): array
    {
        return [
            [
                - 0.1,
                - 0.1
            ],
            [
                .5,
                - .5
            ],
            [
                1.1,
                .5
            ],
            [
                .5,
                1.1
            ]
        ];
    }

    /**
     * Provider: XY
     *
     * @return array
     */
    public static function providerXY(): array
    {
        return [
            [
                0,
                1
            ],
            [
                .1,
                .9
            ],
            [
                .5,
                .5
            ]
        ];
    }

    /**
     * Provider: Invalid RGB
     *
     * @return array
     */
    public static function providerInvalidRGB(): array
    {
        return [
            [
                - 1,
                - 1,
                - 1
            ],
            [
                50,
                - 50,
                50
            ],
            [
                256,
                50,
                50
            ],
            [
                50,
                256,
                50
            ],
            [
                50,
                50,
                256
            ]
        ];
    }

    /**
     * Provider: RGB
     *
     * @return array
     */
    public static function providerRGB(): array
    {
        return array(
            array(
                0,
                150,
                255
            ),
            array(
                10,
                135,
                245
            ),
            array(
                150,
                150,
                150
            )
        );
    }

    /**
     * Provider: Invalid Color temp
     *
     * @return array
     */
    public static function providerInvalidColorTemp(): array
    {
        return [
            [
                152
            ],
            [
                550
            ],
            [
                - 130
            ]
        ];
    }

    /**
     * Provider: XY
     *
     * @return array
     */
    public static function providerColorTemp(): array
    {
        return [
            [
                153
            ],
            [
                200
            ],
            [
                500
            ]
        ];
    }

    /**
     * Provider: Alert
     *
     * @return array
     */
    public static function providerAlert(): array
    {
        return [
            [
                SetLightState::ALERT_NONE
            ],
            [
                SetLightState::ALERT_SELECT
            ],
            [
                SetLightState::ALERT_LONG_SELECT
            ]
        ];
    }

    /**
     * Provider: Effect
     *
     * @return array
     */
    public static function providerEffect(): array
    {
        return [
            [
                SetLightState::EFFECT_NONE
            ],
            [
                SetLightState::EFFECT_COLORLOOP
            ]
        ];
    }

    /**
     * Provider: Transition time
     *
     * @return array
     */
    public static function providerTransitionTime(): array
    {
        return [
            [
                1
            ],
            [
                25
            ],
            [
                .5
            ]
        ];
    }
}

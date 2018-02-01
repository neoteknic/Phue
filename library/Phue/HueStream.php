<?php
/**
 * Created by PhpStorm.
 * User: neoteknic
 * Date: 01/02/2018
 * Time: 20:16
 */

namespace Phue;


class HueStream {
	//https://developers.meethue.com/documentation/hue-entertainment-api
	//UDP port 2100 is used for DTLS
	public const DTLS_PORT=2100;
	private $bridge_ip;
	private $socket=null;
	private $username;
	private $color_mode=0x00;

	/**
	 * @var array $data
	 */
	private $data;
	/**
	 * HueStream constructor.
	 * @param string $ip
	 * @param string $user
	 */
	public function __construct($ip,$user) {
		$this->bridge_ip=$ip;
		$this->username=$user;
	}

	public function open() {
		//Create a UDP socket
		$this->socket=fsockopen('udp://'.$this->bridge_ip,self::DTLS_PORT,$errno, $errstr);
		if (!$this->socket) {
			return $this;
		}
		return $this;
	}

	public function close(){
		@fclose($this->socket);
		return $this;
	}

	public function resetData() {
		$this->data=[];
	}

	/**
	 * @param $id
	 * @param array $color Array or 3 color, 8 or 16 bit int can be r,g,b array or 0,1,2 array
	 * @param bool $bit_8_mode Defaut 8 bit mode, false to use 16 bit color
	 * @return $this
	 */
	public function setLight($id,$color,$bit_8_mode=true) {
		if(count($color)!==3)
			return $this;
		if(!isset($color['r'])){
			$color=[
				'r'=>$color[0],
				'g'=>$color[1],
				'b'=>$color[2]
			];
		}
		$this->data[]=0x00;
		$this->data[]=$this->toTwoBytes($id);
		//Use 8 bit per color
		if($bit_8_mode){
			$r=$this->toOneByte($color['r']);
			$this->data[]=$r;
			$this->data[]=$r;
			$g=$this->toOneByte($color['g']);
			$this->data[]=$g;
			$this->data[]=$g;
			$b=$this->toOneByte($color['b']);
			$this->data[]=$b;
			$this->data[]=$b;
		}
		else{
			$this->data[]=$this->toTwoBytes($color['r']);
			$this->data[]=$this->toTwoBytes($color['g']);
			$this->data[]=$this->toTwoBytes($color['b']);
		}

		return $this;
	}

	public function stream() {
		if(!$this->socket){
			$this->open();
		}
		if($this->socket){
			$string = '';
			foreach (array_merge($this->getHeaders(),$this->data) as $chr) {
				$string .= chr($chr);
			}
			fwrite($this->socket,$string."\n");
			//echo fread($this->socket,1000);
			echo 'write ok';
		}
		return $this;
	}

	/**
	 * Change color mode, xy or rgb
	 * @param string $mode xy or rgb only (case insensitive)
	 */
	public function changeColorMode($mode){
		switch (strtolower($mode)){
			case 'rgb':
			case 'rvb':
				$this->color_mode=0x00;
			break;
			case 'xy':
				$this->color_mode=0x01;
			break;
		}
		return $this;
	}

	private function getHeaders():array{
		return [
			'H', 'u', 'e', 'S', 't', 'r', 'e', 'a', 'm',
			0x01, 0x00, //Version 1.00x07, //sequence number 7
			0x00, 0x00, //Reserved write
			$this->color_mode, //color mode
			0x00, // Reserved, write
		];
	}
	private function toTwoBytes($nb){
		return array_reverse(unpack('C*', pack('S', $nb)));
	}
	private function toOneByte($nb){
		return unpack('C*', pack('C', $nb));
	}

//0x00, 0x00, 0x01, //light ID 1
//0xff, 0xff, 0x00, 0x00, 0x00, 0x00, //red
//0x00, 0x00, 0x02, //light ID 2
//0x00, 0x00, 0x00, 0x00, 0xff, 0xff //blue
}
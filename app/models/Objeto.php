<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Objeto extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'objetos';

	public function GetType($type_id){

    	switch ($type_id) {
    		case '1':
    			return "Objeto";
    			break;
    		case '2':
    			return "Animal";
    			break;
    		case '3':
    			return "Persona";
    			break;
    		default:
    			return "Objeto";
    			break;
    	}

    }

	
}

?>
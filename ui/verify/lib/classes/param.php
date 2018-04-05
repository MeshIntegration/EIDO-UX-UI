<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of param
 *
 * @author sagar
 */
class param {

   //put your code here
   public $title = "EIDO";
   
   /**
    * 
    * @param string $title
    * @param mixed $attribute
    */
   function __construct($title, $attribute = array()) {
      $this->title = $title;
      foreach ($attribute as $key => $value) {
         $this->{$key} = $value;
      }
   }

}

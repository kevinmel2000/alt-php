<?php

class Minerva {
    public static function registry(){
        $rc = new ReflectionClass(get_class());

        $registry = array(

        );

        return array_merge($registry, $rc->getConstants(), $rc->getStaticProperties());
    }
}
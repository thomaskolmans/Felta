<?php

namespace lib\shop\product;

use lib\Felta;

class Location
{

    private $sql;

    private $id;
    private $street;
    private $houseNumber;
    private $postalCode;
    private $city;
    private $country;

    private $latitude;
    private $longitude;

    private $createdAt;
    private $updatedAt;

    public function __construct($id, $street, $houseNumber, $postalCode, $city, $country, $latitude, $longitude, $createdAt, $updatedAt)
    {

        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;

        $this->street = $street;
        $this->houseNumber = $houseNumber;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->$country = $country;

        $this->latitude = $latitude;
        $this->longitude = $longitude;

        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId()
    {
        return $this->id;
    }


    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function getStreet()
    {
        return $this->street;
    }


    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }


    public function getHouseNumber()
    {
        return $this->houseNumber;
    }


    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
        return $this;
    }


    public function getPostalCode()
    {
        return $this->postalCode;
    }


    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }


    public function getCity()
    {
        return $this->city;
    }


    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }


    public function getCountry()
    {
        return $this->country;
    }


    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }


    public function getLatitude()
    {
        return $this->latitude;
    }


    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }


    public function getLongitude()
    {
        return $this->longitude;
    }


    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }


    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }


    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}

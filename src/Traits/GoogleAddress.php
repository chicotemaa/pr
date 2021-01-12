<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * GoogleAddress Trait.
 *
 * @author Gonzalo Alonso <gonkpo@gmail.com>
 */
trait GoogleAddress
{
    /**
     * @var string
     * @ORM\Column(name="street", type="string", length=100, nullable=true)
     * @Groups({"read"})
     */
    protected $street;

    /**
     * @var string
     * @ORM\Column(name="city", type="string", length=50, nullable=true)
     * @Groups({"read"})
     */
    protected $city;

    /**
     * @var string
     * @ORM\Column(name="province", type="string", length=50, nullable=true)
     * @Groups({"read"})
     */
    protected $province;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=50, nullable=true)
     * @Groups({"read"})
     */
    protected $country;

    /**
     * Get Google Address.
     *
     * @return string
     */
    public function getGoogleAddress()
    {
        $address = '';
        if ($this->street) {
            $address .= $this->street.', ';
        }
        if ($this->city) {
            $address .= $this->city.', ';
        }
        if ($this->province) {
            $address .= $this->province.', ';
        }
        if ($this->country) {
            $address .= $this->country;
        }

        return $address;
    }

    /**
     * Set street.
     *
     * @param string $street
     *
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street.
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set province.
     *
     * @param string $province
     *
     * @return $this
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province.
     *
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}

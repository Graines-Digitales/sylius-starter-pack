<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait AccommodationTrait
{
    /**
     * @var string|null PDF URL of the item
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(iri="http://schema.org/url")
     * @Assert\Url
     */
    private $pdfUrl;

    /**
     * @var string|null API URL of the item
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(
     *     iri="http://schema.org/url")
     *     attributes={
     *         "jsonld_context"={
     *             "@id"="http://yourcustomid.com",
     *             "@var"="http://www.w3.org/2001/XMLSchema#string",
     *             "someProperty"={
     *                 "a"="textA",
     *                 "b"="textB"
     *             }
     *         }
     *     }
     * @Assert\Url
     */
    private $websiteUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=15, nullable=true)
     */
    private $reference;

    /**
     * @ var float[]|null The number of rooms (excluding bathrooms and closets) of the acccommodation or lodging business. Typical unit code(s): ROM for room or C62 for no unit. The type of room can be put in the unitText property of the QuantitativeValue.
     *
     * @ ORM\Column(type="simple_array", nullable=true)
     * @ApiProperty(iri="http://schema.org/numberOfRooms")
     *
     * @var int
     *
     * @ORM\Column(name="numberOfPieces", type="smallint", nullable=true)
     * @ Assert\NotNull
     */
    private $numberOfPieces;

    /**
     * @ var float[]|null The number of rooms (excluding bathrooms and closets) of the acccommodation or lodging business. Typical unit code(s): ROM for room or C62 for no unit. The type of room can be put in the unitText property of the QuantitativeValue.
     *
     * @ ORM\Column(type="simple_array", nullable=true)
     * @ApiProperty(iri="http://schema.org/numberOfRooms")
     *
     * @var int
     *
     * @ORM\Column(name="numberOfRooms", type="smallint")
     *
     * @Assert\NotBlank(message="entrez le nombre de chambres")
     */
    private $numberOfRooms;

    /**
     * @ORM\Column(name="numberOfBathrooms", type="smallint", nullable=true)
     * 
     * @ Assert\NotNull
     */
    private $numberOfBathrooms;

    /**
     * @ORM\Column(name="maximumOccupants", type="smallint", nullable=true)
     *
     * @ Assert\NotBlank(message="entrez le nombre d'occupants")
     */
    private $maximumOccupants;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", length=6)
     * 
     * @Assert\NotBlank(message="entrez le prix du bien")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="labelPrice", type="string", length=255, nullable=true)
     */
    private $labelPrice;

    /**
     * @ORM\Column(name="floorSize", type="smallint")
     * 
     * @Assert\NotBlank(message="entrez dans l'espace de vie")
     */
    private $floorSize;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * = Land area = Surface de terrain
     */
    private $areaSize;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $areaTerrace;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\AccommodationPlace")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     * choice(campagne, golf-mogador, médina, nouvelle-ville)
     *
     * @Assert\NotBlank(message="entrez le lieu de la propriété")
     */
    private $place;

    /**
     * @ORM\ManyToMany(targetEntity="\App\Entity\AmenityFeature", inversedBy="accommodations")
     * @ORM\JoinTable(name="app_accommodations_amenities")
     */
    private $amenityFeatures;


    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\AccommodationLabel")
     * @ORM\JoinColumn(name="label_id", referencedColumnName="id")
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\AccommodationNature")
     * @ORM\JoinColumn(name="nature_id", referencedColumnName="id")
     * choice(location, achat ... etc)
     *
     * @Assert\NotBlank(message="sélectionnez la nature du bien")
     */
    private $nature;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\AccommodationType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * choice(maison, appart ... etc)
     *
     * @Assert\NotBlank(message="sélectionnez le type de propriété")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\AccommodationLocation")
     * @ORM\JoinColumn(name="duration_id", referencedColumnName="id")
     *
     * @Assert\Expression(
     *     "(this.getDuration() && this.getNature() == 'Location') || (this.getNature() == 'Vente')",
     *     message="veuillez entrer une durée"
     * )
     */
    private $duration;

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * Set the value of Price.
     *
     * @param float price
     *
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of Price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of LabelPrice.
     *
     * @param float labelPrice
     *
     * @return self
     */
    public function setLabelPrice($labelPrice)
    {
        $this->labelPrice = $labelPrice;

        return $this;
    }

    /**
     * Get the value of LabelPrice.
     *
     * @return string
     */
    public function getLabelPrice()
    {
        return $this->labelPrice;
    }

    /**
     * Set the value of Floor Size.
     *
     * @param int floorSize
     *
     * @return self
     */
    public function setFloorSize($floorSize)
    {
        $this->floorSize = $floorSize;

        return $this;
    }

    /**
     * Get the value of Floor Size.
     *
     * @return int
     */
    public function getFloorSize()
    {
        return $this->floorSize;
    }

    public function getPlace(): ?\App\Entity\AccommodationPlace
    {
        return $this->place;
    }

    public function setPlace(?\App\Entity\AccommodationPlace $place): self
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Set the value of duration.
     *
     * @param duration
     *
     * @return self
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get the value of duration.
     *
     * @return array
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Get the value of Amenities.
     *
     * @return Collection|Amenity[]
     */
    public function getAmenities(): Collection
    {
        return $this->amenityFeatures;
    }

    /**
     * Set the value of Amenities.
     *
     * @param mixed amenityFeatures
     *
     * @return self
     */
    public function setAmenities($amenityFeatures)
    {
        $this->amenityFeatures = $amenityFeatures;

        return $this;
    }

    /**
     * Add amenityFeature.
     *
     * @return Amenity
     */
    public function addAmenity(\App\Entity\AmenityFeature $amenityFeature): self
    {
        // Bidirectional Ownership
        $amenityFeature->addAccommodation($this);

        $this->amenityFeatures[] = $amenityFeature;

        return $this;
    }

    /**
     * Remove amenityFeature.
     */
    public function removeAmenity(\App\Entity\AmenityFeature $amenityFeature)
    {
        $this->amenityFeatures->removeElement($amenityFeature);
    }

    /**
     * Set the value of Nature.
     *
     * @param array nature
     *
     * @return self
     */
    public function setNature($nature)
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * Get the value of Nature.
     *
     * @return array
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Set the value of Type.
     *
     * @param array type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of Type.
     *
     * @return array
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get videos.
     *
     * @return Collection|Accommodation[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    /**
     * Get pdfs.
     *
     * @return Collection|Accommodation[]
     */
    public function getPdfs(): Collection
    {
        return $this->pdfs;
    }

    /**
     * Get the value of MaximumOccupants.
     *
     * @return int
     */
    public function getMaximumOccupants()
    {
        return $this->maximumOccupants;
    }

    /**
     * Set the value of MaximumOccupants.
     *
     * @param int MaximumOccupants
     *
     * @return self
     */
    public function setMaximumOccupants($maximumOccupants)
    {
        $this->maximumOccupants = $maximumOccupants;

        return $this;
    }

    /**
     * Get the value of Number Of Bathrooms.
     *
     * @return int
     */
    public function getNumberOfBathrooms()
    {
        return $this->numberOfBathrooms;
    }

    /**
     * Set the value of Number Of Bathrooms.
     *
     * @param int numberOfBathrooms
     *
     * @return self
     */
    public function setNumberOfBathrooms($numberOfBathrooms)
    {
        $this->numberOfBathrooms = $numberOfBathrooms;

        return $this;
    }

    /**
     * Get the value of Number Of Rooms.
     *
     * @return int
     */
    public function getNumberOfRooms()
    {
        return $this->numberOfRooms;
    }

    /**
     * Set the value of Number Of Rooms.
     *
     * @param int numberOfRooms
     *
     * @return self
     */
    public function setNumberOfRooms($numberOfRooms)
    {
        $this->numberOfRooms = $numberOfRooms;

        return $this;
    }

    /**
     * Get the value of Number Of Pieces.
     *
     * @return int
     */
    public function getNumberOfPieces()
    {
        return $this->numberOfPieces;
    }

    /**
     * Set the value of Number Of Pieces.
     *
     * @param int numberOfPieces
     *
     * @return self
     */
    public function setNumberOfPieces($numberOfPieces)
    {
        $this->numberOfPieces = $numberOfPieces;

        return $this;
    }

    /**
     * Set the value of Pdf Url.
     *
     * @param string|null PDF URL of the item pdfUrl
     *
     * @return self
     */
    public function setPdfUrl(string $pdfUrl)
    {
        $this->pdfUrl = $pdfUrl;

        return $this;
    }

    /**
     * Get the value of Pdf Url.
     *
     * @return string|null PDF URL of the item
     */
    public function getPdfUrl()
    {
        return $this->pdfUrl;
    }

    /**
     * Set the value of Website Url.
     *
     * @param string|null API URL of the item websiteUrl
     *
     * @return self
     */
    public function setWebsiteUrl(string $websiteUrl)
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    /**
     * Get the value of Website Url.
     *
     * @return string|null API URL of the item
     */
    public function getWebsiteUrl()
    {
        return $this->websiteUrl;
    }

    public function getAreaTerrace(): ?int
    {
        return $this->areaTerrace;
    }

    public function setAreaTerrace(?int $areaTerrace): self
    {
        $this->areaTerrace = $areaTerrace;

        return $this;
    }

    public function getAreaSize(): ?int
    {
        return $this->areaSize;
    }

    public function setAreaSize(?int $areaSize): self
    {
        $this->areaSize = $areaSize;

        return $this;
    }

    /**
     * Set the value of Label.
     *
     * @param mixed label
     *
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the value of Label.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

}

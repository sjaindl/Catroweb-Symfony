<?php
namespace Catrobat\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="featured")
 */
class FeaturedProgram
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $image;
    
    /**
     * @ORM\OneToOne(targetEntity="Program",fetch="EAGER")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id", nullable=true)
     **/
    private $program;

    /**
     * @ORM\Column(type="string")
     */
    private $url;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return FeaturedProgram
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set program
     *
     * @param \Catrobat\CoreBundle\Entity\Program $program
     * @return FeaturedProgram
     */
    public function setProgram(\Catrobat\CoreBundle\Entity\Program $program = null)
    {
        $this->program = $program;
    
        return $this;
    }

    /**
     * Get program
     *
     * @return \Catrobat\CoreBundle\Entity\Program 
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return FeaturedProgram
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
}

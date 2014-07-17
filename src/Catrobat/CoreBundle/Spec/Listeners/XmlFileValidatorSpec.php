<?php

namespace Catrobat\CoreBundle\Spec\Listeners;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Util\StringUtils;

class XmlFileValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Catrobat\CoreBundle\Listeners\XmlFileValidator');
    }

    /**
     * @param \Catrobat\CoreBundle\Model\ExtractedCatrobatFile $file
     */
    function it_throws_nothing_if_the_default_application_version_is_validated($file)
    {
        $applicationVersion = "0.9.7";
        if(StringUtils::equals($applicationVersion, $file->getApplicationVersion()))
        {
            $file->getApplicationVersion()->willReturn($applicationVersion);
            shouldNotThrow('Catrobat\CoreBundle\Exceptions\InvalidCatrobatFileException')->duringValidate($file);
        }
    }

    /**
     * @param \Catrobat\CoreBundle\Model\ExtractedCatrobatFile $file
     */
    function it_throws_nothing_if_the_default_catrobat_language_is_validated($file)
    {
        $languageVersion = "0.92";
        $program_xml_properties = $file->getProgramXmlProperties();
        if(StringUtils::equals($languageVersion, $program_xml_properties->header->catrobatLanguageVersion))
        {
            $file->getApplicationVersion()->willReturn($languageVersion);
            shouldNotThrow('Catrobat\CoreBundle\Exceptions\InvalidCatrobatFileException')->duringValidate($file);
        }
    }

    /**
     * @param \Catrobat\CoreBundle\Model\ExtractedCatrobatFile $file
     */
    function it_throws_an_exception_if_the_file_extension_is_invalid($file)
    {

    }
}

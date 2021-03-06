<?php

namespace Catrobat\ApiBundle\Features\Context;

use Behat\Behat\Tester\Exception\PendingException;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Catrobat\CoreBundle\Entity\User;
use Catrobat\CoreBundle\Entity\Program;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Behat\Behat\Context\CustomSnippetAcceptingContext;

//
// Require 3rd-party libraries here:
//
//require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class FeatureContext implements KernelAwareContext, CustomSnippetAcceptingContext
{
    const FIXTUREDIR = "./src/Catrobat/TestBundle/DataFixtures/";
    private $kernel;

    private $user;
    private $request_parameters;
    
    private $files;

    public static function getAcceptedSnippetType() { return 'regex'; }

  /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct()
    {
        $this->request_parameters = array();
        $this->files = array();
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^the upload folder is empty$/
     */
    public function theUploadFolderIsEmpty()
    {
      $extract_dir = $this->kernel->getContainer()->getParameter("catrobat.file.storage.dir");
      $this->emptyDirectory($extract_dir);
    }
    
    /**
     * @Given /^the extract folder is empty$/
     */
    public function theExtractFolderIsEmpty()
    {
      $extract_dir = $this->kernel->getContainer()->getParameter("catrobat.file.extract.dir");
      $this->emptyDirectory($extract_dir);
    }
    
    /**
     * @Given /^there are users:$/
     */
    public function thereAreUsers(TableNode $table)
    {
      $user_manager =  $this->kernel->getContainer()->get('catrobat.core.model.usermanager');
      $users = $table->getHash();
      $user = null;
      for ($i = 0; $i < count($users); $i++)
      {
      	$user = $user_manager->createUser();
      	$user->setUsername($users[$i]["name"]);
      	$user->setEmail("dev".$i."@pocketcode.org");
      	$user->setPlainPassword($users[$i]["password"]);
      	$user->setEnabled(true);
      	$user->setUploadToken($users[$i]["token"]);
      	$user_manager->updateUser($user,false);
      }
      $user_manager->updateUser($user,true);
    }
    
    /**
     * @Given /^there are programs:$/
     */
    public function thereArePrograms(TableNode $table)
    {
    	$em = $this->kernel->getContainer()->get('doctrine')->getManager();
    	$programs = $table->getHash();
    	for ($i = 0; $i < count($programs); $i++)
    	{
    		$user = $em->getRepository('CatrobatCoreBundle:User')->findOneBy(array('username' => $programs[$i]['owned by']));
				$program = new Program();
				$program->setUser($user);
				$program->setName($programs[$i]['name']);
				$program->setDescription($programs[$i]['description']);
				$program->setFilename("file".$i.".catrobat");
				$program->setThumbnail("thumb.png");
				$program->setScreenshot("screenshot.png");
				$program->setViews($programs[$i]['views']);
				$program->setDownloads($programs[$i]['downloads']);
				$program->setUploadedAt(new \DateTime($programs[$i]['upload time'],new \DateTimeZone('UTC')));
				$program->setCatrobatVersion(1);
				$program->setCatrobatVersionName($programs[$i]['version']);
				$program->setLanguageVersion(1);
				$program->setUploadIp("127.0.0.1");
				$program->setRemixCount(0);
				$program->setFilesize(0);
				$program->setVisible(true);
				$program->setUploadLanguage("en");
				$program->setApproved(false);
				$em->persist($program);
    	}
    	$em->flush();
    }

  /**
     * @Given /^I have a parameter "([^"]*)" with value "([^"]*)"$/
     */
    public function iHaveAParameterWithValue($name, $value)
    {
      $this->request_parameters[$name] = $value;
    }
    
    /**
     * @When /^I POST these parameters to "([^"]*)"$/
     */
    public function iPostTheseParametersTo($url)
    {
      $this->client = $this->kernel->getContainer()->get('test.client');
    	$crawler = $this->client->request('POST', $url, $this->request_parameters, $this->files);
    }

    /**
     * @When /^I GET "([^"]*)" with these parameters$/
     */
    public function iGetWithTheseParameters($url)
    {
      $this->client = $this->kernel->getContainer()->get('test.client');
      $crawler = $this->client->request('GET', $url."?".http_build_query($this->request_parameters), array(), $this->files, array('HTTP_HOST' => 'localhost'));
    }
    
    /**
     * @Given /^I am "([^"]*)"$/
     */
    public function iAm($username)
    {
    	$this->user = $username;
    }
    
    /**
     * @Given /^I search for "([^"]*)"$/
     */
    public function iSearchFor($arg1)
    {
      $this->iHaveAParameterWithValue("q", $arg1);
      if (isset($this->request_parameters['limit']))
      {
        $this->iHaveAParameterWithValue("limit", $this->request_parameters['limit']);
      }
      else
      {
        $this->iHaveAParameterWithValue("limit", "1");
      }
      if (isset($this->request_parameters['offset']))
      {
        $this->iHaveAParameterWithValue("offset", $this->request_parameters['offset']);
      }
      else
      {
        $this->iHaveAParameterWithValue("offset", "0");
      }
      $this->iGetWithTheseParameters("/api/projects/search.json");
    }

    /**
     * @Then /^I should get a total of "([^"]*)" projects$/
     */
    public function iShouldGetATotalOfProjects($arg1)
    {
      $response = $this->client->getResponse();
      $responseArray = json_decode($response->getContent(),true);
      assertEquals($arg1,$responseArray['CatrobatInformation']['TotalProjects'],"Wrong number of total projects");
    }

  /**
     * @Given /^I use the limit "([^"]*)"$/
     */
    public function iUseTheLimit($arg1)
    {
      $this->iHaveAParameterWithValue("limit", $arg1);
    }

    /**
     * @Given /^I use the offset "([^"]*)"$/
     */
    public function iUseTheOffset($arg1)
    {
      $this->iHaveAParameterWithValue("offset", $arg1);
    }

  /**
     * @When /^I call "([^"]*)" with token "([^"]*)"$/
     */
    public function iCallWithToken($url, $token)
    {
      $this->client = $this->kernel->getContainer()->get('test.client');
      $params = array("token" => $token, "username" => $this->user); 
      $crawler = $this->client->request('GET', $url."?".http_build_query($params));
    }
    
    /**
     * @Then /^I should get the json object:$/
     */
    public function iShouldGetTheJsonObject(PyStringNode $string)
    {
      $response = $this->client->getResponse();
      assertJsonStringEqualsJsonString($string->getRaw(), $response->getContent(), "");
    }
    
    /**
     * @Then /^I should get the json object with random token:$/
     */
    public function iShouldGetTheJsonObjectWithRandomToken(PyStringNode $string)
    {
      $response = $this->client->getResponse();
      $responseArray = json_decode($response->getContent(),true);
      $expectedArray = json_decode($string->getRaw(),true);
      $responseArray['token'] = "";
      $expectedArray['token'] = "";
      assertEquals($expectedArray, $responseArray);
    }
    
    /**
     * @Then /^I should get the json object with random "([^"]*)" and "([^"]*)":$/
     */
    public function iShouldGetTheJsonObjectWithRandomAndProgramid($arg1, $arg2, PyStringNode $string)
    {
      $response = $this->client->getResponse();
      $responseArray = json_decode($response->getContent(),true);
      $expectedArray = json_decode($string->getRaw(),true);
      $responseArray[$arg1] = $expectedArray[$arg1] = "";
      $responseArray[$arg2] = $expectedArray[$arg2] = "";
      assertEquals($expectedArray, $responseArray, $response);
    }
    
    /**
     * @Then /^I should get programs in the following order:$/
     */
    public function iShouldGetProgramsInTheFollowingOrder(TableNode $table)
    {
      $response = $this->client->getResponse();
      $responseArray = json_decode($response->getContent(),true);
      $returned_programs = $responseArray['CatrobatProjects'];
      $expected_programs = $table->getHash();
      assertEquals(count($expected_programs), count($returned_programs), "Wrong number of returned programs");
      for ($i = 0; $i < count($returned_programs); $i++)
      {
        assertEquals($expected_programs[$i]["Name"], $returned_programs[$i]["ProjectName"], "Wrong order of results");
      }
    }
    
    /**
     * @Then /^I should get following programs:$/
     */
    public function iShouldGetFollowingPrograms(TableNode $table)
    {
      $this->iShouldGetProgramsInTheFollowingOrder($table);
    }
    
    
    /**
     * @Given /^the response code should be "([^"]*)"$/
     */
    public function theResponseCodeShouldBe($code)
    {
      $response = $this->client->getResponse();
      assertEquals($code, $response->getStatusCode(), "Wrong response code");
    }
    
    /**
     * @Given /^the returned "([^"]*)" should be a number$/
     */
    public function theReturnedShouldBeANumber($arg1)
    {
      $response = json_decode($this->client->getResponse()->getContent(),true);
      assertTrue(is_numeric($response[$arg1]));
    }
    
    /**
     * @Given /^I have a file "([^"]*)"$/
     */
    public function iHaveAFile($filename)
    {
      $filepath = "./src/Catrobat/ApiBundle/Features/Fixtures/".$filename;
      assertTrue(file_exists($filepath),"File not found");
      $this->files[] = new UploadedFile($filepath,$filename);
    }

    /**
     * @Given /^I have a valid Catrobat file$/
     */
    public function iHaveAValidCatrobatFile()
    {
        $filepath = self::FIXTUREDIR . "compass.catrobat";
        assertTrue(file_exists($filepath),"File not found");
        $this->files[] = new UploadedFile($filepath,"compass.catrobat");
    }

    /**
     * @Given /^I have a Catrobat file with an invalid code\.xml$/
     */
    public function iHaveACatrobatFileWithAnInvalidCodeXml()
    {
        $filepath = self::FIXTUREDIR . "GeneratedFixtures/program_with_invalid_code_xml.catrobat";
        assertTrue(file_exists($filepath),"File not found");
        $this->files[] = new UploadedFile($filepath,"program_with_invalid_code_xml.catrobat");
    }
    
    /**
     * @Given /^I have a Catrobat file with an missing code\.xml$/
     */
    public function iHaveACatrobatFileWithAnMissingCodeXml()
    {
        $filepath = self::FIXTUREDIR . "GeneratedFixtures/program_with_missing_code_xml.catrobat";
        assertTrue(file_exists($filepath),"File not found");
        $this->files[] = new UploadedFile($filepath,"program_with_missing_code_xml.catrobat");
    }
    
    /**
     * @Given /^I have a Catrobat file with a missing image$/
     */
    public function iHaveACatrobatFileWithAMissingImage()
    {
        $filepath = self::FIXTUREDIR . "GeneratedFixtures/program_with_missing_image.catrobat";
        assertTrue(file_exists($filepath),"File not found");
        $this->files[] = new UploadedFile($filepath,"program_with_missing_image.catrobat");
    }
    
    /**
     * @Given /^I have a Catrobat file with an additional image$/
     */
    public function iHaveACatrobatFileWithAnAdditionalImage()
    {
        $filepath = self::FIXTUREDIR . "GeneratedFixtures/program_with_extra_image.catrobat";
        assertTrue(file_exists($filepath),"File not found");
        $this->files[] = new UploadedFile($filepath,"program_with_extra_image.catrobat");
    }
    
    /**
     * @Given /^I have an invalid Catrobat file$/
     */
    public function iHaveAnInvalidCatrobatFile()
    {
        $filepath = self::FIXTUREDIR . "invalid_archive.catrobat";
        assertTrue(file_exists($filepath),"File not found");
        $this->files[] = new UploadedFile($filepath,"invalid_archive.catrobat");
    }
    
    
    /**
     * @Given /^I have a parameter "([^"]*)" with the md5checksum my file$/
     */
    public function iHaveAParameterWithTheMdchecksumMyFile($parameter)
    {
      $this->request_parameters[$parameter] = md5_file($this->files[0]->getPathname()); 
    }
    
    /**
     * @Given /^I have a parameter "([^"]*)" with an invalid md5checksum of my file$/
     */
    public function iHaveAParameterWithAnInvalidMdchecksumOfMyFile($parameter)
    {
      $this->request_parameters[$parameter] = "INVALIDCHECKSUM"; 
    }
    
    
    /**
     * @Given /^I have a parameter "([^"]*)" with the md5checksum of "([^"]*)"$/
     */
    public function iHaveAParameterWithTheMdchecksumOf($parameter, $file)
    {
      $this->request_parameters[$parameter] = md5_file($this->files[0]->getPathname()); 
    }
    
   private function emptyDirectory($directory)
   {
     $filesystem = new Filesystem();
     
     $finder = new Finder();
     $finder->in($directory)->depth(0);
     foreach ($finder as $file)
     {
       $filesystem->remove($file);
     }
   }
}

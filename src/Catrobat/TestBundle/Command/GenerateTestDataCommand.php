<?php

namespace Catrobat\TestBundle\Command;

use Catrobat\CoreBundle\Services\CatrobatFileCompressor;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Catrobat\CoreBundle\Services\CatrobatFileExtractor;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Finder\Finder;

class GenerateTestDataCommand extends Command
{
  protected $filesystem;
  protected $source;
  protected $target_directory;
  protected $extractor;
  protected $extracted_source_program_directory;
  protected $compressor;

  public function __construct(Filesystem $filesystem, CatrobatFileExtractor $extractor, CatrobatFileCompressor $compressor, $source, $target_directory)
  {
    parent::__construct();
    $this->filesystem = $filesystem;
    $this->source = $source;
    $this->target_directory = realpath($target_directory)."/";
    $this->extractor = $extractor;
    $this->compressor = $compressor;
  }

  protected function configure()
  {
    $this->setName('catrobat:test:generate')->setDescription('Generates test data');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $dialog = $this->getHelperSet()->get('dialog');
    
    if ($dialog->askConfirmation($output, '<question>Generate test data in ' . $this->target_directory . ' (Y/n)?</question>', true))
    {
      $output->writeln("<info>Deleting old test data in " . $this->target_directory . "</info>");
      
      $finder = new Finder();
      $finder->in($this->target_directory)->ignoreDotFiles(true)->depth(0);
      foreach ($finder as $file)
      {
        $this->filesystem->remove($file);
      }
      
      $output->writeln("<info>Generating new test data</info>");
      $this->extractBaseTestProgram("base");      
      $this->generateProgramWithExtraImage("program_with_extra_image");      
      $this->generateProgramWithMissingImage("program_with_missing_image");     
      $this->generateProgramWithTooManyFiles("program_with_too_many_files");
      $this->generateProgramWithTooManyFolders("program_with_too_many_folders"); 
      $this->generateProgramWithMissingCodeXML("program_with_missing_code_xml");
      $this->generateProgramWithInvalidCodeXML("program_with_invalid_code_xml");
      $this->generateProgramWithManualScreenshot("program_with_manual_screenshot");
      $this->generateProgramWithScreenshot("program_with_screenshot");
     // $this->generateProgramWithInvalidContentCodeXML("program_with_invalid_content_code_xml");
      $this->generateProgramWithRudeWordInDescription("program_with_rudeword_in_description");
      $this->generateProgramWithRudeWordInName("program_with_rudeword_in_name");

      $finder->directories()->in($this->target_directory)->depth(0);
      foreach ($finder as $dir)
      {
        $this->compressDirectory($dir->getRelativePathname());
      }
    }
  }
  
  protected function compressDirectory($directory)
  {
    $this->compressor->compress($directory);
  }

  protected function extractBaseTestProgram($directory)
  {
    
    $extracted = $this->extractor->extract(new File($this->source));
    $extracted_path = $extracted->getPath();
    $this->extracted_source_program_directory = $this->target_directory.$directory;
    $this->filesystem->rename($extracted_path, $this->extracted_source_program_directory,true);
  }
  
  protected function generateProgramWithExtraImage($directory)
  {
    $this->filesystem->mirror($this->extracted_source_program_directory, $this->target_directory.$directory);
    $this->filesystem->copy($this->target_directory.$directory."/images/6153c44ce0f49f21facbb8c2b2263ce8_Aussehen.png", $this->target_directory.$directory."/images/6153c44ce0f49f21facbb8c2b2263ce8_extra.png");
  }
  
  protected function generateProgramWithMissingImage($directory)
  {
    $this->filesystem->mirror($this->extracted_source_program_directory, $this->target_directory.$directory);
    $this->filesystem->remove($this->target_directory.$directory."/images/6153c44ce0f49f21facbb8c2b2263ce8_Aussehen.png");
  }
  
  protected function generateProgramWithTooManyFiles($directory)
  {
    $this->filesystem->mirror($this->extracted_source_program_directory, $this->target_directory.$directory);
    $this->filesystem->copy($this->target_directory.$directory."/code.xml", $this->target_directory.$directory."/extraFile.xml");
  }
  
  protected function generateProgramWithTooManyFolders($directory)
  {
    $this->filesystem->mirror($this->extracted_source_program_directory, $this->target_directory.$directory);
    $this->filesystem->mirror($this->target_directory.$directory."/sounds", $this->target_directory.$directory."/extraFolder");
  }
  
  protected function generateProgramWithMissingCodeXML($directory)
  {
    $this->filesystem->mirror($this->extracted_source_program_directory, $this->target_directory.$directory);
    $this->filesystem->remove($this->target_directory.$directory."/code.xml");
  }
  
  protected function generateProgramWithInvalidCodeXML($directory)
  {
    $this->filesystem->mirror($this->extracted_source_program_directory, $this->target_directory.$directory);
    $this->filesystem->remove($this->target_directory.$directory."/code.xml");
    $this->filesystem->copy($this->target_directory.$directory."/automatic_screenshot.png", $this->target_directory.$directory."/code.xml"); 
  }
  
  protected function generateProgramWithManualScreenshot($directory)
  {
    $this->filesystem->mirror($this->extracted_source_program_directory, $this->target_directory.$directory);
    $this->filesystem->rename($this->target_directory.$directory."/automatic_screenshot.png", $this->target_directory.$directory."/manual_screenshot.png");  
  }
  
  protected function generateProgramWithScreenshot($directory)
  {
    $this->filesystem->mirror($this->extracted_source_program_directory, $this->target_directory.$directory);
    $this->filesystem->rename($this->target_directory.$directory."/automatic_screenshot.png", $this->target_directory.$directory."/screenshot.png");    
  }

  protected function generateProgramWithRudeWordInDescription($directory)
  {
    $this->filesystem->mirror($this->extracted_source_program_directory, $this->target_directory.$directory);
    $properties = @simplexml_load_file($this->target_directory.$directory."/code.xml");
    $properties->header->description = "FUCK YOU";
    $properties->asXML($this->target_directory.$directory."/code.xml");
  }

  protected function generateProgramWithRudeWordInName($directory)
  {
    $this->filesystem->mirror($this->extracted_source_program_directory, $this->target_directory.$directory);
    $properties = @simplexml_load_file($this->target_directory.$directory."/code.xml");
    $properties->header->programName = "FUCK YOU";
    $properties->asXML($this->target_directory.$directory."/code.xml");
  }

}

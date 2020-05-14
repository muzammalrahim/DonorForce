<?php

use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($dir = __DIR__ . '/src');
$versions = GitVersionCollection::create($dir)
    ->addFromTags(function ($version) {
        return preg_match('~^\d+\.\d+\.\d+$~', $version);
    })
    ->add('master');

return new Sami($iterator, [
    'title' => 'PhpSpreadsheet',
    'versions' => $versions,
    'build_dir' => __DIR__ . '/build/%version%',
    'cache_dir' => __DIR__ . '/cache/%version%',
    'remote_repository' => new GitHubRemoteRepository('PHPOffice/PhpSpreadsheet', dirname($dir)),
]);
            /** Include path **/
            //echo "<br /> get_include_path = ".get_include_path(). '../../../Classes/';  
            //set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');
            /** PHPExcel_IOFactory */
            //include dirname(__FILE__) . '/../assets/Classes/PHPExcel/IOFactory.php';
            //require_once JPATH_LIBRARIES . '/phpexcel/library/PHPExcel/IOFactory.php';

            

            //$inputFileName = './sampleData/example1.xls';
            $inputFileName = $dest;//realpath(dirname(__FILE__).'/../assets/Excel/Donations_Export_3.xls');             
            echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';

            /**  Identify the type of $inputFileName  **/
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            /**  Create a new Reader of the type that has been identified  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $objPHPExcel = $reader->load($inputFileName);
            /**  Convert Spreadsheet Object to an Array for ease of use  **/
            $sheetData = $objPHPExcel->getActiveSheet()->toArray();

            $Exp_data = array();
            
            foreach($sheetData as $sdata){
                 $empty = '';  
                foreach($sdata as $d){
                    if($d == ''){
                        $Exp_data[] = $sdata; 
                    }                
                }
            }
            //Remove the header     
            //unset($Exp_data[0]);          
            echo "<pre> Exp_data = "; print_r( $Exp_data  ); echo "</pre>";exit();  
            $this->insert_xls($Exp_data);
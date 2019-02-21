<?php
class Image {
  private $_name,
          $_tmpName,
          $_imgSize,
          $_imgExt,
          $_imageHeight,
          $_imageWidth,
          $_localPath = '/image/',
          $_err = array();
    
    public function getFile($name = '',$rule = array()) {
      //check if file contain error
      if ($_FILES[$name]['error'] !== 1) {
        $this->_name = $_FILES[$name]['name'];
        $this->_imgSize = $_FILES[$name]['size'];
        $this->_tmpName = $_FILES[$name]['tmp_name'];
        $this->_imgExt = strtolower(pathinfo($this->_name,PATHINFO_EXTENSION));
        list($this->_imageWidth, $this->_imageHeight)  = getimagesize(($this->_tmpName));
        $this->validate($rule); //calls the validate
      }
      else {
        //returns errors if file contain error
        $this->addErr('server has encountered an internal error');
      }
    }
    public function addErr($errors = ''){
      $this->_err[] = $errors;
    }
    public function Mb2byte($int = 0) {
      return (1000000 * $int);
    }
    public function getPassed() {
      return (count($this->_err) > 0)? false : true;
    }
    public function printErr(){
      print_r($this->_err);
    }
    //addToDatabase receive 1 argument, where the path will
    //receive the path of the image
    public function addToPath($path = array()) {
      $file = '';
        //path of the image
        $file = dirname(__FILE__, 2).'/'. 'image/' . $this->pathGen($path);
        if (!file_exists($file)) {
            mkdir($file);
        }
        $file = $file . $this->_name;
        file_put_contents($file, file_get_contents($this->_tmpName));
        echo "file added";
        return $file; //return the path of the image
    }
    public function pathGen($dir = array()){
      $output = '';
      foreach ($dir as $key) {
        $output .= $key . '/';
      }
      return $output;
    }

    public function validate($rule = array()) {
      foreach ($rule as $key => $value) {
        switch ($key) {
          case 'minSize':
              ($this->_imgSize < $value)? $this->addErr(
                 "Image size should not be smaller than $value bytes"
                 ) : true;
            break;
          case 'maxSize':
              ($this->_imgSize > $value)? $this->addErr(
                "Image size should not be larger than " . ($value/1000000) . " Megabytes"
                ) : true;
            break;
          case 'maxWidth':
              ($this->_imageWidth > $value)? $this->addErr(
                "Image width should not be larger than $value pixels"
                ) : true;
            break;
          case 'maxHeight':
              ($this->_imageHeight > $value)? $this->addErr(
                "Image height should not be larger than $value pixels"
                ) : true;
            break;
          case 'minWidth':
              ($this->_imageWidth < $value)? $this->addErr(
                "Image width should not be smaller than $value pixels"
                ) : true;
            break;
          case 'minHeight':
              ($this->_imageHeight < $value)? $this->addErr(
                "Image height should not be smaller than $value pixels"
                ) : true;
            break;
          case 'type':
              $output = in_array($this->_imgExt,$value);
              ($output !== true)? $this->addErr(
                'Image extension should only be ' . implode(',' ,$value)
                ) : true;
          break;
        }
      }
    }

  }
    // if ($_SERVER['REQUEST_METHOD']=='POST') {
    //   $n = new Image();
    //   $n->getFile('image',array(
    //     'minSize' => 20,
    //     'maxSize' => $n->Mb2byte(4),
    //     'minWidth' => 20,
    //     'minHeight' => 20,
    //     'maxHeight' => 5000,
    //     'maxWidth' => 5000,
    //     'type' => array(
    //       'png',
    //       'jpeg',
    //       'jpg',
    //       'svg',
    //     ),
    //   ));
    //   //get file receive 2 argument where first one is the name of the image and
    //   //another one is the rules of the image, such as the width, height, size and type
    //   if ($n->getPassed()) { //check if the image has passed all the requirement
    //     echo "Valid<br>";
    //     echo $n->addToPath(array('Samuel Ling')); 
    //     //first argument is the hirache of the image 
    //     //when the first argument is true it should return the path of the image on addToPath function
    //     //the path will need to be stored into the database
    //   }
    //   else {
    //     echo "Invalid";
    //     $n->printErr();
    //   }
    // }
  
// <form class="" action="" method="post"  enctype="multipart/form-data">
//   <input type="file" name="image" value="">
//   <input type="submit" name="" value="">
// </form>

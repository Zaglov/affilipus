<?php
namespace imbaa\Affilipus\Core\Autoload;

class Psr_4 {


	private $namespace_folder = array();



	public function add( $namespace, $directory ) {

		if ( ! $namespace || ! $directory ) {
			return;
		}

		$namespace = trim( $namespace, '\\' ) . '\\';
		$directory = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, $directory );
		$directory = rtrim( $directory, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

		$this->namespace_folder[ $namespace ] = $directory;
	}


	public function autoload( $class_name ) {
		
		if ( ! $this->namespace_folder ) {
			return;
		}

		foreach ( $this->namespace_folder as $prefix => $dir ) {



			if ( $class_name === strstr( $class_name, $prefix ) ) {
				$class_path = str_replace( array( $prefix, '\\' ), array( '', DIRECTORY_SEPARATOR ) , $class_name ) . '.php';

				if(!file_exists($dir . $class_path)){

					echo 'Could not load '.$dir . $class_path.' because File does not exist.';

				}

				require $dir . $class_path;
			}
		}

	}
}


<?php

class UploadManager{

	function __construct(){

		require_once 'include/global_context.php';
		require_once 'include/constants.php';
	}

	function uploadFile($file){

		if(isset($file)){

			//File Information
			$file_name = $file['name'];
			$file_path = $file['tmp_name'];
			$file_size = $file['size'];
			$file_error = $file['error'];

			$file_name_array = explode('.', $file_name);
			$file_extension = strtolower(end($file_name_array));

			//Only *.PEM files are allowed
			$allowedExtension = array(FILE_TYPE);

			if(in_array($file_extension, $allowedExtension)){

				if($file_error == null){

					if($file_size < (int)UPLOAD_FILE_SIZE){

						//Everything is file, Upload to Destination
						$file_new_name = uniqid('',true).'.'.$file_extension;

						$file_destination = UPLOAD_DIRECTORY_NAME.'/'.$file_new_name;

						if(move_uploaded_file($file_path, $file_destination)){

							//echo "Successfully Uploaded  ".$file_destination. " :)";

							GlobalContext::$hasCertificateUploaded = true;
							
							return $file_destination;
						}
						else{

							GlobalContext::$hasCertificateUploaded = false;
							return ERROR_INCORRECT_PATH;
						}
					}
					else{

						GlobalContext::$hasCertificateUploaded = false;
						return ERROR_INVALID_SIZE; //Size is greather than Limit.
					}
				}
				else{

					GlobalContext::$hasCertificateUploaded = false;
					return "$file_error";
				}
			}
			else{

				GlobalContext::$hasCertificateUploaded = false;
				if($file_extension != null){

					return ERROR_INVALID_FILE;
				}
				else{
					return ERROR_NO_CERTIFICATE;
				}
			}
		}
	}
}

?>
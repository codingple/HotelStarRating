<?php
		$file_name = 'paris_input.txt';
		$file_path = './'.$file_name;

		$file_r = fopen($file_path, 'r') or die("File does not exist or you lack permission to open it");

		$i = 0; // Number of Hotel
		$data;
		
			
			while(!feof($file_r)){
				$text_tmp = fgets($file_r);

				// Hotel name (0)
				if ( strpos($text_tmp, "class=\"hc_i_hotel\"") != false ){
					preg_match('@>.*(?=</a>)@', $text_tmp, $data_tmp);
					$result = str_replace(">", "", $data_tmp[0]);
					$data[$i][0] = $result;
				}

				// Rating (4)
				else if ( strpos($text_tmp, "<dd class=\"hc_i_rating") != false){
					preg_match('@\(.*\)@', $text_tmp, $data_tmp);
					$result = str_replace("(", "", $data_tmp[0]);
					$result = str_replace(")", "", $result);
					$data[$i][4] = $result;
				}

				// Address (1)
				else if ( strpos($text_tmp, "<dd class=\"hc_i_addr\">") != false ){
					preg_match('@>.*(?=</dd>)@',$text_tmp, $data_tmp);
					$result = str_replace(">", "", $data_tmp[0]);
					if ( strpos($result, ".") != false ){
								$tmp = explode(".", $result);
								$result = $tmp[1];
							}
					$data[$i][1] = $result;
				}

				// userRating (3)
				else if ( strpos($text_tmp, "<dd class=\"hc_i_usrRating\"") != false ){
					while ( 1 ){

						$text_tmp = fgets($file_r);

						// existing
						if ( preg_match('@>.*(?=</span>)@', $text_tmp, $data_tmp)){
							$result = str_replace(">", "", $data_tmp[0]);
							if ( strpos($result, ",") != false ){
								$tmp = explode(", ", $result);
								$result = $tmp[1];
							}

							$data[$i][3] = $result;
							break;
						}

						// none
						else if ( strpos($text_tmp, "&nbsp") != false ){
							$data[$i][3] = 5.0;
							break;
						}
					}
				}

				// Price (2)
				else if ( strpos($text_tmp, "<dd class=\"hc_i_price\"") != false ){
					while (1){

						$text_tmp = fgets($file_r);

						// existing
						if ( preg_match('@</span> .*(?=<span class=\"hc_pr_cur)@', $text_tmp, $data_tmp)){
							$result = str_replace("</span> ", "", $data_tmp[0]);
							$data[$i][2] = $result;

							// i++
							for ( $j=0; $j<5; $j++){
								if ( !array_key_exists($j, $data[$i]) ){
									$data[$i][$j] = 'x';
								}
							}
							$i++;
							break;
						}
					}
				}

			}// end of while


			// print
			for($k=0; $k<$i; $k++){
				for($l=0; $l<5; $l++){
					echo $data[$k][$l];
					echo "\t";
				}
				echo "<br/>";
			}
				

?>
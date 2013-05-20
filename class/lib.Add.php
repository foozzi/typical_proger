    <?PHP
     
    Class Upload extends SimpleImage
    {
     
        private 

            $text = null,

            $filename = null,

            $filesize = null,

            $filetmp = null,

            $link = null,

            $thumb_link = null,

            $err = array(),

            $user_info = array(),

            $min_word = 10,
     
            $valid_extensions = array('gif', 'jpg', 'png', 'GIF', 'JPG', 'PNG');
     
        public function __construct($text, $name, $size, $tmp)
        {

            $this->user_info = Profile::info_user();

            /**
             * Check permission folder upload
             */

            if(!is_writable(UPLOAD_DIR))
            {
                $this->err[] = display_msg('Ошибка', 'alert-error', 'Директория загрузки не доступка для записи, свяжитесь с администрацией');
            }

            $this->Error_Count1();
     
            /**
             * file data array
             */
     
            $this->text = $text;
            $this->filename = $name;
            $this->filesize = $size;
            $this->filetmp = $tmp;
     
            $this->valid_extensions = $valid_extensions;
     
            /**
             * Clean text preg's
             */
     
            //$this->text = preg_replace("/[^\\w\\x7F-\\xFF\\s]+/s", "", $this->text);

            /**
             * clean xss/ sql inj  (func.php)
             */

            $this->text = xss(trim($this->text));
     
            if($this->filesize == 0)
            {
                $this->Add_Quote();
                exit;
            }
     
            /**
             * get ext file
             */
     
            $this->ex = pathinfo($this->filename, PATHINFO_EXTENSION);
     
            /**
             * unick name for image
             */
     
            $this->new_img_name = uniqid() . '.' . $this->ex;
     
            if (isset($valid_extensions))
            {
                $allowed = 0;
                foreach ($valid_extensions as $ext)
                {
                    if(substr($this->filename, (0 - (strlen($ext) + 1))) == ".".$ext)
                    $allowed = 1;
                }
                if ($allowed == 0)
                {
                    $this->err[] = display_msg('Ошибка', 'alert-error', 'Неверный формат изображения');
                }
            }
     
            /**
             * path for upload and write table
            */
     
            $this->link = UPLOAD_DIR . $this->new_img_name;
            $this->link_thumb = UPLOAD_DIR_THUMB . $this->new_img_name;
     
            if($this->Check_Image($this->filetmp, $valid_extensions))
            {
                $this->err[] = 'Это не картинка';
            }
     
            /**
             * default value for text
             */
     
            if(empty($this->text))
            {
                $this->text = '<span class="label label-success"> Подписи нет </span>';
            }
     
            /**
             * Echo error msg
             */
     
            $this->Error_Count1();
     
            /**
             * start upload and insert data
             */

            $this->Uploading();
     
        }
     
        /**
         * anti-upload for other files
         */
     
        private function Check_Image()
        {
            if(!$image_info = get_image_info($this->filetmp) or !in_array($image_info['extension'], $this->valid_extensions))
            {
                return false;
            }
        }
     
        /**
         * function uploading file and insert data to table
         */
     
        private function Uploading()
        {
     
            /**
             * start upload
             */
     
            if(move_uploaded_file($this->filetmp, $this->link))
            {
                /**
                 * get size images
                 */
     
                list($width, $height) = getimagesize($this->link);
     
                /**
                 * if width > thumbnail size
                 */

     
                if($width > MAX_FILE_SIZE)
                {
                    $this->load($this->link);
                    $this->resizeToHeight(MAX_FILE_SIZE);
                    $this->save($this->link_thumb);
                }
                else
                {
                    $this->link_thumb = $this->link;
                }
     
                /**
                 * if image very small
                 */
     
                if($width < MIN_SIZE_FILE || $height < MIN_SIZE_FILE)
                {
                    $this->err[] = display_msg('Ошибка', 'alert-error', 'Пикча маловата');
                }
     
                /**
                * check errors and msg
                */
     
                $this->Error_Count1();
     
                /**
                 * insert data to table
                 */
 
                db::set("INSERT INTO post (
                   id_post,
                   author,
                   text_post,
                   img_large,
                   img_mini,
                   date_post
                   ) VALUES (
                   '%s',
                   '%s',
                   '%s',
                   '%s',
                   '%s',
                   NOW()
                   )",
                    NULL, $this->user_info['name_user'], $this->text, $this->link, $this->link_thumb
                );
     
                /**
                 * Good upload
                 */
     
                $this->err[] = display_msg('Сообщение', 'alert-info', 'Сообщение добавлено на проверку');
            }
            else
            {
                /**
                 * truble upload
                 */
     
                $this->err[] = display_msg('Ошибка', 'alert-error', 'При загрузке возникли какие-то неполадки');
            }
     
            /**
             * echo msg or error
             */
     
                $this->Error_Count1();

     
        }

        /**
         * Add only quote
         */

        private function Add_Quote()
        {

            /**
             * Check empty area 
             */

            if(empty($this->text))
            {
                $this->err[] = display_msg('Ошибка', 'alert-error', 'Все поля пусты');
               
            }

            /**
             * Check minimal word 
             */

            else
            {
                $num_symb = iconv_strlen($this->text,'UTF-8');
                if($num_symb < $this->min_word)
                {
                    $this->err[] = display_msg('Ошибка', 'alert-error', 'Цитата должна быть не менее 10 символов');
                }              
            }

            $this->Error_Count1();

            /**
             * Query add
             */

            $Add_Text = db::set("INSERT INTO post (
                id_post,
                author,
                text_post,
                date_post
                ) VALUES (
                '%s',
                '%s',
                '%s',
                NOW()
                )",
                 NULL, $this->user_info['name_user'], $this->text
            );

            /**
             * if query failed
             */

            if(!$Add_Text)
            {
                $this->err[] = display_msg('Ошибка', 'alert-error', 'Возникли ошибки при добавлении цитаты');
            }

            /**
             * Gooooood ;)
             */

            $this->err[] = display_msg('Сообщение', 'alert-info', 'Цитата успешно добавлена');

            $this->Error_Count1();
        }
     
        /**
         * function count and return errors and msg
         */
     
        private function Error_Count1()
        {
            if(count($this->err) != 0)
            {
                foreach($this->err as $display_error)
                {
                    $this->error_dis = $display_error;
                    echo $this->error_dis;
                    exit;
                }
            }
        }
     
    }


?>
<div class="container">
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/gallery.css" />
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>
        <h1>Gallery</h1>
        <div class="thumbnail-wrapper">
        <?php 
            $paths = GalleryModel::getImages(Session::get("user_id"));
            $directory = "..\\gallery\\" . Session::get("user_id") . "\\";
            foreach($paths as $path){
                $data = file_get_contents($directory . $path->path);
                $type = pathinfo($directory . $path->path, PATHINFO_EXTENSION);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $delete = Config::get('URL') . "gallery/delete";
                $share = Config::get('URL') . "gallery/share";
                $public_link = Config::get('URL') . $path->public_link;
                echo('
                <div class="image-container">
                    <div class="thumbnail" onclick="openModal(this)">
                        <img src="' . $base64 . '" alt="Image description" data-full-image="'. $base64 . '">
                    </div>
                    <div class="button-container">
                        <form action="' . $delete . '" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="image" value="'. $path->path .'"/>
                            <input type="submit" name="delete" class="button" value="Löschen" />
                        </form>
                        <form action="' . $share . '" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="image" value="'. $path->path .'"/>
                            <input type="submit" name="share" class="button" value="Freigeben" />
                        </form>
                        <input type="hidden" name="share_url" value="' . $public_link . '"/>
                    </div>
                </div>
                ');
            }
        ?>
        </div>
        <div id="myModal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="modal-content" id="fullImage">
        </div>
        <script>
            function openModal(element) {
                var fullImageSrc = element.querySelector('img').getAttribute('data-full-image');
                document.getElementById('fullImage').src = fullImageSrc;
                document.getElementById('myModal').style.display = "block";
            }

            function closeModal() {
                document.getElementById('myModal').style.display = "none";
            }

            function addCopyLinkButtons(){
                var shareUrls = document.getElementsByName('share_url');
                for (var i = 0; i < shareUrls.length; i++){
                    var shareUrl = shareUrls[i];
                    console.log(shareUrl.value);
                    if (shareUrl.value !== 'http://localhost/huge/'){
                        var copyButton = document.createElement('button');
                        copyButton.innerHTML = 'Copy Link';
                        copyButton.className = 'button';

                        var url = shareUrl.value;
                        copyButton.onclick = function() {
                            navigator.clipboard.writeText(url).then(function() {
                            console.log('Link copied to clipboard!');
                        }).catch(function(error) {
                            console.error('Error copying text: ', error);
                        });
                        };

                        shareUrl.parentNode.insertBefore(copyButton, shareUrl.nextSibling);
                    }
                }
            }

            window.onload = addCopyLinkButtons;
        </script>
        <form action="<?php echo Config::get('URL'); ?>gallery/image_upload" method="post" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>
    </div>
</div>

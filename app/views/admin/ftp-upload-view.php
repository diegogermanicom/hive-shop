<!DOCTYPE html>
<html lang="es">
    <head>
        <?php include VIEWS_ADMIN.'/partials/head.php'; ?>
        <?php
            if(ENVIRONMENT == 'DEV') {
                echo '<script src="'.PUBLIC_PATH.'/js/ftp-upload.js?'.uniqid().'"></script>';
            } else {
                echo '<script src="'.PUBLIC_PATH.'/js/min/ftp-upload.min.js?'.uniqid().'"></script>';
            }
        ?>
    </head>
    <body id="<?= $data['admin']['name_page']; ?>">
        <div class="app">
            <?php include VIEWS_ADMIN.'/partials/header.php'; ?>
            <div id="popup-upload-ftp" class="popup">
                <div class="content">
                    <input type="hidden" value="" id="popup-upload-ftp-id-file">
                    <div class="title"><b>UPLOAD FILE TO FTP</b></div>
                    <div class="info">
                        <span class="label">File path:</span>
                        <span class="value" id="popup-upload-ftp-folder"></span>
                    </div>
                    <div class="info">
                        <span class="label">File name:</span>
                        <span class="value" id="popup-upload-ftp-name"></span>
                    </div>
                    <div class="info">
                        <span class="label">File size:</span>
                        <span class="value" id="popup-upload-ftp-size"></span>
                    </div>
                    <div class="info">
                        <span class="label">Size in ftp:</span>
                        <span class="value" id="popup-upload-ftp-ftp-size"></span>
                    </div>
                    <div class="botonera">
                        <div class="btn btn-black" id="btn-upload-ftp">UPLOAD FILE</div>
                        <div class="btn btn-black" id="btn-upload-ftp-comparar">COMPARE</div>
                        <div class="btn btn-black" id="btn-upload-ftp-close">CANCEL</div>
                    </div>                
                </div>
            </div>
            <div id="popup-upload-ftp-create" class="popup">
                <div class="content">
                    <input type="hidden" value="" id="popup-upload-ftp-create-id-folder">
                    <div class="title"><b>CREATE DIRECTORY</b></div>
                    <div class="info">
                        <span class="label">Directory path:</span>
                        <span class="value" id="popup-upload-ftp-create-folder"></span>
                    </div>
                    <div class="info">
                        <span class="label">Directory name:</span>
                        <span class="value" id="popup-upload-ftp-create-name"></span>
                    </div>
                    <div class="botonera">
                        <div class="btn btn-black" id="btn-upload-ftp-create">CREATE DIRECTORY</div>
                        <div class="btn btn-black" id="btn-upload-ftp-create-close">CANCEL</div>
                    </div>                
                </div>
            </div>
            <div id="popup-upload-ftp-comparar" class="popup">
                <div class="content">
                    <div class="title"><b>COMPARE FILES</b></div>
                    <div class="table-content">
                        <div class="textarea-content">
                            <textarea id="popup-upload-ftp-comparar-code-server" spellcheck="false"></textarea>                    
                        </div>
                        <div class="textarea-content">
                            <textarea id="popup-upload-ftp-comparar-code-ftp" spellcheck="false"></textarea>                    
                        </div>                    
                    </div>
                    <div class="botonera">
                        <div class="btn btn-black" id="btn-upload-ftp-comparar-close">CANCEL</div>
                    </div>                
                </div>
            </div>
            <?php include VIEWS_ADMIN.'/partials/menu-left.php'; ?>
            <div id="container-admin">
                <section>
                    <div class="container-md">
                        <div class="title-container text-center pb-20">FTP UPLOAD</div>
                        <div class="upload-ftp-dir-content">Loading...</div>
                        <div style="text-align: center; margin-top: 20px;">
                            <div class="btn btn-black" id="btn-upload-all">UPLOAD ALL</div>
                            <div class="btn btn-black" id="btn-refrescar">REFRESH</div>
                        </div>
                    </div>
                </section>
                <?php include VIEWS_ADMIN.'/partials/footer.php'; ?>
            </div>
        </div>
    </body>
</html>
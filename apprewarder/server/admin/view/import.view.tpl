    <div class='content'>
        <h1>{$page_data.page_title}</h1>
        <div class="box form-group">
            <div class='alert alert-info'>
                Please select a .CSV formatted file to upload
            </div>

            <form class="form-horizontal" role="form" enctype="multipart/form-data" action="/{$page_data.page_name}/upload" method="POST">

                <div class="form-group">
                    <label for="file_upload" class="col-sm-2 control-label">File to upload:</label>
                    <div class="col-sm-10">
                        <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                        <input name="file_upload" type="file" id="file_upload" />
                        <input type="hidden" name="function" value="upload"/>
                    </div>
                </div>

                <input type="submit" class="btn btn-lg btn-success" value="Upload File" />

            </form>
        </div>
    </div>


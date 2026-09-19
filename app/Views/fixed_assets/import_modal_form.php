<?php echo form_open_multipart(get_uri("fixed_assets/upload_excel"), array("id" => "import-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="alert alert-info p-3 mb-3">
        <h5 class="fw-bold mb-1"><i data-feather="file-text" class="icon-16"></i> Bulk Import Excel / CSV Instructions</h5>
        <p class="small mb-2">Upload a CSV or Excel file containing fixed asset records. Ensure your file includes the standard column headers matching the baseline asset register template.</p>
        <a href="<?php echo get_uri('fixed_assets/download_template'); ?>" class="btn btn-sm btn-outline-primary"><i data-feather="download" class="icon-14"></i> Download Standard Import CSV Template</a>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="file" class="col-md-3">Select CSV File</label>
            <div class="col-md-9">
                <input type="file" name="file" id="file" class="form-control" accept=".csv, .xlsx, .xls" required />
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="upload" class="icon-16"></span> Process Bulk Upload</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#import-form").appForm({
            onSuccess: function (result) {
                appNotice.success(result.message);
                window.location.reload();
            }
        });
    });
</script>

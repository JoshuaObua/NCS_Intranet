<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="mail" class="icon-16 mr5"></i> <?php echo htmlspecialchars($memo->subject); ?></h4>
            <div class="title-button-group">
                <?php echo anchor(get_uri("hr_memos/inbox"), "<i data-feather='arrow-left' class='icon-16'></i> " . app_lang("back"), array("class" => "btn btn-default")); ?>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <!-- Memo meta -->
                    <table class="table table-bordered mb20">
                        <tr>
                            <th width="150"><?php echo app_lang("memo_number"); ?></th>
                            <td><?php echo $memo->memo_number; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo app_lang("from"); ?></th>
                            <td><?php echo $memo->sender_name; ?> <?php if ($memo->sender_title) { ?><small class="text-muted">(<?php echo $memo->sender_title; ?>)</small><?php } ?></td>
                        </tr>
                        <tr>
                            <th><?php echo app_lang("date"); ?></th>
                            <td><?php echo format_to_datetime($memo->created_at); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo app_lang("priority"); ?></th>
                            <td>
                                <?php
                                $badges = ["low" => "success", "normal" => "info", "high" => "warning", "urgent" => "danger"];
                                $b = $badges[$memo->priority] ?? "secondary";
                                echo "<span class='badge bg-{$b}'>" . app_lang("hr_" . $memo->priority) . "</span>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo app_lang("recipients"); ?></th>
                            <td><?php echo app_lang("hr_target_" . $memo->target_type); ?>
                                <?php if ($memo->department) { ?> &mdash; <?php echo $memo->department; ?><?php } ?>
                            </td>
                        </tr>
                    </table>

                    <!-- Body -->
                    <div class="card border mb20">
                        <div class="card-body">
                            <?php echo nl2br(htmlspecialchars($memo->body)); ?>
                        </div>
                    </div>

                    <!-- Response (if recipient) -->
                    <?php if ($recipient_record) { ?>
                    <div class="card border">
                        <div class="card-header"><strong><?php echo app_lang("your_response"); ?></strong></div>
                        <div class="card-body">
                            <?php if ($recipient_record->response) { ?>
                                <p><?php echo nl2br(htmlspecialchars($recipient_record->response)); ?></p>
                                <small class="text-muted"><?php echo app_lang("responded_on"); ?>: <?php echo format_to_datetime($recipient_record->responded_at); ?></small>
                            <?php } else { ?>
                                <?php echo form_open(get_uri("hr_memos/save_response"), array("id" => "memo-response-form", "class" => "general-form")); ?>
                                <input type="hidden" name="recipient_record_id" value="<?php echo $recipient_record->id; ?>" />
                                <div class="form-group mb10">
                                    <?php echo form_textarea(array("name" => "response", "class" => "form-control", "rows" => 4, "placeholder" => app_lang("type_your_response"))); ?>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm"><i data-feather="send" class="icon-14"></i> <?php echo app_lang("submit_response"); ?></button>
                                <?php echo form_close(); ?>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Recipients list -->
                <div class="col-md-4">
                    <div class="card border">
                        <div class="card-header"><strong><?php echo app_lang("recipients"); ?> (<?php echo count($recipients); ?>)</strong></div>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recipients as $r) { ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <span>
                                        <?php echo get_avatar($r->recipient_image, "thumb-xs mr5"); ?>
                                        <?php echo $r->recipient_name; ?>
                                    </span>
                                    <?php if ($r->read_at) { ?>
                                        <span class="badge bg-success" title="<?php echo format_to_datetime($r->read_at); ?>"><i data-feather="check" class="icon-12"></i></span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary"><?php echo app_lang("unread"); ?></span>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $("#memo-response-form").appForm({
        onSuccess: function () { window.location.reload(); }
    });
});
</script>

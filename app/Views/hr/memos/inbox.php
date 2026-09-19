<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4>
                <i data-feather="mail" class="icon-16 mr5"></i> <?php echo app_lang("hr_memos"); ?>
                <?php if ($unread > 0) { ?>
                    <span class="badge bg-danger ml5"><?php echo $unread; ?></span>
                <?php } ?>
            </h4>
        </div>

        <div class="card-body">
            <?php if (empty($memos)) { ?>
                <p class="text-muted text-center p20"><?php echo app_lang("no_records_found"); ?></p>
            <?php } else { ?>
                <div class="list-group">
                    <?php foreach ($memos as $memo) { ?>
                        <?php $unread_class = $memo->read_at ? "" : "fw-bold bg-light"; ?>
                        <a href="<?php echo get_uri("hr_memos/view/" . $memo->id); ?>" class="list-group-item list-group-item-action <?php echo $unread_class; ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb1">
                                    <?php if (!$memo->read_at) { ?><span class="badge bg-primary mr5"><?php echo app_lang("new"); ?></span><?php } ?>
                                    <?php echo htmlspecialchars($memo->subject); ?>
                                    <small class="text-muted ml10"><?php echo $memo->memo_number; ?></small>
                                </h6>
                                <small class="text-muted"><?php echo format_to_relative_time($memo->created_at); ?></small>
                            </div>
                            <p class="mb1 text-muted small">
                                <i data-feather="user" class="icon-12 mr2"></i> <?php echo $memo->sender_name; ?>
                                <?php if ($memo->sender_title) { ?> &mdash; <?php echo $memo->sender_title; ?><?php } ?>
                            </p>
                            <?php if ($memo->response) { ?>
                                <small class="text-success"><i data-feather="check" class="icon-12"></i> <?php echo app_lang("responded"); ?></small>
                            <?php } ?>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

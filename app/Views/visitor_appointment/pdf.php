<div style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
    <div style="text-align: center; border-bottom: 2px solid #0056b3; padding-bottom: 15px; margin-bottom: 20px;">
        <h1 style="color: #0056b3; margin: 0; font-size: 22px;">NATIONAL COUNCIL OF SPORTS</h1>
        <h3 style="margin: 5px 0 0 0; font-size: 16px; color: #555;">VISITOR APPOINTMENT PASS</h3>
        <p style="margin: 5px 0 0 0; font-size: 12px; color: #777;">Reference ID: #<?php echo $model_info->id; ?> | Date Generated: <?php echo date("Y-m-d H:i"); ?></p>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <td style="padding: 8px; font-weight: bold; width: 30%; background-color: #f8f9fa; border: 1px solid #dee2e6;">Appointment Type:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo $model_info->appointment_type === 'internal' ? 'Internal Staff Meeting' : 'External Visitor'; ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Status:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6; font-weight: bold; color: #0056b3;"><?php echo strtoupper($model_info->status); ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Visitor Name:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo $model_info->appointment_type === 'internal' ? $model_info->created_by_user : ($model_info->first_name . " " . $model_info->last_name . ($model_info->other_name ? " " . $model_info->other_name : "")); ?></td>
        </tr>
        <?php if ($model_info->appointment_type !== 'internal') { ?>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">ID Document:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo $model_info->id_type ? strtoupper($model_info->id_type) . ": " . $model_info->id_number : "N/A"; ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Phone / Email:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo ($model_info->phone ? $model_info->phone : "-") . " / " . ($model_info->email ? $model_info->email : "-"); ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Organization:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo $model_info->organization ? $model_info->organization : "-"; ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Person To Visit:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo $model_info->to_user_name; ?> (<?php echo $model_info->to_user_email; ?>)</td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Scheduled Date & Time:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo $model_info->appointment_date . " at " . $model_info->appointment_time; ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Reason For Visit:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo nl2br($model_info->reason); ?></td>
        </tr>
        <?php if ($model_info->status_reason) { ?>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Status Remarks:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo nl2br($model_info->status_reason); ?> (By <?php echo $model_info->status_by_user ? $model_info->status_by_user : "Officer"; ?>)</td>
        </tr>
        <?php } ?>
    </table>

    <div style="margin-top: 30px; border-top: 1px dashed #aaa; padding-top: 15px; font-size: 11px; color: #666; text-align: center;">
        <p>Please present this official pass upon arrival at the Security Desk.</p>
        <p>National Council of Sports Intranet System</p>
    </div>
</div>

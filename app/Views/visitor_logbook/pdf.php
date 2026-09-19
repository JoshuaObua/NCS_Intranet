<div style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
    <div style="text-align: center; border-bottom: 2px solid #0056b3; padding-bottom: 15px; margin-bottom: 20px;">
        <h1 style="color: #0056b3; margin: 0; font-size: 22px;">NATIONAL COUNCIL OF SPORTS</h1>
        <h3 style="margin: 5px 0 0 0; font-size: 16px; color: #555;">GATE PASS / VISITOR LOGBOOK ENTRY</h3>
        <p style="margin: 5px 0 0 0; font-size: 12px; color: #777;">Entry Reference: #<?php echo $model_info->id; ?> | Issued Gate: <?php echo $model_info->gate_name; ?></p>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <td style="padding: 8px; font-weight: bold; width: 30%; background-color: #f8f9fa; border: 1px solid #dee2e6;">Visitor Name:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo $model_info->first_name . " " . $model_info->last_name . ($model_info->other_name ? " " . $model_info->other_name : ""); ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Nationality & ID:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo strtoupper($model_info->nationality) . " (" . strtoupper($model_info->id_type) . ": " . $model_info->id_number . ")"; ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Phone / Email:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo ($model_info->phone ? $model_info->phone : "-") . " / " . ($model_info->email ? $model_info->email : "-"); ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Organization:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo $model_info->organization ? $model_info->organization : "-"; ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Person To Visit:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo $model_info->to_user_name; ?> (<?php echo $model_info->to_user_email; ?>)</td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Entry Gate:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo $model_info->gate_name; ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Time In:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo format_to_datetime($model_info->time_in); ?> (Security: <?php echo $model_info->recorded_by_user ? $model_info->recorded_by_user : "Officer"; ?>)</td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Time Out:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo $model_info->time_out ? format_to_datetime($model_info->time_out) : "Checked In (On Premises)"; ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6;">Reason For Visit:</td>
            <td style="padding: 8px; border: 1px solid #dee2e6;"><?php echo nl2br($model_info->reason); ?></td>
        </tr>
    </table>

    <div style="margin-top: 30px; border-top: 1px dashed #aaa; padding-top: 15px; font-size: 11px; color: #666; text-align: center;">
        <p>Official Gate Entry Pass. Please return to Security upon departure.</p>
        <p>National Council of Sports Security System</p>
    </div>
</div>

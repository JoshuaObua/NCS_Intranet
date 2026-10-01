-- Generated from install/database.sql (MySQL) by converting the default seed rows
-- to PostgreSQL. Runs once, on first initialisation of the database volume.
-- The admin user is created separately by 03-admin.sh from environment variables.
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
BEGIN;
INSERT INTO public."ncs_company" ("id", "name", "address", "phone", "email", "website", "vat_number", "gst_number", "is_default", "logo", "deleted") VALUES ('1', 'Company Name', '', '', '', '', '', '', '1', '', '0');
INSERT INTO public."ncs_contract_templates" ("id", "title", "template", "deleted") VALUES ('1', 'Template 3.7', '<p>&nbsp;</p>
<table class="table" style="background-color: #3d3d3d; color: #ffffff; width: 100%;">
<tbody>
<tr>
<td style="text-align: center; width: 100%;">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div><span style="font-size: 40px;"><strong>{CONTRACT_TITLE}</strong></span></div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
</tr>
</tbody>
</table>
<p style="text-align: justify;">&nbsp;</p>
<p style="text-align: justify;">This contract states the terms and conditions that shall govern the contractual agreement between {COMPANY_NAME} (the Service Provider) and {CONTRACT_TO_COMPANY_NAME} (the Client) who agrees to be bound by the terms of the contract.</p>
<table style="margin-top: 0px; margin-bottom: 10px; width: 100%;">
<tbody>
<tr>
<td style="padding: 0px; width: 100%;">
<div style="margin-top: 20px;">
<div style="text-align: center;">
<div style="font-size: 30px;">{CONTRACT_ID}</div>
<table style="margin-top: 10px; width: 100%;">
<tbody>
<tr>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">
<div style="border-bottom: 5px solid #ff9800;">&nbsp;</div>
</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
</div>
</td>
</tr>
</tbody>
</table>
<div>Contract Date: {CONTRACT_DATE}<br>Expiry Date: {CONTRACT_EXPIRY_DATE}</div>
<table style="width: 100%; padding-top: 30px; margin-top: 0px;">
<tbody>
<tr>
<td style="width: 50%; padding-left: 0; padding-right: 10px;">
<p>Client</p>
{CONTRACT_TO_INFO}</td>
<td style="width: 50%; padding-left: 10px;">
<p>Service Provider</p>
{COMPANY_INFO}</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<table style="margin-top: 0px; margin-bottom: 10px; width: 100%;">
<tbody>
<tr>
<td style="padding: 0px; width: 100%;">
<div style="margin-top: 20px;">
<div style="text-align: center;">
<div style="font-size: 30px;">Service Details</div>
<table style="margin-top: 10px; width: 100%;">
<tbody>
<tr>
<td style="width: 14.4239%;">&nbsp;</td>
<td style="width: 14.4239%;">&nbsp;</td>
<td style="width: 14.4239%;">&nbsp;</td>
<td style="width: 14.4239%;">
<div style="border-bottom: 5px solid #ff9800;">&nbsp;</div>
</td>
<td style="width: 14.4239%;">&nbsp;</td>
<td style="width: 14.4239%;">&nbsp;</td>
<td style="width: 12.8504%;">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
</div>
</td>
</tr>
</tbody>
</table>
<p style="text-align: justify;">The specific scope, timeline, and any additional requirements related to the services shall be detailed in a separate document or statement of work, which shall form an integral part of this contract.</p>
<p>&nbsp;</p>
<p>{CONTRACT_ITEMS}</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table style="margin-top: 0px; margin-bottom: 10px; width: 100%;">
<tbody>
<tr>
<td style="padding: 0px; width: 100%;">
<div style="margin-top: 20px;">
<div style="text-align: center;">
<div style="font-size: 30px;">1. Service Policy</div>
<table style="margin-top: 10px; width: 100%;">
<tbody>
<tr>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">
<div style="border-bottom: 5px solid #ff9800;">&nbsp;</div>
</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
</div>
</td>
</tr>
</tbody>
</table>
<p style="text-align: justify;">The Service Policy outlines the terms and conditions governing the provision of services by Service Provider to the Client. It encompasses guidelines regarding service delivery, quality standards, support mechanisms, and dispute resolution procedures. The Service Provider is committed to upholding the highest level of professionalism, responsiveness, and customer satisfaction in delivering the agreed upon services.</p>
<p style="text-align: justify;">&nbsp;</p>
<p style="text-align: justify;">Any deviations from the Service Policy shall be communicated promptly and resolved in a timely manner to ensure seamless collaboration and adherence to the mutual objectives outlined in the contract.</p>
<p style="text-align: justify;">&nbsp;</p>
<p>&nbsp;</p>
<table style="margin-top: 0px; margin-bottom: 10px; width: 100%;">
<tbody>
<tr>
<td style="padding: 0px; width: 100%;">
<div style="margin-top: 20px;">
<div style="text-align: center;">
<div style="font-size: 30px;">2. Delivery</div>
<table style="margin-top: 10px; width: 100%;">
<tbody>
<tr>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">
<div style="border-bottom: 5px solid #ff9800;">&nbsp;</div>
</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
</div>
</td>
</tr>
</tbody>
</table>
<p style="text-align: justify;">The Service Provider will commence delivery of services upon receipt of a signed contract and any necessary initial payments as specified. Delivery timelines and milestones will be outlined in the project schedule or statement of work provided to the Client. The Service Provider will make reasonable efforts to meet agreed-upon deadlines and milestones, keeping the Client informed of any delays or changes to the delivery schedule. Delivery methods may vary depending on the nature of the services and may include in-person meetings, electronic communication, or physical shipment of goods.</p>
<p style="text-align: justify;">&nbsp;</p>
<p style="text-align: justify;">Upon completion of the services, the Client will be provided with deliverables as outlined in the project scope or statement of work, with any necessary documentation or training materials included as specified.</p>
<p style="text-align: justify;">&nbsp;</p>
<p>&nbsp;</p>
<table style="margin-top: 0px; margin-bottom: 10px; width: 100%;">
<tbody>
<tr>
<td style="padding: 0px; width: 100%;">
<div style="margin-top: 20px;">
<div style="text-align: center;">
<div style="font-size: 30px;">3. Intellectual property rights</div>
<table style="margin-top: 10px; width: 100%;">
<tbody>
<tr>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">
<div style="border-bottom: 5px solid #ff9800;">&nbsp;</div>
</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
</div>
</td>
</tr>
</tbody>
</table>
<p style="text-align: justify;">All intellectual property rights, including but not limited to copyrights, patents, trademarks, and trade secrets, associated with the services provided under this contract shall remain the exclusive property of the originating party unless otherwise agreed upon in writing. The Service Provider retains ownership of any proprietary methodologies, technologies, or materials utilized in delivering the services, and the Client agrees not to reproduce, distribute, or disclose such intellectual property without prior written consent.</p>
<p style="text-align: justify;">&nbsp;</p>
<p style="text-align: justify;">Any intellectual property created or developed during the course of providing the services shall be jointly owned by both parties unless otherwise specified in a separate agreement. Any use or exploitation of intellectual property rights beyond the scope of this contract requires the express written consent of the owning party.</p>
<p style="text-align: justify;">&nbsp;</p>
<p>&nbsp;</p>
<table style="margin-top: 0px; margin-bottom: 10px; width: 100%;">
<tbody>
<tr>
<td style="padding: 0px; width: 100%;">
<div style="margin-top: 20px;">
<div style="text-align: center;">
<div style="font-size: 30px;">4. Confidentiality</div>
<table style="margin-top: 10px; width: 100%;">
<tbody>
<tr>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">
<div style="border-bottom: 5px solid #ff9800;">&nbsp;</div>
</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
</div>
</td>
</tr>
</tbody>
</table>
<p style="text-align: justify;">Both parties agree to maintain strict confidentiality regarding any proprietary or sensitive information disclosed during the course of this contract. This includes but is not limited to trade secrets, business strategies, financial information, and client data. The Service Provider shall take all necessary precautions to prevent unauthorized access or disclosure of confidential information and shall only share such information with authorized personnel directly involved in fulfilling the obligations of this contract.</p>
<p style="text-align: justify;">&nbsp;</p>
<p style="text-align: justify;">The Client agrees not to disclose any confidential information obtained from the Service Provider to any third parties without prior written consent. This confidentiality obligation shall survive the termination of this contract and continue indefinitely thereafter.</p>
<p style="text-align: justify;">&nbsp;</p>
<p>&nbsp;</p>
<table style="margin-top: 0px; margin-bottom: 10px; width: 100%;">
<tbody>
<tr>
<td style="padding: 0px; width: 100%;">
<div style="margin-top: 20px;">
<div style="text-align: center;">
<div style="font-size: 30px;">5. Support</div>
<table style="margin-top: 10px; width: 100%;">
<tbody>
<tr>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">
<div style="border-bottom: 5px solid #ff9800;">&nbsp;</div>
</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
<td style="width: 14.5125%;">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
</div>
</td>
</tr>
</tbody>
</table>
<p style="text-align: justify;">The Service Provider agrees to provide reasonable support and assistance to the Client during the term of this contract. Support may include but is not limited to troubleshooting, technical assistance, and guidance related to the services provided. The Service Provider will make commercially reasonable efforts to respond promptly to inquiries and requests for support from the Client, within the parameters specified in the service level agreement (SLA) or support agreement. Support will be provided during normal business hours unless otherwise agreed upon. Any additional support beyond the scope outlined in this contract may be subject to additional fees or terms as mutually agreed upon by both parties.</p>
<p style="text-align: justify;">&nbsp;</p>
<p style="text-align: justify;">{CONTRACT_NOTE}</p>', '0');
INSERT INTO public."ncs_email_templates" ("id", "template_name", "email_subject", "default_message", "custom_message", "template_type", "language", "deleted") VALUES ('1', 'login_info', 'Login details', '<div style="background-color: #eeeeef; padding: 50px 0; "><div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;">  <h1>Login Details</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);">            <p style="color: rgb(85, 85, 85); font-size: 14px;"> Hello {USER_FIRST_NAME} {USER_LAST_NAME},<br><br>An account has been created for you.</p>            <p style="color: rgb(85, 85, 85); font-size: 14px;"> Please use the following info to login your dashboard:</p>            <hr>            <p style="color: rgb(85, 85, 85); font-size: 14px;">Dashboard URL:&nbsp;<a href="{DASHBOARD_URL}" target="_blank">{DASHBOARD_URL}</a></p>            <p style="color: rgb(85, 85, 85); font-size: 14px;"></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Email: {USER_LOGIN_EMAIL}</span><br></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Password:&nbsp;{USER_LOGIN_PASSWORD}</span></p>            <p style="color: rgb(85, 85, 85);"><br></p>            <p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>        </div>    </div></div>', '', 'default', '', '0'),
('2', 'reset_password', 'Reset password', '<div style="background-color: #eeeeef; padding: 50px 0; "><div style="max-width:640px; margin:0 auto; "><div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Reset Password</h1>
 </div>
 <div style="padding: 20px; background-color: rgb(255, 255, 255); color:#555;">                    <p style="font-size: 14px;"> Hello {ACCOUNT_HOLDER_NAME},<br><br>A password reset request has been created for your account.&nbsp;</p>
                    <p style="font-size: 14px;"> To initiate the password reset process, please click on the following link:</p>
                    <p style="font-size: 14px;"><a href="{RESET_PASSWORD_URL}" target="_blank">Reset Password</a></p>
                    <p style="font-size: 14px;"></p>
                    <p style=""><span style="font-size: 14px; line-height: 20px;"><br></span></p>
<p style=""><span style="font-size: 14px; line-height: 20px;">If you''ve received this mail in error, it''s likely that another user entered your email address by mistake while trying to reset a password.</span><br></p>
<p style=""><span style="font-size: 14px; line-height: 20px;">If you didn''t initiate the request, you don''t need to take any further action and can safely disregard this email.</span><br></p>
<p style="font-size: 14px;"><br></p>
<p style="font-size: 14px;">{SIGNATURE}</p>
                </div>
            </div>
        </div>', '', 'default', '', '0'),
('3', 'team_member_invitation', 'You are invited', '<div style="background-color: #eeeeef; padding: 50px 0; "><div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Account Invitation</h1>   </div>  <div style="padding: 20px; background-color: rgb(255, 255, 255);">            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello,</span><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="font-weight: bold;"><br></span></span></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="font-weight: bold;">{INVITATION_SENT_BY}</span> has sent you an invitation to join with a team.</span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{INVITATION_URL}" target="_blank">Accept this Invitation</a></span></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">If you don not want to accept this invitation, simply ignore this email.</span><br><br></p>            <p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>        </div>    </div></div>', '', 'default', '', '0'),
('4', 'send_invoice', 'New invoice', '<div style="background-color: #eeeeef; padding: 50px 0;">
<div style="max-width: 640px; margin: 0 auto;">
<div style="color: #fff; text-align: center; background-color: #33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;">
<h1>INVOICE</h1>
<h3>&nbsp;{INVOICE_FULL_ID}</h3>
</div>
<div style="padding: 20px; background-color: #ffffff; font-size: 14px;">
<p>Hello {CONTACT_FIRST_NAME},</p>
<p>Thank you for your business cooperation.</p>
<p>Your invoice for the project {PROJECT_TITLE} has been generated and is attached here.</p>
<p>&nbsp;</p>
<p><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{INVOICE_URL}" target="_blank" rel="noopener" aria-invalid="true">Show Invoice</a></p>
<p>&nbsp;</p>
<p>Invoice balance due is {BALANCE_DUE}</p>
<p>Please pay this invoice within {DUE_DATE}.&nbsp;</p>
<p>&nbsp;</p>
<p>{SIGNATURE}</p>
</div>
</div>
</div>', '', 'default', '', '0'),
('5', 'signature', 'Signature', 'Powered By: <a href="https://atenimedia.com/" target="_blank">atenimedia </a>', '', 'default', '', '0'),
('6', 'client_contact_invitation', 'You are invited', '<div style="background-color: #eeeeef; padding: 50px 0; ">    <div style="max-width:640px; margin:0 auto; ">  <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Account Invitation</h1> </div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello,</span><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="font-weight: bold;"><br></span></span></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="font-weight: bold;">{INVITATION_SENT_BY}</span> has sent you an invitation to a client portal.</span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{INVITATION_URL}" target="_blank">Accept this Invitation</a></span></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">If you don not want to accept this invitation, simply ignore this email.</span><br><br></p>            <p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>        </div>    </div></div>', '', 'default', '', '0'),
('7', 'ticket_created', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Ticket #{TICKET_ID} Opened</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px; font-weight: bold;">Title: {TICKET_TITLE}</span><span style="line-height: 18.5714px;"><br></span></p><p style=""><span style="line-height: 18.5714px;">{TICKET_CONTENT}</span><br></p> <p style=""><br></p> <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{TICKET_URL}" target="_blank">Show Ticket</a></span></p> <p style=""><br></p><p style="">Regards,</p><p style=""><span style="line-height: 18.5714px;">{USER_NAME}</span><br></p>   </div>  </div> </div>', '', 'default', '', '0'),
('8', 'ticket_commented', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Ticket #{TICKET_ID} Replies</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px; font-weight: bold;">Title: {TICKET_TITLE}</span><span style="line-height: 18.5714px;"><br></span></p><p style=""><span style="line-height: 18.5714px;">{TICKET_CONTENT}</span></p><p style=""><span style="line-height: 18.5714px;"><br></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{TICKET_URL}" target="_blank">Show Ticket</a></span></p></div></div></div>', '', 'default', '', '0'),
('9', 'ticket_closed', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Ticket #{TICKET_ID}</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;">The Ticket #{TICKET_ID} has been closed by&nbsp;</span><span style="line-height: 18.5714px;">{USER_NAME}</span></p> <p style=""><br></p> <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{TICKET_URL}" target="_blank">Show Ticket</a></span></p>   </div>  </div> </div>', '', 'default', '', '0'),
('10', 'ticket_reopened', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Ticket #{TICKET_ID}</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;">The Ticket #{TICKET_ID} has been reopened by&nbsp;</span><span style="line-height: 18.5714px;">{USER_NAME}</span></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{TICKET_URL}" target="_blank">Show Ticket</a></span></p>  </div> </div></div>', '', 'default', '', '0'),
('11', 'general_notification', '{EVENT_TITLE}', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>{APP_TITLE}</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;">{EVENT_TITLE}</span></p><p style=""><span style="line-height: 18.5714px;">{EVENT_DETAILS}</span></p><p style=""><span style="line-height: 18.5714px;"><br></span></p><p style=""><span style="line-height: 18.5714px;"></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{NOTIFICATION_URL}" target="_blank">View Details</a></span></p>  </div> </div></div>', '', 'default', '', '0'),
('12', 'invoice_payment_confirmation', 'Payment received', '<div style="background-color: #eeeeef; padding: 50px 0;">
<div style="max-width: 640px; margin: 0 auto;">
<div style="color: #fff; text-align: center; background-color: #33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;">
<h1>Payment Confirmation</h1>
</div>
<div style="padding: 20px; background-color: #ffffff; font-size: 14px;">
<p>Hello,<br>We have received your payment of {PAYMENT_AMOUNT} for {INVOICE_FULL_ID} <br>Thank you for your business cooperation.</p>
<p>&nbsp;</p>
<p><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{INVOICE_URL}" target="_blank" rel="noopener" aria-invalid="true">View Invoice</a></p>
<p>&nbsp;</p>
<p>{SIGNATURE}</p>
</div>
</div>
</div>', '', 'default', '', '0'),
('13', 'message_received', '{SUBJECT}', '<meta content="text/html; charset=utf-8" http-equiv="Content-Type"> <meta content="width=device-width, initial-scale=1.0" name="viewport"> <style type="text/css"> #message-container p {margin: 10px 0;} #message-container h1, #message-container h2, #message-container h3, #message-container h4, #message-container h5, #message-container h6 { padding:10px; margin:0; } #message-container table td {border-collapse: collapse;} #message-container table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; } #message-container a span{padding:10px 15px !important;} </style> <table id="message-container" align="center" border="0" cellpadding="0" cellspacing="0" style="background:#eee; margin:0; padding:0; width:100% !important; line-height: 100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; font-family:Helvetica,Arial,sans-serif; color: #555;"> <tbody> <tr> <td valign="top"> <table align="center" border="0" cellpadding="0" cellspacing="0"> <tbody> <tr> <td height="50" width="600">&nbsp;</td> </tr> <tr> <td style="background-color:#33333e; padding:25px 15px 30px 15px; font-weight:bold; " width="600"><h2 style="color:#fff; text-align:center;">{USER_NAME} sent you a message</h2></td> </tr> <tr> <td bgcolor="whitesmoke" style="background:#fff; font-family:Helvetica,Arial,sans-serif" valign="top" width="600"> <table align="center" border="0" cellpadding="0" cellspacing="0"> <tbody> <tr> <td height="10" width="560">&nbsp;</td> </tr> <tr> <td width="560"><p><span style="background-color: transparent;">{MESSAGE_CONTENT}</span></p> <p style="display:inline-block; padding: 10px 15px; background-color: #00b393;"><a href="{MESSAGE_URL}" style="text-decoration: none; color:#fff;" target="_blank">Reply Message</a></p> </td> </tr> <tr> <td height="10" width="560">&nbsp;</td> </tr> </tbody> </table> </td> </tr> <tr> <td height="60" width="600">&nbsp;</td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', '0'),
('14', 'invoice_due_reminder_before_due_date', 'Invoice due reminder', '<div style="background-color: #eeeeef; padding: 50px 0;">
<div style="max-width: 640px; margin: 0 auto;">
<div style="color: #fff; text-align: center; background-color: #33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;">
<h1>Invoice Due Reminder</h1></div>
<div style="padding: 20px; background-color: #ffffff; font-size: 14px;">
<p>Hello,<br>We would like to remind you that invoice {INVOICE_FULL_ID} is due on {DUE_DATE}. Please pay the invoice within due date.&nbsp;</p>
<p><span>If you have already submitted the payment, please ignore this email.</span></p><p><span><br></span></p>
<p><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{INVOICE_URL}" target="_blank" rel="noopener" aria-invalid="true">Show Invoice</a></p>
<p>&nbsp;</p>
<p>{SIGNATURE}</p>
</div>
</div>
</div>', '', 'default', '', '0'),
('15', 'invoice_overdue_reminder', 'Invoice overdue reminder', '<div style="background-color: #eeeeef; padding: 50px 0;">
<div style="max-width: 640px; margin: 0 auto;">
<div style="color: #fff; text-align: center; background-color: #33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;">
<h1>Invoice Overdue Reminder</h1></div>
<div style="padding: 20px; background-color: #ffffff; font-size: 14px;">
<p>Hello,<br>We would like to remind you that you have an unpaid invoice {INVOICE_FULL_ID}. We kindly request you to pay the invoice as soon as possible.&nbsp;</p>
<p><span>If you have already submitted the payment, please ignore this email.</span></p><p><span><br></span></p>
<p><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{INVOICE_URL}" target="_blank" rel="noopener" aria-invalid="true">Show Invoice</a></p>
<p>&nbsp;</p>
<p>{SIGNATURE}</p>
</div>
</div>
</div>', '', 'default', '', '0'),
('16', 'recurring_invoice_creation_reminder', 'Recurring invoice creation reminder', '<div style="background-color: #eeeeef; padding: 50px 0;">
<div style="max-width: 640px; margin: 0 auto;">
<div style="color: #fff; text-align: center; background-color: #33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;">
<h1>Invoice Creation Reminder</h1></div>
<div style="padding: 20px; background-color: #ffffff; font-size: 14px;">
<p>Hello,<br>We would like to remind you that a recurring invoice will be created on {NEXT_RECURRING_DATE}.&nbsp;</p>
<p><span><br></span></p>
<p><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{INVOICE_URL}" target="_blank" rel="noopener" aria-invalid="true">Show Invoice</a></p>
<p>&nbsp;</p>
<p>{SIGNATURE}</p>
</div>
</div>
</div>', '', 'default', '', '0'),
('17', 'project_task_deadline_reminder', 'Project task deadline reminder', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>{APP_TITLE}</h1></div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">  <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello,</span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">This is to remind you that there are some tasks which deadline is {DEADLINE}.</span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">{TASKS_LIST}</span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>  </div> </div></div>', '', 'default', '', '0'),
('18', 'estimate_sent', 'New estimate', '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;"> <tbody><tr> <td align="center" valign="top" style="padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody><tr> <td align="center" valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;"> <tbody><tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;"> <h2 style="display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;"><font color="#ffffff" face="Arial"><span style="letter-spacing: -1px;"><b>ESTIMATE #{ESTIMATE_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"><p> Hello {CONTACT_FIRST_NAME},<br></p><p>Here is the estimate. Please check the attachment.</p><p></p></td></tr><tr><td valign="top" style="padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%; text-size-adjust: 100%;"><tbody><tr><td style="padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><tbody><tr><td align="center" valign="middle" style="font-size: 16px; padding: 10px; text-size-adjust: 100%;"><a href="{ESTIMATE_URL}" target="_blank" style="font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;">Show Estimate</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign="top" style="padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', '0'),
('19', 'estimate_request_received', 'Estimate request received', '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;"> <tbody><tr> <td align="center" valign="top" style="padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody><tr> <td align="center" valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;"> <tbody><tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;"> <h2 style="display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;"><font color="#ffffff" face="Arial"><span style="letter-spacing: -1px;"><b>ESTIMATE REQUEST #{ESTIMATE_REQUEST_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding: 20px 18px 0px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;"><p style="color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;"><span style="background-color: transparent;">A new estimate request has been received from {CONTACT_FIRST_NAME}.</span><br></p><p style="color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;"></p></td></tr><tr><td valign="top" style="padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%; text-size-adjust: 100%;"><tbody><tr><td style="padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><tbody><tr><td align="center" valign="middle" style="font-size: 16px; padding: 10px; text-size-adjust: 100%;"><a href="{ESTIMATE_REQUEST_URL}" target="_blank" style="font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;">Show Estimate Request</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign="top" style="padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', '0'),
('20', 'estimate_rejected', 'Estimate rejected', '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;"> <tbody><tr> <td align="center" valign="top" style="padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody><tr> <td align="center" valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;"> <tbody><tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;"> <h2 style="display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;"><font color="#ffffff" face="Arial"><span style="letter-spacing: -1px;"><b>ESTIMATE #{ESTIMATE_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding: 20px 18px 0px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;"><p style=""><font color="#606060" face="Arial"><span style="font-size: 15px;">The estimate #{ESTIMATE_ID} has been rejected.</span></font><br></p><p style="color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;"></p></td></tr><tr><td valign="top" style="padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%; text-size-adjust: 100%;"><tbody><tr><td style="padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><tbody><tr><td align="center" valign="middle" style="font-size: 16px; padding: 10px; text-size-adjust: 100%;"><a href="{ESTIMATE_URL}" target="_blank" style="font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;">Show Estimate</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign="top" style="padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', '0'),
('21', 'estimate_accepted', 'Estimate accepted', '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;"> <tbody><tr> <td align="center" valign="top" style="padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody><tr> <td align="center" valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;"> <tbody><tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;"> <h2 style="display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;"><font color="#ffffff" face="Arial"><span style="letter-spacing: -1px;"><b>ESTIMATE #{ESTIMATE_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding: 20px 18px 0px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;"><p style=""><font color="#606060" face="Arial"><span style="font-size: 15px;">The estimate #{ESTIMATE_ID} has been accepted.</span></font><br></p><p style="color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;"></p></td></tr><tr><td valign="top" style="padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%; text-size-adjust: 100%;"><tbody><tr><td style="padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><tbody><tr><td align="center" valign="middle" style="font-size: 16px; padding: 10px; text-size-adjust: 100%;"><a href="{ESTIMATE_URL}" target="_blank" style="font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;">Show Estimate</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign="top" style="padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', '0'),
('22', 'new_client_greetings', 'Welcome!', '<div style="background-color: #eeeeef; padding: 50px 0; ">    <div style="max-width:640px; margin:0 auto; ">  <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Welcome to {COMPANY_NAME}</h1> </div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">            <p><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello {CONTACT_FIRST_NAME},</span></p><p><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Thank you for creating your account. </span></p><p><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">We are happy to see you here.<br></span></p><hr><p style="color: rgb(85, 85, 85); font-size: 14px;">Dashboard URL:&nbsp;<a href="{DASHBOARD_URL}" target="_blank">{DASHBOARD_URL}</a></p><p style="color: rgb(85, 85, 85); font-size: 14px;"></p><p><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Email: {CONTACT_LOGIN_EMAIL}</span><br></p><p><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Password:&nbsp;{CONTACT_LOGIN_PASSWORD}</span></p><p style="color: rgb(85, 85, 85);"><br></p><p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>        </div>    </div></div>', '', 'default', '', '0'),
('23', 'verify_email', 'Please verify your email', '<div style="background-color: #eeeeef; padding: 50px 0; "><div style="max-width:640px; margin:0 auto; "><div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Account verification</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255); color:#555;"><p style="font-size: 14px;">To initiate the signup process, please click on the following link:<br></p><p style="font-size: 14px;"><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{VERIFY_EMAIL_URL}" target="_blank">Verify Email</a></span></p>  <p style="font-size: 14px;"><br></p><p style=""><span style="font-size: 14px;">If you did not initiate the request, you do not need to take any further action and can safely disregard this email.</span></p><p style=""><span style="font-size: 14px;"><br></span></p><p style="font-size: 14px;">{SIGNATURE}</p></div></div></div>', '', 'default', '', '0'),
('24', 'new_order_received', 'New order received', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>ORDER #{ORDER_ID}</h1></div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">  <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px;">A new order has been received from&nbsp;</span><span style="color: rgb(85, 85, 85); font-size: 14px;">{CONTACT_FIRST_NAME} and is attached here.</span><br></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{ORDER_URL}" target="_blank">Show Order</a></span></p><p style=""><br></p>  </div> </div></div>', '', 'default', '', '0'),
('25', 'order_status_updated', 'Order status updated', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>ORDER #{ORDER_ID}</h1></div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">  <p><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello {CONTACT_FIRST_NAME},</span><br></p><p><span style="font-size: 14px; line-height: 20px;">Thank you for your business cooperation.</span><br></p><p><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Your order&nbsp;</span><font color="#555555"><span style="font-size: 14px;">has been updated&nbsp;</span></font><span style="color: rgb(85, 85, 85); font-size: 14px;">and is attached here.</span></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{ORDER_URL}" target="_blank">Show Order</a></span></p><p style=""><br></p>  </div> </div></div>', '', 'default', '', '0'),
('26', 'proposal_sent', 'Proposal sent', '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;"> <tbody><tr> <td align="center" valign="top" style="padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody><tr> <td align="center" valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;"> <tbody><tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;"> <h2 style="display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;"><font color="#ffffff" face="Arial"><span style="letter-spacing: -1px;"><b>PROPOSAL #{PROPOSAL_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"><p> Hello {CONTACT_FIRST_NAME},<br></p><p>Here is a proposal for you.</p><p></p></td></tr><tr><td valign="top" style="padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%; text-size-adjust: 100%;"><tbody><tr><td style="padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><tbody><tr><td align="center" valign="middle" style="font-size: 16px; padding: 10px; text-size-adjust: 100%;"><a href="{PROPOSAL_URL}" target="_blank" style="font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;">Show Proposal</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign="top" style="padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"><p> </p><p>Public URL: {PUBLIC_PROPOSAL_URL}</p><p><br></p><p>{SIGNATURE} </p></td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', '0'),
('27', 'project_completed', 'Project completed', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Project #{PROJECT_ID}</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;">The Project #{PROJECT_ID}&nbsp;has been closed by&nbsp;</span><span style="line-height: 18.5714px;">{USER_NAME}</span></p><p style=""><span style="line-height: 18.5714px;">Title:&nbsp;</span>{PROJECT_TITLE}</p> <p style=""><br></p> <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{PROJECT_URL}" target="_blank">Show Project</a></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="color: rgb(78, 94, 106); font-size: 13.5px;">{SIGNATURE}</span><br></span></p>   </div>  </div> </div>', '', 'default', '', '0'),
('28', 'proposal_accepted', 'Proposal accepted', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>PROPOSAL #{PROPOSAL_ID}</h1></div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">  <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px;">The proposal #{PROPOSAL_ID} has been accepted.</span><br></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{PROPOSAL_URL}" target="_blank">Show Proposal</a></span></p><p style=""><br></p>  </div> </div></div>', '', 'default', '', '0'),
('29', 'proposal_rejected', 'Proposal rejected', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>PROPOSAL #{PROPOSAL_ID}</h1></div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">  <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px;">The proposal #{PROPOSAL_ID} has been rejected.</span><br></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{PROPOSAL_URL}" target="_blank">Show Proposal</a></span></p><p style=""><br></p>  </div> </div></div>', '', 'default', '', '0'),
('30', 'estimate_commented', 'Estimate  #{ESTIMATE_ID}', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Estimate #{ESTIMATE_ID} Replies</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;">{COMMENT_CONTENT}</span></p><p style=""><span style="line-height: 18.5714px;"><br></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{ESTIMATE_URL}" target="_blank">Show Estimate</a></span></p></div></div></div>', '', 'default', '', '0'),
('31', 'contract_sent', 'Contract sent', '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;"> <tbody><tr> <td align="center" valign="top" style="padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody><tr> <td align="center" valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;"> <tbody><tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;"> <h2 style="display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;"><font color="#ffffff" face="Arial"><span style="letter-spacing: -1px;"><b>CONTRACT #{CONTRACT_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody><tr> <td valign="top" style="padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"><p> Hello {CONTACT_FIRST_NAME},<br></p><p>Here is a contract for you.</p><p></p></td></tr><tr><td valign="top" style="padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%; text-size-adjust: 100%;"><tbody><tr><td style="padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><tbody><tr><td align="center" valign="middle" style="font-size: 16px; padding: 10px; text-size-adjust: 100%;"><a href="{CONTRACT_URL}" target="_blank" style="font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;">Show Contract</a></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td valign="top" style="padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"><p>Public URL: {PUBLIC_CONTRACT_URL}<br></p><p><br></p><p>{SIGNATURE}<br></p></td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', '0'),
('32', 'contract_accepted', 'Contract accepted', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>CONTRACT #{CONTRACT_ID}</h1></div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">  <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px;">The contract #{CONTRACT_ID} has been accepted.</span><br></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{CONTRACT_URL}" target="_blank">Show Contract</a></span></p><p style=""><br></p>  </div> </div></div>', '', 'default', '', '0'),
('33', 'contract_rejected', 'Contract rejected', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>CONTRACT #{CONTRACT_ID}</h1></div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">  <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px;">The contract #{CONTRACT_ID} has been rejected.</span><br></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{CONTRACT_URL}" target="_blank">Show Contract</a></span></p><p style=""><br></p>  </div> </div></div>', '', 'default', '', '0'),
('34', 'invoice_manual_payment_added', 'Manual payment added', '<div style="background-color: #eeeeef; padding: 50px 0;">
<div style="max-width: 640px; margin: 0 auto;">
<div style="color: #fff; text-align: center; background-color: #33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;">
<h1>Payment Added</h1></div>
<div style="padding: 20px; background-color: #ffffff; font-size: 14px;">
<p>Hello,<br>A new payment has been added to {INVOICE_FULL_ID}.&nbsp;</p>
<p>Payment amount: {PAYMENT_AMOUNT}&nbsp;</p>
<p><span><br></span></p>
<p><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{INVOICE_URL}" target="_blank" rel="noopener" aria-invalid="true">Show Invoice</a></p>
<p>&nbsp;</p>
<p>{SIGNATURE}</p>
</div>
</div>
</div>', '', 'default', '', '0'),
('35', 'subscription_request_sent', 'New subscription request', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h2>{SUBSCRIPTION_TITLE}</h2></div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">  <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello {CONTACT_FIRST_NAME},</span><br></p><p style=""><span style="font-size: 14px;">You have a new subscription request. Please click here to see the subscription.</span></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{SUBSCRIPTION_URL}" target="_blank">Show Subscription</a></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>  </div> </div></div>', '', 'default', '', '0'),
('36', 'announcement_created', '{ANNOUNCEMENT_TITLE}', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Announcement: {ANNOUNCEMENT_TITLE}</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;">A new announcement has been created by {USER_NAME}.</span></p><p style=""><span style="line-height: 18.5714px;"><br></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{ANNOUNCEMENT_URL}" target="_blank">Show Announcement</a></span></p></div></div></div>', '', 'default', '', '0'),
('37', 'task_general', '{TASK_TITLE} (Task #{TASK_ID})', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>{EVENT_TITLE}</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;"><b>Task:</b> #</span><span style="font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);">{TASK_ID} -&nbsp;</span><span style="font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);">{TASK_TITLE}</span></p><p style=""><span style="line-height: 18.5714px;"><b>{CONTEXT_LABEL}:</b>&nbsp;</span>{CONTEXT_TITLE}</p> <p style=""><br></p> <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{TASK_URL}" target="_blank">Show Task&nbsp;</a></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="color: rgb(78, 94, 106); font-size: 13.5px;">{SIGNATURE}</span><br></span></p>   </div>  </div> </div>', '', 'default', '', '0'),
('38', 'task_assigned', '{TASK_TITLE} (Task #{TASK_ID})', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Task assigned</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;"><b>{USER_NAME}</b>  Assigned a task to <b>{ASSIGNED_TO_USER_NAME}</b></span></p><p style=""><span style="line-height: 18.5714px;"><b>Task:</b> #</span><span style="font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);">{TASK_ID} -&nbsp;</span><span style="font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);">{TASK_TITLE}</span></p><p style=""><span style="line-height: 18.5714px;"><b>{CONTEXT_LABEL}:</b>&nbsp;</span>{CONTEXT_TITLE}</p> <p style=""><br></p> <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{TASK_URL}" target="_blank">Show Task&nbsp;</a></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="color: rgb(78, 94, 106); font-size: 13.5px;">{SIGNATURE}</span><br></span></p>   </div>  </div> </div>', '', 'default', '', '0'),
('39', 'task_commented', '{TASK_TITLE} (Task #{TASK_ID})', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Task commented</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;"><b>{USER_NAME}</b>  Commented on a task.</span></p><p style=""><span style="line-height: 18.5714px;"><b>Task:</b> #</span><span style="font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);">{TASK_ID} -&nbsp;</span><span style="font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);">{TASK_TITLE}</span></p><p style=""><span style="line-height: 18.5714px;"><b>{CONTEXT_LABEL}:</b>&nbsp;</span>{CONTEXT_TITLE}</p> <p style=""><br></p> <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{TASK_URL}" target="_blank">Show Task&nbsp;</a></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="color: rgb(78, 94, 106); font-size: 13.5px;">{SIGNATURE}</span><br></span></p>   </div>  </div> </div>', '', 'default', '', '0'),
('40', 'subscription_started', 'Started a subscription', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h2>{SUBSCRIPTION_TITLE}</h2></div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">  <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello {CONTACT_FIRST_NAME},</span><br></p><p style=""><span style="font-size: 14px;">A new subscription has been started.&nbsp;</span></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{SUBSCRIPTION_URL}" target="_blank">Show Subscription</a></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>  </div> </div></div>', '', 'default', '', '0'),
('41', 'subscription_invoice_created_via_cron_job', 'New invoice generated from subscription', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>INVOICE #{INVOICE_ID}</h1></div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">  <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello {CONTACT_FIRST_NAME},</span><br></p><p style=""><span style="font-size: 14px; line-height: 20px;">Thank you for your business cooperation.</span><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Your invoice for the subscription {SUBSCRIPTION_TITLE} has been generated and is attached here.</span></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{INVOICE_URL}" target="_blank">Show Invoice</a></span></p><p style=""><span style="font-size: 14px; line-height: 20px;"><br></span></p><p style=""><span style="font-size: 14px; line-height: 20px;">Invoice balance due is {BALANCE_DUE}</span><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Please pay this invoice within {DUE_DATE}.&nbsp;</span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>  </div> </div></div>', '', 'default', '', '0'),
('42', 'send_credit_note', 'New credit note', '<div style="background-color: #eeeeef; padding: 50px 0;">
<div style="max-width: 640px; margin: 0 auto;">
<div style="color: #fff; text-align: center; background-color: #33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;">
<h1>CREDIT NOTE #{CREDIT_NOTE_FULL_ID}</h1></div>
<div style="padding: 20px; background-color: #ffffff; font-size: 14px;">
<p>Hello {CONTACT_FIRST_NAME},&nbsp;</p>
<p>Your invoice {INVOICE_FULL_ID} has been credited.&nbsp;</p>
<p>Here is the credit note.&nbsp;&nbsp;</p>
<p><span><br></span></p>
<p><span style="color: #555555; font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{CREDIT_NOTE_URL}" target="_blank" rel="noopener" aria-invalid="true">Show Credit Note</a></span></p>
<p>&nbsp;</p>
<p>{SIGNATURE}</p>
</div>
</div>
</div>', '', 'default', '', '0'),
('43', 'subscription_cancelled', 'Subscription cancelled', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h2>{SUBSCRIPTION_TITLE}</h2></div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">  <p style=""><font color="#606060" face="Arial"><span style="font-size: 15px;">The subscription {SUBSCRIPTION_TITLE} has been cancelled by {CANCELLED_BY}.</span></font><br></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{SUBSCRIPTION_URL}" target="_blank">Show Subscription</a></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>  </div> </div></div>', '', 'default', '', '0'),
('44', 'proposal_commented', 'Proposal #{PROPOSAL_ID}', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Proposal #{PROPOSAL_ID} Replies</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;">{COMMENT_CONTENT}</span></p><p style=""><span style="line-height: 18.5714px;"><br></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{PROPOSAL_URL}" target="_blank">Show Proposal</a></span></p></div></div></div>', '', 'default', '', '0'),
('45', 'subscription_renewal_reminder', 'Subscription Renewal Reminder', '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;"> <tbody> <tr> <td align="center" valign="top" style="padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td align="center" valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody> <tr> <td valign="top" style="padding-top: 40px;padding-right: 18px;padding-bottom: 40px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"> <h2 style="display: block;margin: 0;padding: 0;font-family: Arial;font-size: 30px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: -1px;text-align: center;color: #ffffff !important;">Subscription Renewal Reminder</h2> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td valign="top" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%"> <tbody> <tr> <td valign="top" style="padding: 20px 18px 0px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;"> <p style=""><font color="#606060" face="Arial"><span style="font-size: 15px;">This is a reminder that your subscription:&nbsp;<b>{SUBSCRIPTION_TITLE}</b></span></font><font color="#606060" face="Arial" style="font-weight: var(--bs-body-font-weight);"><span style="font-size: 15px;">&nbsp;</span></font><font color="#606060" face="Arial" style="font-weight: var(--bs-body-font-weight);"><span style="font-size: 15px;">will renew soon. Please ensure that your payment details are up to date to avoid any interruptions in your service.</span></font></p> <p style=""><font color="#606060" face="Arial"><span style="font-size: 15px;"><br></span></font></p> <p style=""><font color="#606060" face="Arial"><span style="font-size: 15px;">If you have already renewed your subscription, please ignore this email. </span></font></p> <p style=""><font color="#606060" face="Arial"><span style="font-size: 15px;">Thank you for your continued support.</span></font><br></p> </td> </tr> <tr> <td valign="top" style="padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td style="padding-top: 15px;padding-right: 0x;padding-bottom: 15px;padding-left: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <tbody> <tr> <td align="center" valign="middle" style="font-family: Arial;font-size: 16px;padding: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"> <a href="{SUBSCRIPTION_URL}" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;display: block;">View Subscription</a> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <p></p> </td> </tr> <tr> <td valign="top" style="padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', '0'),
('46', 'upcoming_event', 'Upcoming Event', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Upcoming Event</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;">Hello, </span></p><p style=""><span style="line-height: 18.5714px;">There is an&nbsp;</span>upcoming event. Please check the details below:</p><p style=""><span style="line-height: 18.5714px; font-weight: bold;"><br></span></p><p style=""><span style="line-height: 18.5714px; font-weight: bold;">Title: </span><span style="line-height: 18.5714px;">{EVENT_TITLE}</span></p><p style=""><span style="line-height: 18.5714px;"><b>Event time:&nbsp;</b></span>{EVENT_DATE_TIME}</p><p style=""><span style="line-height: 18.5714px;">{EVENT_DESCRIPTION}</span></p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{EVENT_URL}" target="_blank">Show Event</a></span></p><p style=""><br></p><p style="">{SIGNATURE}</p></div></div></div>', '', 'default', '', '0'),
('47', 'upcoming_reminder', 'Upcoming Reminder', '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Upcoming Reminder</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;">Hello, </span></p><p style=""><span style="line-height: 18.5714px;">There is an&nbsp;</span>upcoming reminder. Please check the details below:</p><p style=""><span style="line-height: 18.5714px; font-weight: bold;"><br></span></p><p style=""><span style="line-height: 18.5714px; font-weight: bold;">Title: </span><span style="line-height: 18.5714px;">{REMINDER_TITLE}</span></p><p style=""><span style="line-height: 18.5714px;"><b>Reminder time:&nbsp;</b></span>{REMINDER_DATE_TIME}</p><p style=""><br></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{REMINDER_URL}" target="_blank">Show Reminder</a></span></p><p style=""><br></p><p style="">{SIGNATURE}</p></div></div></div>', '', 'default', '', '0');
INSERT INTO public."ncs_expense_categories" ("id", "title", "deleted") VALUES ('1', 'Miscellaneous expense', '0');
INSERT INTO public."ncs_item_categories" ("id", "title", "deleted") VALUES ('1', 'General item', '0');
INSERT INTO public."ncs_lead_source" ("id", "title", "sort", "deleted") VALUES ('1', 'Google', '1', '0'),
('2', 'Facebook', '2', '0'),
('3', 'Twitter', '3', '0'),
('4', 'Youtube', '4', '0'),
('5', 'Elsewhere', '5', '0');
INSERT INTO public."ncs_lead_status" ("id", "title", "color", "sort", "deleted") VALUES ('1', 'New', '#f1c40f', '0', '0'),
('2', 'Qualified', '#2d9cdb', '1', '0'),
('3', 'Discussion', '#29c2c2', '2', '0'),
('4', 'Negotiation', '#2d9cdb', '3', '0'),
('5', 'Won', '#83c340', '4', '0'),
('6', 'Lost', '#e74c3c', '5', '0');
INSERT INTO public."ncs_leave_types" ("id", "title", "status", "color", "description", "deleted") VALUES ('1', 'Casual Leave', 'active', '#83c340', '', '0');
INSERT INTO public."ncs_notification_settings" ("id", "event", "category", "enable_email", "enable_web", "enable_slack", "notify_to_team", "notify_to_team_members", "notify_to_terms", "sort", "deleted") VALUES ('1', 'project_created', 'project', '0', '0', '0', '', '', '', '1', '0'),
('2', 'project_deleted', 'project', '0', '0', '0', '', '', '', '2', '0'),
('3', 'project_task_created', 'project', '0', '1', '0', '', '', 'task_assignee,task_collaborators', '3', '0'),
('4', 'project_task_updated', 'project', '0', '1', '0', '', '', 'task_assignee,task_collaborators', '4', '0'),
('5', 'project_task_assigned', 'project', '0', '1', '0', '', '', 'task_assignee,task_collaborators', '5', '0'),
('7', 'project_task_started', 'project', '0', '0', '0', '', '', '', '7', '0'),
('8', 'project_task_finished', 'project', '0', '0', '0', '', '', '', '8', '0'),
('9', 'project_task_reopened', 'project', '0', '0', '0', '', '', '', '9', '0'),
('10', 'project_task_deleted', 'project', '0', '1', '0', '', '', 'task_assignee,task_collaborators', '10', '0'),
('11', 'project_task_commented', 'project', '0', '1', '0', '', '', 'task_assignee,task_collaborators,mentioned_members', '11', '0'),
('12', 'project_member_added', 'project', '0', '1', '0', '', '', 'project_members', '12', '0'),
('13', 'project_member_deleted', 'project', '0', '1', '0', '', '', 'project_members', '13', '0'),
('14', 'project_file_added', 'project', '0', '1', '0', '', '', 'project_members', '14', '0'),
('15', 'project_file_deleted', 'project', '0', '1', '0', '', '', 'project_members', '15', '0'),
('16', 'project_file_commented', 'project', '0', '1', '0', '', '', 'project_members,mentioned_members', '16', '0'),
('17', 'project_comment_added', 'project', '0', '1', '0', '', '', 'project_members,mentioned_members', '17', '0'),
('18', 'project_comment_replied', 'project', '0', '1', '0', '', '', 'project_members,comment_creator,mentioned_members', '18', '0'),
('19', 'project_customer_feedback_added', 'project', '0', '1', '0', '', '', 'project_members,mentioned_members', '19', '0'),
('20', 'project_customer_feedback_replied', 'project', '0', '1', '0', '', '', 'project_members,client_primary_contact,comment_creator,mentioned_members', '20', '0'),
('21', 'client_signup', 'client', '0', '0', '0', '', '', '', '21', '0'),
('22', 'invoice_online_payment_received', 'invoice', '0', '0', '0', '', '', '', '22', '0'),
('23', 'leave_application_submitted', 'leave', '0', '0', '0', '', '', '', '23', '0'),
('24', 'leave_approved', 'leave', '0', '1', '0', '', '', 'leave_applicant', '24', '0'),
('25', 'leave_assigned', 'leave', '0', '1', '0', '', '', 'leave_applicant', '25', '0'),
('26', 'leave_rejected', 'leave', '0', '1', '0', '', '', 'leave_applicant', '26', '0'),
('27', 'leave_canceled', 'leave', '0', '0', '0', '', '', '', '27', '0'),
('28', 'ticket_created', 'ticket', '0', '0', '0', '', '', 'ticket_assignee', '28', '0'),
('29', 'ticket_commented', 'ticket', '0', '1', '0', '', '', 'client_primary_contact,ticket_creator,ticket_assignee', '29', '0'),
('30', 'ticket_closed', 'ticket', '0', '1', '0', '', '', 'client_primary_contact,ticket_creator,ticket_assignee', '30', '0'),
('31', 'ticket_reopened', 'ticket', '0', '1', '0', '', '', 'client_primary_contact,ticket_creator,ticket_assignee', '31', '0'),
('32', 'estimate_request_received', 'estimate', '0', '0', '0', '', '', '', '32', '0'),
('34', 'estimate_accepted', 'estimate', '0', '0', '0', '', '', '', '34', '0'),
('35', 'estimate_rejected', 'estimate', '0', '0', '0', '', '', '', '35', '0'),
('36', 'new_message_sent', 'message', '0', '0', '0', '', '', '', '36', '0'),
('37', 'message_reply_sent', 'message', '0', '0', '0', '', '', '', '37', '0'),
('38', 'invoice_payment_confirmation', 'invoice', '0', '0', '0', '', '', '', '22', '0'),
('39', 'new_event_added_in_calendar', 'event', '0', '1', '0', '', '', 'recipient', '39', '0'),
('40', 'recurring_invoice_created_vai_cron_job', 'invoice', '0', '0', '0', '', '', 'client_primary_contact', '22', '0'),
('41', 'new_announcement_created', 'announcement', '0', '1', '0', '', '', 'recipient', '41', '0'),
('42', 'invoice_due_reminder_before_due_date', 'invoice', '0', '1', '0', '', '', 'client_primary_contact', '22', '0'),
('43', 'invoice_overdue_reminder', 'invoice', '0', '1', '0', '', '', 'client_primary_contact', '22', '0'),
('44', 'recurring_invoice_creation_reminder', 'invoice', '0', '0', '0', '', '', '', '22', '0'),
('45', 'project_completed', 'project', '0', '0', '0', '', '', '', '2', '0'),
('46', 'lead_created', 'lead', '0', '0', '0', '', '', '', '21', '0'),
('47', 'client_created_from_lead', 'lead', '0', '0', '0', '', '', '', '21', '0'),
('48', 'project_task_deadline_pre_reminder', 'project', '0', '1', '0', '', '', 'task_assignee', '20', '0'),
('49', 'project_task_reminder_on_the_day_of_deadline', 'project', '0', '1', '0', '', '', 'task_assignee', '20', '0'),
('50', 'project_task_deadline_overdue_reminder', 'project', '0', '1', '0', '', '', 'task_assignee', '20', '0'),
('51', 'recurring_task_created_via_cron_job', 'project', '0', '1', '0', '', '', 'project_members,task_assignee', '20', '0'),
('52', 'calendar_event_modified', 'event', '0', '0', '0', '', '', '', '39', '0'),
('53', 'client_contact_requested_account_removal', 'client', '0', '0', '0', '', '', '', '21', '0'),
('54', 'bitbucket_push_received', 'project', '0', '0', '0', '', '', '', '45', '0'),
('55', 'github_push_received', 'project', '0', '0', '0', '', '', '', '45', '0'),
('56', 'invited_client_contact_signed_up', 'client', '0', '0', '0', '', '', '', '21', '0'),
('57', 'created_a_new_post', 'timeline', '0', '0', '0', '', '', '', '52', '0'),
('58', 'timeline_post_commented', 'timeline', '0', '1', '0', '', '', 'post_creator', '52', '0'),
('59', 'ticket_assigned', 'ticket', '0', '1', '0', '', '', 'ticket_assignee', '31', '0'),
('60', 'new_order_received', 'order', '0', '0', '0', '', '', '', '1', '0'),
('61', 'order_status_updated', 'order', '0', '0', '0', '', '', '', '2', '0'),
('62', 'proposal_accepted', 'proposal', '0', '0', '0', '', '', '', '34', '0'),
('63', 'proposal_rejected', 'proposal', '0', '0', '0', '', '', '', '35', '0'),
('64', 'estimate_commented', 'estimate', '0', '0', '0', '', '', '', '35', '0'),
('65', 'invoice_manual_payment_added', 'invoice', '0', '0', '0', '', '', '', '22', '0'),
('66', 'contract_accepted', 'contract', '0', '0', '0', '', '', '', '66', '0'),
('67', 'contract_rejected', 'contract', '0', '0', '0', '', '', '', '67', '0'),
('68', 'subscription_request_sent', 'subscription', '0', '1', '0', '', '', 'client_primary_contact', '68', '0'),
('69', 'subscription_started', 'subscription', '0', '1', '0', '', '', 'client_primary_contact', '68', '0'),
('70', 'subscription_invoice_created_via_cron_job', 'subscription', '0', '1', '0', '', '', 'client_primary_contact', '68', '0'),
('71', 'general_task_created', 'general_task', '0', '1', '0', '', '', 'task_assignee,task_collaborators', '69', '0'),
('72', 'general_task_updated', 'general_task', '0', '1', '0', '', '', 'task_assignee,task_collaborators', '70', '0'),
('73', 'general_task_assigned', 'general_task', '0', '1', '0', '', '', 'task_assignee,task_collaborators', '71', '0'),
('74', 'general_task_started', 'general_task', '0', '0', '0', '', '', '', '72', '0'),
('75', 'general_task_finished', 'general_task', '0', '0', '0', '', '', '', '73', '0'),
('76', 'general_task_reopened', 'general_task', '0', '0', '0', '', '', '', '74', '0'),
('77', 'general_task_deleted', 'general_task', '0', '1', '0', '', '', 'task_assignee,task_collaborators', '75', '0'),
('78', 'general_task_commented', 'general_task', '0', '1', '0', '', '', 'task_assignee,task_collaborators,mentioned_members', '76', '0'),
('79', 'proposal_commented', 'proposal', '0', '0', '0', '', '', '', '77', '0'),
('80', 'subscription_cancelled', 'subscription', '0', '0', '0', '', '', '', '68', '0'),
('81', 'proposal_preview_opened', 'proposal', '0', '0', '0', '', '', '', '77', '0'),
('82', 'proposal_email_opened', 'proposal', '0', '0', '0', '', '', '', '77', '0'),
('83', 'subscription_renewal_reminder', 'subscription', '0', '0', '0', '', '', '', '68', '0'),
('84', 'upcoming_event', 'event', '0', '0', '0', '', '', '', '81', '0'),
('85', 'upcoming_reminder', 'reminder', '0', '0', '0', '', '', '', '82', '0');
INSERT INTO public."ncs_order_status" ("id", "title", "color", "sort", "deleted") VALUES ('1', 'New', '#f1c40f', '0', '0'),
('2', 'Processing', '#29c2c2', '1', '0'),
('3', 'Confirmed', '#83c340', '2', '0');
INSERT INTO public."ncs_payment_methods" ("id", "title", "type", "description", "online_payable", "available_on_invoice", "minimum_payment_amount", "settings", "sort", "deleted") VALUES ('1', 'Cash', 'custom', 'Cash payments', '0', '0', '0', '', '0', '0'),
('2', 'Stripe', 'stripe', 'Stripe online payments', '1', '0', '0', 'a:3:{s:15:"pay_button_text";s:6:"Stripe";s:10:"secret_key";s:6:"";s:15:"publishable_key";s:6:"";}', '0', '0'),
('3', 'PayPal Payments Standard', 'paypal_payments_standard', 'PayPal Payments Standard Online Payments', '1', '0', '0', 'a:4:{s:15:"pay_button_text";s:6:"PayPal";s:5:"email";s:4:"";s:11:"paypal_live";s:1:"0";s:5:"debug";s:1:"0";}', '0', '0'),
('4', 'Paytm', 'paytm', 'Paytm online payments', '1', '0', '0', '', '0', '0'),
('5', 'Client Wallet', 'client_wallet', 'Client wallet to store and allocate funds to invoices', '0', '0', '0', '', '0', '0');
INSERT INTO public."ncs_project_status" ("id", "title", "title_language_key", "key_name", "icon", "deleted") VALUES ('1', 'Open', 'open', 'open', 'grid', '0'),
('2', 'Completed', 'completed', 'completed', 'check-circle', '0'),
('3', 'Hold', 'hold', '', 'pause-circle', '0'),
('4', 'Canceled', 'canceled', '', 'x-circle', '0');
INSERT INTO public."ncs_proposal_templates" ("id", "title", "template", "deleted") VALUES ('1', 'Template 3.9', '<p><br></p>
<p><br></p>
<p><br></p>
<h1 style="text-align: center;">Web Design Proposal</h1>
<p style="text-align: center;"><br></p>


<p><img src="/assets/images/image_preview.png" style="width: 100%;"><br>
</p>
<p><br></p>
<p style="text-align: justify;">In response to the growing demands and opportunities within the industry, we propose to develop a comprehensive solution tailored to address key challenges and capitalize on emerging trends. Our proposal aims to deliver tangible value by leveraging our expertise, innovative approaches, and commitment to excellence.</p>
<p style="text-align: justify;"><br></p>
<p><br></p>
<h3 style="text-align: left;">{PROPOSAL_ID}</h3>
<p style="text-align: left;">Issued on {PROPOSAL_DATE}. Please note: this proposal expires on {PROPOSAL_EXPIRY_DATE}.</p>
<p style="text-align: left;"><br></p>
<p style="text-align: left;">To:</p>
<p style="text-align: left;">{PROPOSAL_TO_INFO}</p>
<p style="text-align: left;"><br></p>
<p style="text-align: left;">Proposal from:&nbsp;</p>
<p style="text-align: left;">{COMPANY_INFO}</p>
<p><br></p>
<p><br></p>
<h3>Our Best Offer</h3>
<p>In consideration of your unique needs and aspirations, we are pleased to present our best offer, crafted with meticulous attention to detail and driven by a commitment to delivering exceptional value.</p>
<p>{PROPOSAL_ITEMS}</p>
<p><br></p>
<h3><br></h3>
<h3><br></h3>
<h3>Our Objective</h3>
<p>Our objective is to align seamlessly with your business goals, leveraging our expertise and resources to drive tangible results and foster long-term success. Through a collaborative partnership, we aim to understand your unique challenges, opportunities, and aspirations, enabling us to tailor our approach to meet your specific needs. By focusing on measurable outcomes, continuous improvement, and proactive communication, we are committed to exceeding your expectations and establishing a foundation for sustained growth and competitiveness in a dynamic business environment.</p>
<p><img src="/assets/images/image_preview.png" style="width: 100%;"><br>
</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<h3>Our Portfolio</h3>
<p>Some of our recent work here:</p>
<table class="table table-bordered">
<tbody>
<tr>
<td>
<p>
<span class="timeline-images inline-block"><img class="pasted-image" src="/assets/images/image_preview.png"></span><br>
</p>
</td>
<td>
<p>
<span class="timeline-images inline-block"><img class="pasted-image" src="/assets/images/image_preview.png"></span>
</p>
</td>
</tr>
<tr>
<td>
<p>
<span class="timeline-images inline-block"><img class="pasted-image" src="/assets/images/image_preview.png"></span>
</p>
</td>
<td>
<p>
<span class="timeline-images inline-block"><img class="pasted-image" src="/assets/images/image_preview.png"></span>

</p>
</td>
</tr>
</tbody>
</table>
<p><br></p>
<h3>Let’s Connect</h3>
<p>We are excited about the chance to collaborate. Drop us a line at {COMPANY_EMAIL} or give us a call at {COMPANY_PHONE} — we would love to hear from you.</p>', '0');
INSERT INTO public."ncs_settings" ("setting_name", "setting_value", "type", "deleted") VALUES ('accepted_file_formats', 'jpg,jpeg,png,doc,xlsx,txt,pdf,zip,webm', 'app', '0'),
('allowed_ip_addresses', '', 'app', '0'),
('app_title', 'National Council Of Sports', 'app', '0'),
('app_verification_key', 'APP-VERIFICATION-KEY', 'app', '0'),
('contract_color', '#000000', 'app', '0'),
('currency_symbol', '$', 'app', '0'),
('date_format', 'Y-m-d', 'app', '0'),
('decimal_separator', '.', 'app', '0'),
('default_contract_template', '1', 'app', '0'),
('default_currency', 'USD', 'app', '0'),
('default_due_date_after_billing_date', '14', 'app', '0'),
('default_permissions_for_non_primary_contact', 'projects', 'app', '0'),
('default_proposal_template', '1', 'app', '0'),
('default_theme_color', 'F2F2F2', 'app', '0'),
('email_sent_from_address', 'noreply@ncsintranet.atenimedia.com', 'app', '0'),
('email_sent_from_name', 'National Council Of Sports', 'app', '0'),
('enable_audio_recording', '1', 'app', '0'),
('estimate_color', '#000000', 'app', '0'),
('first_day_of_week', '0', 'app', '0'),
('invoice_color', '#000000', 'app', '0'),
('invoice_item_list_background', '#f4f4f4', 'app', '0'),
('invoice_logo', 'default-invoice-logo.png', 'app', '0'),
('invoice_number_format', '{SERIAL}', 'app', '0'),
('invoice_prefix', 'INVOICE #', 'app', '0'),
('item_purchase_code', 'ITEM-PURCHASE-CODE', 'app', '0'),
('module_announcement', '1', 'app', '0'),
('module_attendance', '1', 'app', '0'),
('module_chat', '1', 'app', '0'),
('module_contract', '1', 'app', '0'),
('module_estimate', '1', 'app', '0'),
('module_estimate_request', '1', 'app', '0'),
('module_event', '1', 'app', '0'),
('module_expense', '1', 'app', '0'),
('module_file_manager', '1', 'app', '0'),
('module_gantt', '1', 'app', '0'),
('module_help', '1', 'app', '0'),
('module_invoice', '1', 'app', '0'),
('module_knowledge_base', '1', 'app', '0'),
('module_lead', '1', 'app', '0'),
('module_leave', '1', 'app', '0'),
('module_message', '1', 'app', '0'),
('module_note', '1', 'app', '0'),
('module_order', '1', 'app', '0'),
('module_project_timesheet', '1', 'app', '0'),
('module_proposal', '1', 'app', '0'),
('module_reminder', '1', 'app', '0'),
('module_subscription', '1', 'app', '0'),
('module_ticket', '1', 'app', '0'),
('module_timeline', '1', 'app', '0'),
('module_todo', '1', 'app', '0'),
('order_color', '#000000', 'app', '0'),
('proposal_color', '#000000', 'app', '0'),
('show_the_status_checkbox_in_tasks_list', '1', 'app', '0'),
('show_theme_color_changer', 'yes', 'app', '0'),
('signin_page_background', 'sigin-background-image.jpg', 'app', '0'),
('site_logo', 'ncs_logo.png', 'app', '0'),
('favicon', 'ncs_logo.png', 'app', '0'),
('show_logo_in_signin_page', 'yes', 'app', '0'),
('task_point_range', '5', 'app', '0'),
('time_format', 'small', 'app', '0'),
('timezone', 'UTC', 'app', '0');
INSERT INTO public."ncs_task_priority" ("id", "title", "icon", "color", "deleted") VALUES ('1', 'Minor', 'arrow-down', '#aab7b7', '0'),
('2', 'Major', 'arrow-up', '#e18a00', '0'),
('3', 'Critical ', 'alert-circle', '#ad159e', '0'),
('4', 'Blocker ', 'alert-octagon', '#e74c3c', '0');
INSERT INTO public."ncs_task_status" ("id", "title", "key_name", "color", "sort", "hide_from_kanban", "deleted", "hide_from_non_project_related_tasks") VALUES ('1', 'To Do', 'to_do', '#F9A52D', '0', '0', '0', '0'),
('2', 'In progress', 'in_progress', '#1672B9', '1', '0', '0', '0'),
('3', 'Done', 'done', '#00B393', '2', '0', '0', '0');
INSERT INTO public."ncs_taxes" ("id", "title", "percentage", "deleted", "stripe_tax_id") VALUES ('1', 'Tax (10%)', '10', '0', '');
INSERT INTO public."ncs_ticket_types" ("id", "title", "deleted") VALUES ('1', 'General Support', '0');
-- Explicit ids were inserted above, so move every serial sequence past them.
DO $$
DECLARE r record;
BEGIN
  FOR r IN
    SELECT t.relname AS tbl, a.attname AS col, pg_get_serial_sequence(format('public.%I', t.relname), a.attname) AS seq
    FROM pg_class t JOIN pg_namespace ns ON ns.oid = t.relnamespace
    JOIN pg_attribute a ON a.attrelid = t.oid AND a.attnum > 0 AND NOT a.attisdropped
    WHERE ns.nspname = 'public' AND t.relkind = 'r'
  LOOP
    IF r.seq IS NOT NULL THEN
      EXECUTE format('SELECT setval(%L, GREATEST(COALESCE((SELECT MAX(%I) FROM public.%I), 0), 1), (SELECT MAX(%I) FROM public.%I) IS NOT NULL)',
                     r.seq, r.col, r.tbl, r.col, r.tbl);
    END IF;
  END LOOP;
END $$;
COMMIT;

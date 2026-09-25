# NCS IT Officer & Systems Administrator Dashboard Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/it-officer-dashboard-plan.md`  
> **Role:** Head of IT / Systems Administrator / IT Officer  
> **Department:** Information & Communications Technology (ICT) Department - National Council of Sports (NCS)  
> **Authority Scope:** Server Operations, Database Backup Automation, User RBAC Provisioning, System Security, IT Helpdesk Queue, Hardware Assets  
> **Universal Scope:** My Activities, Direct Messages, Real-Time Notifications, Profile, Security Settings, and Personal Leave Application & Approval Portal  
> **Compliance Standards:** Computer Misuse Act (Uganda), NITA-Uganda IT Standards, ISO 27001 Cybersecurity Framework  

---

## 1. Executive Role Overview & Mission

The **IT / ICT Department** is responsible for maintaining 24/7 intranet uptime, database backup integrity, network security, user access control, IT service desk ticketing, and enterprise hardware infrastructure.

This plan details the **IT Infrastructure Command Center** inside `NCS_Intranet`. Systems Administrators use this dashboard to monitor server metrics, manage database backups (`BackupsView.vue`), control user accounts (`UsersView.vue`), enforce 2FA security (`SecuritySettingsView.vue`), and resolve IT helpdesk service tickets (`HelpdeskDashboardView.vue`), while maintaining personal workplace access.

---

## 2. Key Modules & User Interface Specifications

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | IT Infrastructure & Systems Command Center   [🔄 Backup Now] [➕ Add User] |
+-----------------------------------------------------------------------------------+
| [SYSTEM INFRASTRUCTURE HEALTH CARDS]                                             |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | System Uptime      | | Database Backup    | | Open IT Tickets   | | Active User Sessions| |
| | 99.98% (Online)    | | SUCCESS (02:00 AM)| | 3 Pending SLA   | | 42 Users Logged In| |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [IT WORKSPACE: (1) System Operations | (2) User RBAC | (3) IT Helpdesk | (4) Backups]    |
| +-------------------------------------------------------------------------------+ |
| | [ENTERPNCS IT HELPDESK & TICKET MANAGEMENT QUEUE]                            | |
| | Ticket ID  | User / Dept      | Category       | Issue Description   | SLA Status | |
| | T-2026-042 | Musoke (Eng)     | Printer Conn   | Kyocera Driver Error| In Progress| |
| | T-2026-045 | Akello (Finance) | Password Reset | Account Locked      | Resolved   | |
| | T-2026-048 | Admin (HR)       | Network Drop   | Switch Port #14 Down| High Priority|
| | [ Assign Ticket ] [ Update SLA ] [ Resolve & Close ] [ Escalated to HOD ]     | |
| +-------------------------------------------------------------------------------+ |
| +-----------------------------------------------+ +-------------------------------+ |
| | System Backup & Disaster Recovery Log         | | Universal Personal Suite       | |
| | PostgreSQL DB Backup: 42.8 MB (Encrypted S3)  | | - [LEAVE] Annual Request (Pass) | |
| | Media Artifacts Sync: SUCCESS                 | | - [ACTIVITIES] 19 Tasks Done| |
| | Next Scheduled Run  : Today 02:00 AM          | | - [MESSAGES] 4 Tech Chats   | |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 3. Universal Shared Personal Workplace Suite (For All IT Staff)

In addition to system administration and helpdesk tools, every user in the ICT Department receives:

1. **Personal Leave Application & Status Portal (`LeaveApplyView.vue`):**
   - Submit leave applications (Annual, Sick, Compassionate, Study).
   - Real-time approval tracking: `Submitted` $\rightarrow$ `IT Manager Recommendation` $\rightarrow$ `HR Verification` $\rightarrow$ `GS Approval`.
   - Personal leave balance summary and IT Department duty roster.
2. **My Activities Audit Stream (`MyActivitiesView.vue`):**
   - Personal log tracking tickets resolved, backups executed, user roles updated, and system maintenance events.
3. **Direct Messages & Inter-Office Memos:**
   - Internal chat drawer and E-Memo system for communicating with staff, HODs, and HR.
4. **Notifications Center (`🔔`):**
   - Real-time alerts for incoming helpdesk tickets, system backup statuses, leave decisions, and GS broadcasts.
5. **My Profile Management (`ProfileView.vue`):**
   - Bio data, staff ID, digital signature upload for IT clearance sign-offs, avatar upload.
6. **Account & Security Settings (`SecuritySettingsView.vue`):**
   - Password management, 2FA TOTP setup, active session termination, and security logs.

---

## 4. Database Schema & Technical Architecture

```sql
CREATE TABLE it_helpdesk_tickets (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    ticket_number VARCHAR(32) UNIQUE NOT NULL,
    requester_id UUID NOT NULL REFERENCES users(id),
    category VARCHAR(64) NOT NULL, -- HARDWARE, SOFTWARE, NETWORK, ACCESS, PRINTER
    priority VARCHAR(32) NOT NULL DEFAULT 'MEDIUM', -- LOW, MEDIUM, HIGH, URGENT
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    assigned_to UUID REFERENCES users(id),
    status VARCHAR(32) NOT NULL DEFAULT 'OPEN', -- OPEN, IN_PROGRESS, RESOLVED, CLOSED
    resolution_notes TEXT,
    closed_at TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

---

## 5. REST API Mapping (`backend/internal/handlers/it_officer_handler.go`)

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/api/v1/it/system-health` | GET | Query server CPU, memory, database, and storage health metrics |
| `/api/v1/it/helpdesk/tickets` | GET/POST | Query and submit IT helpdesk tickets |
| `/api/v1/user/leave/apply` | POST | IT staff personal leave application submittal |
| `/api/v1/user/activities` | GET | Personal IT activity log query |

---

## 6. Implementation Verification Roadmap

- [ ] Create Go backend handler `backend/internal/handlers/it_officer_handler.go`.
- [ ] Build Vue frontend view `frontend/src/views/it/ITOfficerDashboard.vue`.
- [ ] Verify personal leave application and universal workplace modules for all IT staff.

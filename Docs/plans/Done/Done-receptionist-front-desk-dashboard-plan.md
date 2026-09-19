# NCS Receptionist Front Desk & Visitor Clearance Dashboard Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/receptionist-front-desk-dashboard-plan.md`  
> **Role:** Receptionist / Front Desk Officer / Helpdesk-adjacent front office staff  
> **System Scope:** Dedicated role branch inside `frontend/src/views/DashboardView.vue` plus reception views (`ReceptionistVisitorView.vue`, `VisitorInitiateView.vue`, `ReceptionistLeaveApplyView.vue`, `ReceptionistLeaveStatusView.vue`, `ReceptionistReportsView.vue`)  
> **Authority Scope:** Visitor intake, host approval tracking, pass issuance, check-in/check-out control, annual leave self-service, front desk reports  
> **Source Verification:** Added after comparing dashboard role branches with `Docs/plans/`; the receptionist/front desk dashboard was present in code but did not have a dedicated plan document.

---

## 1. Executive Purpose & Front Desk Operations Architecture

The **Receptionist Front Desk Dashboard** is the operational command desk for visitor clearance and receptionist self-service inside the NCS intranet. It is selected in `DashboardView.vue` when the authenticated user has `receptionist` or `helpdesk` role and does not hold privileged executive/admin roles.

It consolidates four critical front desk duties:

1. Register and track visitor pass requests.
2. Monitor host approval, badge issuance, check-in, and check-out status.
3. Provide annual leave application and balance visibility for front desk staff.
4. Surface front desk reporting links for daily visitor flow and leave handover oversight.

```
+--------------------------------------------------------------------------------------------------+
|                         RECEPTIONIST FRONT DESK DASHBOARD                                        |
+--------------------------------------------------------------------------------------------------+
| Role Gate: receptionist/helpdesk, excluding admin/super_admin/general_secretary                   |
| Primary View: DashboardView.vue, receptionist branch                                              |
+-------------------------+-------------------------+-------------------------+--------------------+
| Total Visitors          | Pending Approval        | On Premises            | Leave Balance      |
| visitor_passes count    | PENDING_APPROVAL count  | CHECKED_IN count       | 21 - used days     |
+-------------------------+-------------------------+-------------------------+--------------------+
| Quick Actions: Visitor Clearance Desk | Apply for Leave | Leave Status & History | Reports          |
+--------------------------------------------------------------------------------------------------+
| Recent Visitor Passes table: pass no., visitor, target department, host officer, status           |
+--------------------------------------------------------------------------------------------------+
| Leave Entitlement summary: utilized days, relieving officer, latest approval status               |
+--------------------------------------------------------------------------------------------------+
```

---

## 2. Dashboard Coverage Verification

| Dashboard Item Found in Code | Source Location | Plan Coverage Status |
| :--- | :--- | :--- |
| Receptionist role branch | `frontend/src/views/DashboardView.vue` `isReceptionist` branch | Covered by this plan |
| Visitor Clearance Desk quick action | `/reception/visitors` route | Covered by this plan |
| Apply for Leave quick action | `/reception/leave/apply` route | Covered by this plan |
| Leave Status & History quick action | `/reception/leave/status` route | Covered by this plan |
| Front Desk Reports quick action | `/reception/reports` route | Covered by this plan |
| Visitor pass lifecycle API | `/api/v1/reception/visitors*` endpoints | Covered by this plan |
| Reception leave API | `/api/v1/reception/leave/*` endpoints | Covered by this plan |

---

## 3. Key Modules & User Interface Specifications

### 3.1 Hero Status Card

- Displays time-based greeting and the receptionist's resolved profile name.
- Shows the operational description: **Front Desk & Visitor Clearance Operations - National Council of Sports**.
- Presents a live **Front Desk Active** badge for role clarity and shift readiness.

### 3.2 KPI Cards

| KPI Card | Calculation / Data Source | Purpose |
| :--- | :--- | :--- |
| Total Visitors | `receptionVisitors.length` from `/api/v1/reception/visitors` | Total visitor passes loaded for the current front desk view |
| Pending Approval | Count of `PENDING_APPROVAL` visitor records | Highlights visitors awaiting host or officer clearance |
| On Premises | Count of `CHECKED_IN` visitor records | Shows active visitors currently inside NCS premises |
| Leave Balance | `21 - approved annual leave days` | Gives the receptionist immediate leave entitlement visibility |

### 3.3 Front Desk Quick Action Navigation

| Action | Frontend Route | Functional Intent |
| :--- | :--- | :--- |
| Visitor Clearance Desk | `/reception/visitors` | Open full visitor pass registry and clearance queue |
| Apply for Leave | `/reception/leave/apply` | Submit receptionist leave application with relieving officer handover details |
| Leave Status & History | `/reception/leave/status` | Review submitted leave applications and current approval state |
| Front Desk Reports | `/reception/reports` | Review visitor inflow, pass status distribution, and reporting summaries |

### 3.4 Recent Visitor Passes Table

The dashboard lists the five most recent visitor passes with:

- Pass number.
- Visitor name and organization or phone.
- Target department.
- Host officer name.
- Current clearance status badge.

Supported statuses include `PENDING_APPROVAL`, `APPROVED`, `CHECKED_IN`, `COMPLETED`, and `REJECTED`.

### 3.5 Leave Entitlement Summary

The leave summary card displays:

- Statutory annual leave allocation of 21 days.
- Approved annual leave days utilized.
- Available leave balance.
- Latest relieving officer.
- Latest request status.
- Shortcut to submit a new leave request.

---

## 4. Database Schema & Data Model

### 4.1 Visitor Passes

Implemented by migration `backend/migrations/073_create_reception_visitors.sql`.

| Field | Purpose |
| :--- | :--- |
| `pass_number` | Unique front desk pass identifier |
| `visitor_name`, `visitor_phone`, `visitor_id_number` | Visitor identity and contact record |
| `visitor_organization` | External organization or delegation |
| `target_department` | NCS destination department |
| `host_officer_name` | Officer expected to receive the visitor |
| `purpose_of_visit` | Business justification for clearance |
| `status` | Visitor lifecycle status |
| `badge_number`, `clearance_code` | Gate pass issuance metadata |
| `checked_in_at`, `checked_out_at` | Premises occupancy timestamps |
| `vehicle_reg_no`, `items_declared`, `remarks` | Security desk supporting fields |

### 4.2 Staff Leave Applications

Implemented by migrations `074_create_staff_leave_applications.sql` and `075_expand_staff_leave_fields.sql`.

| Field Group | Purpose |
| :--- | :--- |
| Staff identity | Staff name, department, role, file number, contact phone, contact email |
| Leave period | Leave type, start date, end date, return date, days requested |
| Handover | Relieving officer name, role, duty handover details |
| Welfare and emergency | Address while on leave and emergency phone |
| Approval tracking | Status, approving user, approval timestamp, supervisor remarks |

---

## 5. REST API Mapping

| Endpoint | Method | Function | Dashboard Usage |
| :--- | :--- | :--- | :--- |
| `/api/v1/reception/visitors` | GET | List visitor passes | KPI cards and recent visitors table |
| `/api/v1/reception/visitors` | POST | Create visitor pass | Visitor clearance desk intake |
| `/api/v1/reception/visitors/{id}` | GET | Fetch visitor pass detail | Pass detail and print workflow |
| `/api/v1/reception/visitors/{id}/approve` | PUT | Approve visitor clearance | Host/officer approval lifecycle |
| `/api/v1/reception/visitors/{id}/checkin` | PUT | Mark visitor checked in | On-premises KPI update |
| `/api/v1/reception/visitors/{id}/checkout` | PUT | Mark visitor checked out | Completes visitor visit record |
| `/api/v1/reception/leave/apply` | POST | Submit leave request | Apply for Leave action |
| `/api/v1/reception/leave/status` | GET | List leave applications | Leave balance and status summary |
| `/api/v1/reception/reports` | GET | Retrieve reception reports | Front Desk Reports action |

---

## 6. Implementation Verification Roadmap

1. **Plan coverage check:** Confirm `Docs/plans/receptionist-front-desk-dashboard-plan.md` exists for the `DashboardView.vue` receptionist branch.
2. **Role routing check:** Login as `receptionist` and confirm `/dashboard` renders the front desk dashboard, not the privileged executive overview.
3. **Visitor KPI check:** Seed or create visitor passes and confirm Total Visitors, Pending Approval, and On Premises counts update from `/api/v1/reception/visitors`.
4. **Quick action check:** Verify all four front desk quick actions route to the intended pages.
5. **Leave balance check:** Submit approved annual leave and confirm the 21-day entitlement calculation updates correctly.
6. **Report check:** Confirm `/reception/reports` returns daily visitor and leave handover summaries.

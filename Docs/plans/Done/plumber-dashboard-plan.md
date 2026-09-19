# Plumber / Field Tradesman Dashboard Blueprint & Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/plumber-dashboard-plan.md`  
> **Role:** Plumber / Field Trade Technician  
> **Reports To:** Assistant Engineer Civil  
> **Target Device:** Mobile-Responsive / Tablet / Touch-First Desktop UI  

---

## 1. Role Overview & Objectives

The **Plumber Dashboard** is a streamlined, field-optimized interface designed for plumbers working across NCS sports grounds, public washrooms, dressing rooms, and administration blocks.

---

## 2. Mobile-First Navigation & Sidebar Menu Items

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Plumber Field Portal    [🔍 Search...] [🔄] [🌙] [🔔 2] [💬 1] [User] |
+-----------------------------------------------------------------------------------+
| [MOBILE DASHBOARD / NAVIGATION TABS]                                              |
| [ 📋 Jobs ]  [ 📊 My Work Log ]  [ 📜 My Activities ]  [ 🌴 Leave ]  [ 👤 Profile ]  |
+-----------------------------------------------------------------------------------+
| [ MY CURRENT ASSIGNED JOBS (2) ]                                                 |
| +-------------------------------------------------------------------------------+ |
| | WO-2026-00880 | HIGH PRIORITY                                                  | |
| | Location: Lugogo Indoor Arena - Main Washrooms                                | |
| | Description: Burst 50mm PPR water pipe under basin 3 flooding floor.           | |
| | Status: [ IN PROGRESS ]                                                       | |
| | [ 📷 Capture BEFORE Photo ]  [ 📷 Capture AFTER Photo ]                         | |
| | [ 🛠️ Request Parts / Fittings ]                                              | |
| | [ MARK JOB AS COMPLETED (Submit for Inspection) ]                             | |
| +-------------------------------------------------------------------------------+ |
+-----------------------------------------------------------------------------------+
| [STICKY BOTTOM NAV BAR (MOBILE)]                                                  |
| [ 🏠 Home ]   [ 📋 Jobs ]   [ 📜 Activities ]   [ 💬 Chat ]   [ ⚙️ Settings ]      |
+-----------------------------------------------------------------------------------+
```

### Functional Features
- **My Activities Menu Item:** Accessible via sidebar or mobile bottom bar (`📜 Activities`). Displays personal action audit history (work orders completed, camera photos uploaded, fittings requested, leave applications submitted).
- **Navbar & Controls:** Light/Dark theme toggle, search, refresh, notifications drawer, and direct chat.

---

## 3. "My Activities" Personal Audit Log for Plumbers

```json
// GET /api/v1/intranet/my-activities?category=WORK_ORDER
{
  "status": "success",
  "data": [
    {
      "id": "act-135",
      "action_type": "WO_STATUS_COMPLETED",
      "description": "Marked Work Order WO-0880 as COMPLETED and uploaded Before/After photos.",
      "target_resource_id": "WO-0880",
      "created_at": "2026-07-29T12:05:00Z"
    }
  ]
}
```

---

## 4. Go REST API & Checklist

| Endpoint | Method | Scope | Description |
| :--- | :--- | :--- | :--- |
| `/api/v1/engineering/work-orders/my-jobs` | GET | Plumber | List assigned jobs |
| `/api/v1/intranet/my-activities` | GET | Self Only | Query personal account audit log |

- [ ] **Step 1:** Add **"My Activities"** link to plumber mobile navigation bar and sidebar.
- [ ] **Step 2:** Test personal activity audit query for Plumber user account.
